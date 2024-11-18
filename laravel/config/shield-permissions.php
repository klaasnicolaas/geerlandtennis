<?php

return [
    'roles' => [
        'admin' => [
            'guard_name' => 'web',
            'permissions' => [
                'resource' => [
                    'role' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'],
                    'user' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any', 'restore', 'restore_any', 'replicate', 'reorder', 'force_delete', 'force_delete_any'],
                    'team' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'],
                    'tennis-match' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'],
                    'tennis-set' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'],
                    'tournament' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'],
                ],
                'special' => [
                    'download-backup',
                    'delete-backup',
                ],
                'page' => [
                    'Backups',
                ],
            ],
        ],
        'moderator' => [
            'guard_name' => 'web',
            'permissions' => [
                'resource' => [
                    'role' => ['view', 'view_any'],
                    'user' => ['view', 'view_any'],
                    'tournament' => ['view_any'],
                ],
            ],
        ],
        'user' => [
            'guard_name' => 'web',
            'permissions' => [
                'resource' => [
                    'tournament' => ['view_any'],
                ],
            ],
        ],
    ],
    'direct_permissions' => [
        'download-backup' => 'web',
        'delete-backup' => 'web',
    ],
];
