<?php
$data = json_decode(file_get_contents("php://input"), true);
file_put_contents("debug.log", print_r($data, true), FILE_APPEND);

if (!empty($data['nama'])) {
    $success = file_put_contents("last_panggilan.txt", "Nama Pasien:\n" . trim($data['nama']) . "\nHarap menuju ke loket farmasi.");
    if ($success === false) {
        file_put_contents("debug.log", "Failed to write to file\n", FILE_APPEND);
    }
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "message" => "Nama kosong"]);
}
?>