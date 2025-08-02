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

        $userIds = User::where('service', $user->service)->pluck('id')->toArray();

        $tables = [
            'courriers',
            'accuse_receptions',
            'messages',
            'telegrammes',
            'reponses',
        ];

        $totalCount = 0;

        foreach ($tables as $table) {
            try {
                $count = DB::table($table)
                    ->whereIn('user_id', $userIds)
                    ->count();

                $totalCount += $count;
            } catch (\Exception $e) {
                continue;
            }
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

        $userIds = User::where('service', $user->service)->pluck('id')->toArray();

        $tablesWithLabels = [
            'courriers' => '📄 Nouveau courrier enregistré',
            'accuse_receptions' => '✅ Nouvel accusé de réception',
            'messages' => '💬 Nouveau message reçu',
            'telegrammes' => '📨 Nouveau télégramme reçu',
            'reponses' => '✉️ Nouvelle réponse envoyée',
        ];

        $notifications = collect();

        foreach ($tablesWithLabels as $table => $message) {
            try {
                $rows = DB::table($table)
                    ->whereIn('user_id', $userIds)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(['id', 'created_at']);

                foreach ($rows as $row) {
                    $notifications->push([
                        'id' => $row->id,
                        'created_at' => $row->created_at,
                        'message' => $message,
                        'type' => $table,
                    ]);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Trier toutes les notifications confondues par date
        $sorted = $notifications->sortByDesc('created_at')->take(20)->values()->all();

        return response()->json($sorted);
    }
}
