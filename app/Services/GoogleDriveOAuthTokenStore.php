<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class GoogleDriveOAuthTokenStore
{
    public function get(): ?array
    {
        $path = $this->path();

        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $contents = Storage::disk('local')->get($path);
        $decoded = json_decode(Crypt::decryptString($contents), true);

        return is_array($decoded) ? $decoded : null;
    }

    public function put(array $token): void
    {
        $existing = $this->get() ?? [];

        if (empty($token['refresh_token']) && !empty($existing['refresh_token'])) {
            $token['refresh_token'] = $existing['refresh_token'];
        }

        if (!empty($token['expires_in'])) {
            $token['expires_at'] = Carbon::now()->addSeconds((int) $token['expires_in'])->toIso8601String();
        }

        Storage::disk('local')->put($this->path(), Crypt::encryptString(json_encode($token)));
    }

    public function isConnected(): bool
    {
        $token = $this->get();

        return !empty($token['access_token']) || !empty($token['refresh_token']);
    }

    private function path(): string
    {
        return (string) config('google_drive.oauth_token_path', 'google-drive-oauth-token.json');
    }
}
