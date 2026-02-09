<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\AnnexeMessage;
use App\Models\User; // Ajout de l'import
use App\Models\Telegramme;
use App\Models\Reponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller 
{
    // MessageController.php

    public function index()
{
    $userEntreprise = auth()->user()->entreprise;
    $authUserId = auth()->id();

    // Récupérer les utilisateurs de la même entreprise, sauf l'utilisateur connecté
    $users = User::where('id', '!=', $authUserId)
                 ->where('entreprise', $userEntreprise)
                 ->get();

    // Récupérer les conversations (uniquement avec utilisateurs de la même entreprise)
    $sentUsers = Message::where('sender_id', $authUserId)
        ->with('receiver')
        ->get()
        ->pluck('receiver')
        ->filter(function ($user) use ($userEntreprise) {
            return $user->entreprise === $userEntreprise;
        });

    $receivedUsers = Message::where('receiver_id', $authUserId)
        ->with('sender')
        ->get()
        ->pluck('sender')
        ->filter(function ($user) use ($userEntreprise) {
            return $user->entreprise === $userEntreprise;
        });

    $conversations = $sentUsers->merge($receivedUsers)->unique('id');

    return view('messages.index', compact('users', 'conversations'));
}


    



public function store(Request $request) 
{
    // Validation des données de la requête
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'content' => 'required|string',
        'annexes.*' => 'file|max:2048' // Taille max 2MB
    ]);

    // Récupérer l'utilisateur récepteur
    $receiver = User::findOrFail($request->receiver_id);

    // Vérifier si le récepteur est dans la même entreprise que l'expéditeur
    if ($receiver->entreprise !== auth()->user()->entreprise) {
        return back()->withErrors('Vous ne pouvez envoyer un message qu\'aux utilisateurs de la même entreprise.');
    }

    // Création du message
    $message = Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'content' => $request->content
    ]);

    // Vérification et enregistrement des fichiers annexes
    if ($request->hasFile('annexes')) {
        foreach ($request->file('annexes') as $file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Vérification stricte de l'extension
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'])) {
                return back()->withErrors("Extension non autorisée : .$extension");
            }

            // Vérification du type MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file->getPathname());
            finfo_close($finfo);

            $allowedMimeTypes = [
                'image/jpeg', 'image/png', 'application/pdf',
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            if (!in_array($mime, $allowedMimeTypes)) {
                return back()->withErrors("Type MIME non autorisé : $mime");
            }

            // Vérification avancée du .docx
            if ($mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                $zip = new ZipArchive;
                if ($zip->open($file->getPathname()) === TRUE) {
                    $requiredFiles = [
                        'word/document.xml', // Contenu principal
                        '[Content_Types].xml', // Fichier obligatoire
                        'docProps/core.xml' // Métadonnées
                    ];

                    foreach ($requiredFiles as $fileName) {
                        if ($zip->locateName($fileName) === false) {
                            $zip->close();
                            return back()->withErrors('Fichier .docx invalide : fichiers internes manquants.');
                        }
                    }

                    // Vérifier que word/document.xml contient du texte
                    $content = $zip->getFromName('word/document.xml');
                    if (empty(trim($content))) {
                        $zip->close();
                        return back()->withErrors('Fichier .docx corrompu ou vide.');
                    }

                    $zip->close();
                } else {
                    return back()->withErrors('Impossible d’ouvrir le fichier .docx.');
                }
            }

            // Enregistrer le fichier après toutes les validations
            $path = $file->store('annexes', 'public');

            AnnexeMessage::create([
                'message_id' => $message->id,
                'file_path' => $path
            ]);
        }
    }

    // Retourner avec un message de succès
    return redirect()->back()->with('success', 'Message envoyé avec succès.');
}




    public function unreadCount()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['unread_count' => 0]);
        }

        // unread direct messages
        $messageCount = Message::where('receiver_id', $user->id)
                         ->where('is_read', false)
                         ->count();

        // telegrammes relevant to user's services or addressed to the user and not yet handled (no reponse)
        $telegrammeCount = 0;
        $userServices = json_decode($user->service, true) ?: [];

        $tquery = Telegramme::query()->whereDoesntHave('reponses');

        if (!empty($userServices) && is_array($userServices)) {
            // try JSON contains first
            foreach ($userServices as $svc) {
                $tquery->orWhereJsonContains('service_concerne', $svc);
                // also fallback to LIKE in case service_concerne is stored as CSV/text
                $tquery->orWhere('service_concerne', 'like', '%' . $svc . '%');
            }
        }

        // also telegrammes explicitly created by or for this user
        $tquery->orWhere('user_id', $user->id);

        $telegrammeCount = $tquery->distinct()->count();

        return response()->json(['unread_count' => ($messageCount + $telegrammeCount)]);
    }

    /**
     * Return a list of recent notifications (messages + telegrammes) for the user
     */
    public function notificationsList()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([]);
        }

        $notifications = [];

        // unread messages
        $messages = Message::where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->with('sender')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();

        foreach ($messages as $m) {
            $notifications[] = [
                'type' => 'message',
                'id' => $m->id,
                'created_at' => $m->created_at->toDateTimeString(),
                'content' => trim($m->content) ?: 'Nouveau message',
                'from' => $m->sender ? $m->sender->name ?? $m->sender->email : null,
                'url' => url('/messages/' . ($m->sender_id ?? $m->sender->id ?? '')),
            ];
        }

        // telegrammes matching user's services or addressed to user and without responses
        $userServices = json_decode($user->service, true) ?: [];

        $tquery = Telegramme::with('annexes')->whereDoesntHave('reponses');

        if (!empty($userServices) && is_array($userServices)) {
            $tquery->where(function($q) use ($userServices) {
                foreach ($userServices as $svc) {
                    $q->orWhereJsonContains('service_concerne', $svc);
                    $q->orWhere('service_concerne', 'like', '%' . $svc . '%');
                }
            });
        } else {
            // if user has no services, still include telegrammes explicitly addressed to them
            $tquery->where('user_id', $user->id);
        }

        // also include telegrammes created for this user
        $tquery->orWhere('user_id', $user->id);

        $telegrammes = $tquery->orderBy('created_at', 'desc')->limit(20)->get();

        foreach ($telegrammes as $t) {
            $notifications[] = [
                'type' => 'telegramme',
                'id' => $t->id,
                'created_at' => $t->created_at->toDateTimeString(),
                'content' => 'Télégramme: ' . (
                    strlen($t->objet ?? '') ? $t->objet : ($t->numero_enregistrement ?? 'Télégramme reçu')
                ),
                'from' => $t->user_id ? ('Utilisateur #' . $t->user_id) : null,
                'url' => url('/telegramme/' . $t->id),
            ];
        }

        // sort by created_at desc and limit to 20
        usort($notifications, function($a, $b){ return strtotime($b['created_at']) <=> strtotime($a['created_at']); });
        $notifications = array_slice($notifications, 0, 20);

        return response()->json($notifications);
    }

