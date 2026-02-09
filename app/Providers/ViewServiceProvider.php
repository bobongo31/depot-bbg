<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    View::composer('*', function ($view) {
        $notifications = collect();
        $count = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $userIds = User::where('service', $user->service)->pluck('id')->toArray();

            $tables = [
                'courriers',
                'accuse_receptions',
                'messages',
                'telegrammes',
                'reponses',
            ];

            foreach ($tables as $table) {
                try {
                    $countTable = DB::table($table)
                        ->whereIn('user_id', $userIds)
                        ->count();
                    $count += $countTable;

                    $rows = DB::table($table)
                        ->whereIn('user_id', $userIds)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get(['id', 'created_at']);

                    foreach ($rows as $row) {
                        $notifications->push([
                            'id' => $row->id,
                            'created_at' => $row->created_at,
                            'type' => $table,
                        ]);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $count = min($count, 100);
        $notifications = $notifications->sortByDesc('created_at')->take(20);

        $view->with('notificationCount', $count)
             ->with('notifications', $notifications);
    });
}

    public function register(): void
    {
        //
    }
}
