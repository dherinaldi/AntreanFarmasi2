<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Jakarta');

// PATH ABSOLUT (paling aman)
$FILE = __DIR__ . '/last_panggilan.txt';

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

$nopen = isset($input['nopen']) ? trim($input['nopen']) : '';
$nama  = isset($input['nama'])  ? trim($input['nama'])  : '';

if ($nopen === '' || $nama === '') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'nopen/nama kosong',
        'debug_raw' => $raw
    ]);
    exit;
}

$payload = [
    'nopen' => $nopen,
    'nama'  => $nama,
    'waktu' => date('Y-m-d H:i:s')
];

$json = json_encode($payload, JSON_UNESCAPED_UNICODE);

// TULIS FILE (cek hasil)
$ok = @file_put_contents($FILE, $json);

if ($ok === false) {
    $err = error_get_last();
    echo json_encode([
        'status' => 'error',
        'msg' => 'Gagal menulis last_panggilan.txt',
        'file' => $FILE,
        'error' => $err
    ]);
    exit;
}

echo json_encode([
    'status' => 'ok',
    'file' => $FILE,
    'data' => $payload
]);
