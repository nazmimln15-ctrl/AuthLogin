<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

$base = 'http://127.0.0.1:8000';
$client = new Client(['base_uri' => $base, 'cookies' => true, 'http_errors' => false]);

function getCsrf($body) {
    if (preg_match('/name="_token" value="([^"]+)"/', $body, $m)) return $m[1];
    return null;
}

// Test login and fetch admin users
echo "Testing API token issuance and admin API...\n";

// Obtain personal access token via convenience endpoint
$res = $client->post('/api/token', [
    'form_params' => [
        'email' => 'admin@gmail.com',
        'password' => 'admin',
    ],
]);
echo "/api/token status: " . $res->getStatusCode() . "\n";
$body = json_decode((string) $res->getBody(), true);
$token = $body['token'] ?? null;
if (! $token) {
    echo "Could not obtain API token. Ensure Sanctum is installed and migrations run.\n";
} else {
    $res = $client->get('/api/admin/users', ['headers' => ['Authorization' => 'Bearer ' . $token]]);
    echo "API /api/admin/users status: " . $res->getStatusCode() . "\n";
    echo $res->getBody() . "\n";
}

// Test mahasiswa login
echo "\nTesting mahasiswa login and access to /mahasiswa...\n";
$res = $client->get('/login');
$body = (string) $res->getBody();
$token = getCsrf($body);
$res = $client->post('/login', [
    'form_params' => [
        '_token' => $token,
        'email' => 'mahasiswa@gmail.com',
        'password' => 'mahasiswa',
    ],
    'allow_redirects' => true,
]);
echo "Login mahasiswa response: " . $res->getStatusCode() . "\n";
$res = $client->get('/mahasiswa');
echo "/mahasiswa status: " . $res->getStatusCode() . "\n";
echo "Done.\n";
