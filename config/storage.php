<?php

return [
    // Storage limit per tier, in bytes.
    'tiers' => [
        'normal'  => 500 * 1024,          // 500 KB
        'premium' => 5 * 1024 * 1024,   // 5 MB
    ],

    // Notify the user once usage crosses this percentage.
    'warning_threshold' => 80,

    // Days an item must stay in trash before it can be permanently deleted.
    'trash_retention_days' => 2,

    // Where premium users are told to contact for subscription storage.
    'support_email' => env('SUPPORT_EMAIL', 'support@cloudnest.test'),
];
