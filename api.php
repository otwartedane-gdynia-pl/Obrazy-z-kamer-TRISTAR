<?php

declare(strict_types=1);

$apiBase = 'https://api.zdiz.gdynia.pl/';

$allowedEndpoints = [
    'ri/rest/cameras',
    'ri/rest/camera_image_data',
];

$path = isset($_GET['path']) ? trim((string) $_GET['path'], '/') : '';

if (!in_array($path, $allowedEndpoints, true)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Endpoint not allowed'], JSON_UNESCAPED_UNICODE);
    exit;
}

$params = $_GET;
unset($params['path']);

$url = $apiBase . $path;

if ($params) {
    $url .= '?' . http_build_query($params);
}

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_USERAGENT => 'OtwarteDaneGdynia-TRISTAR-Demo/1.0',
]);

$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);

    http_response_code(502);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['error' => 'Nie udało się pobrać danych z API TRISTAR', 'details' => $error],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

curl_close($ch);

http_response_code($status ?: 200);
header('Content-Type: ' . ($contentType ?: 'application/json; charset=utf-8'));
header('Cache-Control: no-store');

echo $response;