public function startConversation(Request $request)
{
    $receiverId = $request->input('receiver_id');

    // Vérifier si le receiver_id est valide
    if (!$receiverId) {
        return back()->withErrors('Le destinataire est invalide.');
    }

    // Vérifier si une conversation existe déjà
    $existingConversation = Message::where(function ($query) use ($receiverId) {
        $query->where('sender_id', auth()->id())
              ->where('receiver_id', $receiverId);
    })->orWhere(function ($query) use ($receiverId) {
        $query->where('sender_id', $receiverId)
              ->where('receiver_id', auth()->id());
    })->exists();

    // Si la conversation existe déjà, rediriger vers cette conversation
    if ($existingConversation) {
        return redirect()->route('messages.show', $receiverId);
    }

    // Créer le premier message
    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $receiverId,
        'content' => 'Hello, je démarre cette conversation.',
    ]);

    // Rediriger vers la page des messages après la création
    return redirect()->route('messages.index');
}



public function destroy($id)
{
    $message = Message::findOrFail($id);
    
    // Vérifier si l'utilisateur est le destinataire ou l'expéditeur du message
    if ($message->sender_id == auth()->id() || $message->receiver_id == auth()->id()) {
        $message->delete();
    }

    return redirect()->back()->with('success', 'Message supprimé avec succès.');
}

