<?php

return [
    'service_account_json' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON', storage_path('app/google-drive-service-account.json')),
    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
];