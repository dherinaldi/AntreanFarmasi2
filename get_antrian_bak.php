<?php
date_default_timezone_set('Asia/Jakarta');

$host = "192.168.100.110"; //ip websserver
$user = "admin";// user db
$pass = "S!MRSGos2";//pass db
$db   = "regonline";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$tanggal = date('Y-m-d');
$IDRUANGAN = '103010201'; // ID Farmasi / Apotek

// =========================================================================
// 1. QUERY BELUM DITERIMA (ORDER RESEP)
// =========================================================================
$query_order = "
SELECT 
    k.NOPEN,
    DATE_FORMAT(or2.TANGGAL, '%d-%m-%Y %H:%i:%s') AS TANGGAL,
    LPAD(p.NORM, 6, '0') NORM,
    p2.NAMA AS NAMA,
    or2.CITO,
    MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END) AS RACIKAN,
    r.DESKRIPSI AS ASAL_RUANGAN
FROM layanan.order_resep or2
LEFT JOIN pendaftaran.kunjungan k ON k.NOMOR = or2.KUNJUNGAN
LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
LEFT JOIN master.pasien p2 ON p.NORM = p2.NORM
LEFT JOIN master.ruangan r ON r.ID = k.RUANGAN
LEFT JOIN layanan.order_detil_resep odr ON odr.ORDER_ID = or2.NOMOR
WHERE or2.TUJUAN = '$IDRUANGAN'
  AND or2.STATUS = 1
  AND DATE(or2.TANGGAL) = '$tanggal'
GROUP BY k.NOPEN
ORDER BY k.MASUK DESC
";

$result_order = $koneksi->query($query_order);

// =========================================================================
// 2. QUERY SEDANG DILAYANI (STATUS = 1)
// =========================================================================
$query_kunjungan = "
SELECT 
    k.NOPEN,
    DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL,
    LPAD(p.NORM, 6, '0') AS NORM,
    p2.NAMA AS NAMA,
    -- Subquery untuk mencari Asal Ruangan (Bukan Lab/Rad/Apotek) --
    (SELECT r2.DESKRIPSI 
     FROM pendaftaran.kunjungan k2 
     JOIN master.ruangan r2 ON r2.ID = k2.RUANGAN 
     WHERE k2.NOPEN = k.NOPEN 
       AND k2.RUANGAN != '$IDRUANGAN' 
       AND r2.DESKRIPSI NOT LIKE '%Laboratorium%' 
       AND r2.DESKRIPSI NOT LIKE '%Radiologi%'
     ORDER BY k2.MASUK DESC LIMIT 1) AS ASAL_RUANGAN,
    -- Subquery untuk cek status Racikan --
    (SELECT MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END) 
     FROM layanan.order_resep or2 
     JOIN layanan.order_detil_resep odr ON odr.ORDER_ID = or2.NOMOR 
     WHERE or2.KUNJUNGAN = k.NOMOR) AS RACIKAN,
    -- Subquery untuk cek status CITO --
    (SELECT MAX(or3.CITO) FROM layanan.order_resep or3 WHERE or3.KUNJUNGAN = k.NOMOR) AS CITO
FROM pendaftaran.kunjungan k
LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
LEFT JOIN master.pasien p2 ON p.NORM = p2.NORM
WHERE k.RUANGAN = '$IDRUANGAN'
  AND k.STATUS = 1
  AND DATE(k.MASUK) = '$tanggal'
GROUP BY k.NOPEN
ORDER BY k.MASUK DESC
";

$result_kunjungan = $koneksi->query($query_kunjungan);

// =========================================================================
// 3. QUERY SELESAI (STATUS = 2)
// =========================================================================
$query_kunjungan_sel = "
SELECT 
    k.NOPEN,
    DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL,
    LPAD(p.NORM, 6, '0') AS NORM,
    p2.NAMA AS NAMA,
    -- Subquery untuk mencari Asal Ruangan (Bukan Lab/Rad/Apotek) --
    (SELECT r2.DESKRIPSI 
     FROM pendaftaran.kunjungan k2 
     JOIN master.ruangan r2 ON r2.ID = k2.RUANGAN 
     WHERE k2.NOPEN = k.NOPEN 
       AND k2.RUANGAN != '$IDRUANGAN' 
       AND r2.DESKRIPSI NOT LIKE '%Laboratorium%' 
       AND r2.DESKRIPSI NOT LIKE '%Radiologi%'
     ORDER BY k2.MASUK DESC LIMIT 1) AS ASAL_RUANGAN,
    -- Subquery untuk cek status Racikan --
    (SELECT MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END) 
     FROM layanan.order_resep or2 
     JOIN layanan.order_detil_resep odr ON odr.ORDER_ID = or2.NOMOR 
     WHERE or2.KUNJUNGAN = k.NOMOR) AS RACIKAN,
    -- Subquery untuk cek status CITO --
    (SELECT MAX(or3.CITO) FROM layanan.order_resep or3 WHERE or3.KUNJUNGAN = k.NOMOR) AS CITO
FROM pendaftaran.kunjungan k
LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
LEFT JOIN master.pasien p2 ON p.NORM = p2.NORM
WHERE k.RUANGAN = '$IDRUANGAN'
  AND k.STATUS = 2
  AND DATE(k.MASUK) = '$tanggal'
GROUP BY k.NOPEN
ORDER BY k.MASUK DESC
";

$result_kunjungan_sel = $koneksi->query($query_kunjungan_sel);

// ============================
// SUSUN DATA UNTUK JSON
// ============================
$data = [
    'belum_diterima' => [],
    'dilayani'       => [],
    'selesai'        => []
];

while ($row = $result_order->fetch_assoc())       { $data['belum_diterima'][] = $row; }
while ($row = $result_kunjungan->fetch_assoc())   { $data['dilayani'][]       = $row; }
while ($row = $result_kunjungan_sel->fetch_assoc()){ $data['selesai'][]        = $row; }

// HITUNG TOTAL
$data['total'] = [
    'belum_diterima' => count($data['belum_diterima']),
    'dilayani'       => count($data['dilayani']),
    'selesai'        => count($data['selesai'])
];

header('Content-Type: application/json');
echo json_encode($data);
?>