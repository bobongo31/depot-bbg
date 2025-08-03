<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    // Compteur total des notifications
    public function getNotificationCount()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        $totalCount = 0;

        $tablesWithService = [
            'courriers',
            'accuse_receptions',
            'telegrammes',
            'reponses',
        ];

        foreach ($tablesWithService as $table) {
            try {
                $count = DB::table($table)
                    ->where(function ($query) use ($user) {
                        $query->where('service_concerne', $user->service)
                              ->orWhere('user_id', $user->id);
                    })
                    ->where('user_id', '!=', $user->id)
                    ->count();

                $totalCount += $count;
            } catch (\Exception $e) {
                continue;
            }
        }

        // Messages non lus reçus par l'utilisateur
        try {
            $messageCount = DB::table('messages')
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            $totalCount += $messageCount;
        } catch (\Exception $e) {
            // Ignorer l'erreur
        }

        return response()->json(['count' => min($totalCount, 100)]);
    }

    // Liste détaillée des notifications
    public function getNotificationsList()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([]);
    }

    $tablesWithLabels = [
        'courriers' => '📄 Vous avez reçu un nouveau courrier.',
        'accuse_receptions' => '✅ Un accusé de réception a été enregistré.',
        'telegrammes' => '📨 Un télégramme vous a été adressé.',
        'reponses' => '✉️ Une réponse a été publiée.',
    ];

    $notifications = collect();

    foreach ($tablesWithLabels as $table => $message) {
        try {
            $rows = DB::table($table)
                ->where(function ($query) use ($user) {
                    $query->where('service_concerne', $user->service)
                          ->orWhere('user_id', $user->id);
                })
                ->where('user_id', '!=', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['created_at']);

            foreach ($rows as $row) {
                $notifications->push([
                    'created_at' => $row->created_at,
                    'content' => $message,
                    'type' => $table,
                ]);
            }
        } catch (\Exception $e) {
            continue;
        }
    }

    // Messages non lus reçus par l'utilisateur
    try {
        $rows = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['created_at']);

        foreach ($rows as $row) {
            $notifications->push([
                'created_at' => $row->created_at,
                'content' => '💬 Vous avez reçu un nouveau message.',
                'type' => 'messages',
            ]);
        }
    } catch (\Exception $e) {
        // Ignorer l'erreur
    }

    $sorted = $notifications->sortByDesc('created_at')->take(20)->values()->all();

    return response()->json($sorted);
}

}
