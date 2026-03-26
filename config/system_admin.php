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
        // Default backup scope: 'db' or 'storage'
        'default_scope' => env('SYSTEM_BACKUP_DEFAULT_SCOPE', 'db'),
        // Storage-specific backup options (used when scope = 'storage')
        'storage' => [
            'path' => env('SYSTEM_BACKUP_STORAGE_PATH', 'backups/storage'),
            'disk' => env('SYSTEM_BACKUP_STORAGE_DISK', env('FILESYSTEM_DRIVER', 'local')),
            // Semicolon-separated list of absolute paths to copy storage backups to
            // Example Windows: C:/Users/Administrateur/Documents;C:/Users/Administrateur/Desktop
            'copy_to' => array_filter(explode(';', env('SYSTEM_BACKUP_STORAGE_COPY_TO', ''))),
            // Enable automatic copying to the destinations configured in `copy_to`
            'copy_enabled' => env('SYSTEM_BACKUP_STORAGE_COPY_ENABLED', true),
            // If true, only perform copies when the source disk is 'local'
            'copy_only_if_local' => env('SYSTEM_BACKUP_STORAGE_COPY_ONLY_IF_LOCAL', true),
        ],
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
        // Daily storage (files) backup
        'backup-storage-daily' => [
            'label' => 'Sauvegarde fichiers quotidienne',
            'command' => 'system:backup',
            'arguments' => ['--trigger' => 'scheduled', '--scope' => 'storage'],
            'frequency' => 'Tous les jours à 03:00',
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