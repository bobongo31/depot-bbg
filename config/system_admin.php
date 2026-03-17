<?php

return [

    'role_admins' => ['ADMIN', 'SUPERADMIN'],

    'backup' => [
        'disk' => 'local',
        'path' => 'backups',
        'retention_days' => env('SYSTEM_BACKUP_RETENTION_DAYS', 30),
        'mysqldump_path' => env('MYSQLDUMP_PATH', 'mysqldump'),
        'mysql_path' => env('MYSQL_CLIENT_PATH', 'mysql'),
        'restore_enabled' => env('ADMIN_RESTORE_ENABLED', false),
    ],

    'health' => [
        'disk_min_free_gb' => env('SYSTEM_DISK_MIN_FREE_GB', 5),
    ],

    'tasks' => [
        'backup-daily' => [
            'label' => 'Sauvegarde quotidienne',
            'command' => 'system:backup',
            'arguments' => ['--trigger' => 'scheduled'],
            'frequency' => 'Tous les jours à 01:30',
            'even_in_maintenance' => false,
            'enabled' => true,
        ],
        'backup-prune' => [
            'label' => 'Purge anciennes sauvegardes',
            'command' => 'system:backups-prune',
            'arguments' => [],
            'frequency' => 'Tous les jours à 02:00',
            'even_in_maintenance' => false,
            'enabled' => true,
        ],
        'health-poll' => [
            'label' => 'Contrôle santé et synchronisation alertes',
            'command' => 'system:health:poll',
            'arguments' => [],
            'frequency' => 'Toutes les 5 minutes',
            'even_in_maintenance' => true,
            'enabled' => true,
        ],
    ],

    'queue_names' => ['default'],
];