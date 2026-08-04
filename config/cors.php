<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173', 'http://localhost:3000', // رابط لوحة الـ Vue
        'http://localhost:5500', 'http://127.0.0.1:5500',  // رابط موقع "وفر كاش" الثابت (VS Code Live Server)
        'http://localhost:8080', 'http://127.0.0.1:8080',  // بديل لو تم تشغيله عبر سيرفر بورت 8080
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
