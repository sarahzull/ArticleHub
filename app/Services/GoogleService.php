<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;

class GoogleService
{
    protected $client;
    protected $driveService;
    protected $sheetsService;

    // public function __construct()
    // {
    //     $this->client = new Google_Client();
    //     $this->client->setAuthConfig(base_path('credentials.json'));
    //     $this->client->setScopes([
    //         Google_Service_Drive::DRIVE,
    //         Google_Service_Sheets::SPREADSHEETS
    //     ]);
    //     $this->client->setAccessType('offline');
    //     $this->client->setPrompt('select_account consent');

    //     if ($this->client->isAccessTokenExpired()) {
    //         if ($this->client->getRefreshToken()) {
    //             $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
    //         } else {
    //             $authUrl = $this->client->createAuthUrl();
    //             printf("Open the following link in your browser:\n%s\n", $authUrl);
    //             print 'Enter verification code: ';
    //             $authCode = trim(fgets(STDIN));

    //             $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
    //             $this->client->setAccessToken($accessToken);

    //             if (array_key_exists('error', $accessToken)) {
    //                 throw new \Exception(join(', ', $accessToken));
    //             }
    //         }
    //     }

    //     $this->driveService = new Google_Service_Drive($this->client);
    //     $this->sheetsService = new Google_Service_Sheets($this->client);
    // }

    // public function listFilesInFolder($folderId)
    // {
    //     $query = "'{$folderId}' in parents";
    //     $response = $this->driveService->files->listFiles(['q' => $query]);
    //     return $response->getFiles();
    // }

    // public function readFileContent($fileId)
    // {
    //     $response = $this->driveService->files->get($fileId, ['alt' => 'media']);
    //     return $response->getBody()->getContents();
    // }

    // public function appendToSheet($spreadsheetId, $range, $values)
    // {
    //     $body = new Google_Service_Sheets_ValueRange([
    //         'values' => $values
    //     ]);
    //     $params = [
    //         'valueInputOption' => 'RAW'
    //     ];
    //     $this->sheetsService->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    // }

    // public function getFolderIdByName($parentFolderId, $folderName)
    // {
    //     $query = "mimeType='application/vnd.google-apps.folder' and '{$parentFolderId}' in parents and name='{$folderName}'";
    //     $response = $this->driveService->files->listFiles(['q' => $query]);

    //     if (count($response->getFiles()) == 0) {
    //         return null;
    //     }

    //     return $response->getFiles()[0]->getId();
    // }
}
