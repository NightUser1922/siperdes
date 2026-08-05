<?php

return [
    'auth_type' => env('GOOGLE_DRIVE_AUTH_TYPE', 'oauth'),
    'service_account_json' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON', storage_path('app/google-drive-service-account.json')),
    'oauth_client_id' => env('GOOGLE_DRIVE_OAUTH_CLIENT_ID'),
    'oauth_client_secret' => env('GOOGLE_DRIVE_OAUTH_CLIENT_SECRET'),
    'oauth_redirect_uri' => env('GOOGLE_DRIVE_OAUTH_REDIRECT_URI', env('APP_URL') . '/google-drive/callback'),
    'oauth_token_path' => env('GOOGLE_DRIVE_OAUTH_TOKEN_PATH', 'google-drive-oauth-token.json'),
    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
];
