<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\AnnexeMessage;
use App\Models\User; // Ajout de l'import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller 
{
    // MessageController.php

    public function index()
{
    // Récupérer tous les utilisateurs sauf l'utilisateur actuellement connecté
    $users = User::where('id', '!=', auth()->id())->get();


    
    // Récupérer les conversations existantes en fusionnant les destinataires des messages envoyés et les expéditeurs des messages reçus
    $sentUsers = Message::where('sender_id', auth()->id())
        ->with('receiver')
        ->get()
        ->pluck('receiver');

    $receivedUsers = Message::where('receiver_id', auth()->id())
        ->with('sender')
        ->get()
        ->pluck('sender');

    $conversations = $sentUsers->merge($receivedUsers)->unique('id');

    // Passer les variables à la vue
    return view('messages.index', compact('users', 'conversations'));
}

    



    public function store(Request $request) 
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'annexes.*' => 'file|max:2048' // Fichiers max 2MB
        ]);

        // Créer le message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content
        ]);

        // Enregistrer les fichiers annexes s'il y en a
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                $path = $file->store('annexes', 'public');

                AnnexeMessage::create([
                    'message_id' => $message->id,
                    'file_path' => $path
                ]);
            }
        }

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
    $authUserId = auth()->id();
    
    // Récupérer l'utilisateur sélectionné
    $selectedUser = User::findOrFail($userId);
    
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
    
    // Récupérer les conversations existantes
    $conversations = User::whereHas('messages', function ($query) use ($authUserId) {
        $query->where('sender_id', $authUserId)
              ->orWhere('receiver_id', $authUserId);
    })->get();
    
    // Récupérer tous les utilisateurs sauf l'utilisateur connecté
    $users = User::where('id', '!=', auth()->id())->get();
    
    return view('messages.index', compact('conversations', 'messages', 'selectedUser', 'users'));
}

}
