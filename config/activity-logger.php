<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Activity Logger
    |--------------------------------------------------------------------------
    |
    | List the models you want to auto-log here. Use fully-qualified class
    | names. The observer will listen for created/updated/deleted events and
    | record them via Spatie activity().
    |
    */

    'models' => [
        // Add model classes you want auto-logged here.
        \App\Models\User::class,
        // Spatie Permission models
        \Spatie\Permission\Models\Role::class,
        \Spatie\Permission\Models\Permission::class,
    ],

    // Default attributes to include in properties when logging (null = all attributes)
    'attributes' => null,
];
