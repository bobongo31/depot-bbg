<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\AnnexeMessage;
use App\Models\User; // Ajout de l'import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;


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
    $unreadCount = Message::where('receiver_id', auth()->id())
                         ->where('is_read', false)
                         ->count();

    return response()->json(['unread_count' => $unreadCount]);
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

}
