<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class GoogleDriveService
{
    private ?Drive $drive = null;

    public function __construct(private GoogleDriveOAuthTokenStore $tokenStore)
    {
    }

    public function oauthAuthUrl(string $state): string
    {
        $client = $this->oauthClient();
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setState($state);

        return $client->createAuthUrl();
    }

    public function storeOAuthToken(string $code): void
    {
        $token = $this->oauthClient()->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new RuntimeException('Google Drive OAuth gagal: ' . ($token['error_description'] ?? $token['error']));
        }

        $this->tokenStore->put($token);
    }

    public function isOAuthConnected(): bool
    {
        return $this->tokenStore->isConnected();
    }

    public function upload(UploadedFile $file, string $namaArsip): array
    {
        $folderId = config('google_drive.folder_id');
        $metadata = new DriveFile([
            'name' => $this->fileName($namaArsip, $file),
            'parents' => $folderId ? [$folderId] : [],
        ]);

        $created = $this->drive()->files->create($metadata, [
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType() ?: $file->getClientMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id,name,mimeType,size',
            'supportsAllDrives' => true,
        ]);

        return [
            'id' => $created->getId(),
            'name' => $created->getName(),
            'mime_type' => $created->getMimeType() ?: $file->getClientMimeType(),
            'size' => (int) ($created->getSize() ?: $file->getSize() ?: 0),
        ];
    }

    public function read(string $fileId): array
    {
        $metadata = $this->metadata($fileId);
        $response = $this->drive()->files->get($fileId, [
            'alt' => 'media',
            'supportsAllDrives' => true,
        ]);

        return [
            'content' => $response->getBody()->getContents(),
            'name' => $metadata['name'],
            'mime_type' => $metadata['mime_type'],
            'size' => $metadata['size'],
        ];
    }

    public function delete(string $fileId): void
    {
        $this->drive()->files->delete($fileId, [
            'supportsAllDrives' => true,
        ]);
    }

    public function metadata(string $fileId): array
    {
        $file = $this->drive()->files->get($fileId, [
            'fields' => 'id,name,mimeType,size',
            'supportsAllDrives' => true,
        ]);

        return [
            'id' => $file->getId(),
            'name' => $file->getName(),
            'mime_type' => $file->getMimeType(),
            'size' => (int) ($file->getSize() ?: 0),
        ];
    }

    private function drive(): Drive
    {
        if ($this->drive) {
            return $this->drive;
        }

        if (config('google_drive.auth_type', 'oauth') === 'service_account') {
            $this->drive = $this->legacyServiceAccountDrive();

            return $this->drive;
        }

        $this->drive = new Drive($this->authorizedOAuthClient());

        return $this->drive;
    }

    private function oauthClient(): Client
    {
        $client = new Client();
        $client->setApplicationName('SIPERDES Arsip Digital');
        $client->setClientId((string) config('google_drive.oauth_client_id'));
        $client->setClientSecret((string) config('google_drive.oauth_client_secret'));
        $client->setRedirectUri((string) config('google_drive.oauth_redirect_uri'));
        $client->setScopes(['https://www.googleapis.com/auth/drive']);

        return $client;
    }

    private function authorizedOAuthClient(): Client
    {
        $client = $this->oauthClient();
        $token = $this->tokenStore->get();

        if (!$token) {
            throw new RuntimeException('Google Drive belum dihubungkan dengan OAuth.');
        }

        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken() ?: ($token['refresh_token'] ?? null);

            if (!$refreshToken) {
                throw new RuntimeException('Refresh token Google Drive tidak tersedia. Hubungkan ulang Google Drive.');
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken['error'])) {
                throw new RuntimeException('Refresh token Google Drive gagal: ' . ($newToken['error_description'] ?? $newToken['error']));
            }

            $this->tokenStore->put($newToken);
            $client->setAccessToken($this->tokenStore->get());
        }

        return $client;
    }

    private function legacyServiceAccountDrive(): Drive
    {
        // Legacy: only use this mode with Google Workspace Shared Drive.
        $client = new Client();
        $client->setApplicationName('SIPERDES Arsip Digital');
        $client->setAuthConfig($this->credentialPath());
        $client->setScopes(['https://www.googleapis.com/auth/drive']);

        return new Drive($client);
    }

    private function credentialPath(): string
    {
        $path = (string) config('google_drive.service_account_json');

        if ($path === '') {
            throw new RuntimeException('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON belum dikonfigurasi.');
        }

        if (!preg_match('/^[A-Za-z]:[\\\\\/]|^\//', $path)) {
            $path = base_path($path);
        }

        if (!is_file($path)) {
            throw new RuntimeException('File Service Account Google Drive tidak ditemukan: ' . $path);
        }

        return $path;
    }

    private function fileName(string $namaArsip, UploadedFile $file): string
    {
        $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '-', $namaArsip) ?: 'arsip-digital';
        $extension = $file->getClientOriginalExtension();

        return trim($safeName, '-') . '-' . time() . ($extension ? '.' . $extension : '');
    }
}