public function transfer(Request $request)
{
    $message = Message::findOrFail($request->message_id);
    $receiver = User::findOrFail($request->receiver_id);

    // Créer le nouveau message transféré
    $newMessage = Message::create([
        'content' => $message->content,
        'sender_id' => auth()->id(),
        'receiver_id' => $receiver->id,
        'created_at' => now(),
    ]);

    // Transférer les annexes existantes
    foreach ($message->annexes as $annexe) {
        $newMessage->annexes()->create([
            'file_path' => $annexe->file_path,
            'original_name' => $annexe->original_name,
        ]);
    }

    // Ajouter les nouvelles annexes
    if ($request->hasFile('new_annexes')) {
        foreach ($request->file('new_annexes') as $file) {
            $path = $file->store('annexes');
            $newMessage->annexes()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }

    return redirect()->route('messages.show', $receiver->id)->with('success', 'Message transféré avec succès.');
}

public function getAnnexes($messageId)
{
    $message = Message::findOrFail($messageId);
    $annexes = $message->annexes->map(function ($annexe) {
        return [
            'name' => $annexe->original_name,
            'url' => asset('storage/' . $annexe->file_path),
        ];
    });

    return response()->json(['annexes' => $annexes]);
}


public function show($userId)
{
    $authUser = auth()->user();
    $authUserId = $authUser->id;
    
    // Récupérer l'utilisateur sélectionné
    $selectedUser = User::findOrFail($userId);

    // Vérifier si l'utilisateur sélectionné appartient à la même entreprise
    if ($selectedUser->entreprise !== $authUser->entreprise) {
        abort(403, 'Accès non autorisé.');
    }

    // Récupérer les messages de la conversation
    $messages = Message::where(function ($query) use ($authUserId, $userId) {
        $query->where('sender_id', $authUserId)
              ->where('receiver_id', $userId);
    })->orWhere(function ($query) use ($authUserId, $userId) {
        $query->where('sender_id', $userId)
              ->where('receiver_id', $authUserId);
    })->orderBy('created_at', 'asc')->get();

    // Marquer les messages comme lus
    Message::where('sender_id', $userId)
        ->where('receiver_id', $authUserId)
        ->update(['is_read' => true]);

    // Récupérer les utilisateurs avec qui l'utilisateur connecté a déjà discuté
    $conversations = User::whereHas('messages', function ($query) use ($authUserId) {
        $query->where('sender_id', $authUserId)
              ->orWhere('receiver_id', $authUserId);
    })->get();

    // Récupérer uniquement les utilisateurs de la même entreprise (sauf soi-même)
    $users = User::where('entreprise', $authUser->entreprise)
                 ->where('id', '!=', $authUserId)
                 ->get();

    return view('messages.index', compact('conversations', 'messages', 'selectedUser', 'users'));
}

/**
     * Mark a notification (message or telegramme) as read/acknowledged
     */
    public function markNotificationRead(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|in:message,telegramme',
            'id' => 'required|integer',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['ok' => false], 401);
        }

        if ($data['type'] === 'message') {
            $msg = Message::find($data['id']);
            if (!$msg) {
                return response()->json(['ok' => false], 404);
            }
            if ($msg->receiver_id !== $user->id) {
                return response()->json(['ok' => false], 403);
            }
            $msg->is_read = true;
            $msg->save();
            return response()->json(['ok' => true]);
        }

        if ($data['type'] === 'telegramme') {
            // For telegramme we currently don't track per-user read state in DB.
            // As a minimal acknowledgement we simply return success so frontend can navigate.
            // Future: implement a telegramme_user_reads table to track per-user reads.
            $t = Telegramme::find($data['id']);
            if (!$t) {
                return response()->json(['ok' => false], 404);
            }
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false], 400);
    }
}
