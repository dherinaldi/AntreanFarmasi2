<?php
// Matikan error agar JSON bersih
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');

$host = "192.168.100.110";
$user = "admin";
$pass = "S!MRSGos2";
$db   = "regonline";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die(json_encode(["error" => "Koneksi database gagal"]));
}

$IDRUANGAN = '103010201'; // ID Farmasi

/**
 * FUNGSI UTAMA: Sinkronisasi Berdasarkan Nomor RM (NORM)
 * ASAL RUANGAN: Difilter agar tidak mengambil Lab, Radiologi, atau Penunjang lainnya.
 */
function ambilDataFarmasi($status, $koneksi, $IDRUANGAN, $isOrder = false)
{
    $tanggal = isset($_REQUEST['tanggal']) && strtotime($_REQUEST['tanggal'])
        ? date('Y-m-d', strtotime($_REQUEST['tanggal']))
        : date('Y-m-d');
    #echo $tanggal;

    if ($isOrder) {
        // QUERY BELUM DITERIMA (ORDER RESEP)
        $sql = "
        SELECT
            p.NORM,
            k.NOPEN,
            p2.NAMA,
            DATE_FORMAT(or2.TANGGAL, '%d-%m-%Y %H:%i:%s') AS TANGGAL,
            -- LOGIKA ASAL RUANGAN: Hanya Poli/UGD, Abaikan Lab/Radiologi
            (SELECT r2.DESKRIPSI FROM pendaftaran.kunjungan k2
             JOIN master.ruangan r2 ON r2.ID = k2.RUANGAN
             WHERE k2.NOPEN = k.NOPEN
             AND k2.RUANGAN != '$IDRUANGAN'
             AND r2.DESKRIPSI NOT LIKE '%Laboratorium%'
             AND r2.DESKRIPSI NOT LIKE '%Radiologi%'
             AND (r2.DESKRIPSI LIKE '%Poli%' OR r2.DESKRIPSI LIKE '%REHAB%' OR r2.DESKRIPSI LIKE '%IGD%' OR r2.DESKRIPSI LIKE '%HEMO%')
             ORDER BY k2.MASUK DESC LIMIT 1) AS ASAL_RUANGAN,
            MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END) AS RACIKAN,
            -- CARI NOMOR ANTREAN BERDASARKAN NORM
            (SELECT CONCAT(res.POLI_BPJS, '-', LPAD(res.ANTRIAN_POLI, 3, '0'))
             FROM regonline.reservasi res
             WHERE res.NORM = p.NORM
             AND DATE(res.TANGGALKUNJUNGAN) = CURDATE()
             ORDER BY res.ID ASC LIMIT 1) AS NO_ANTRIAN
        FROM layanan.order_resep or2
        JOIN pendaftaran.kunjungan k ON k.NOMOR = or2.KUNJUNGAN
        LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
        LEFT JOIN master.pasien p2 ON p.NORM = p2.NORM
        LEFT JOIN master.ruangan r ON r.ID = k.RUANGAN
        LEFT JOIN layanan.order_detil_resep odr ON odr.ORDER_ID = or2.NOMOR
        WHERE or2.TUJUAN = '$IDRUANGAN'
          AND or2.STATUS = $status
          AND or2.TANGGAL BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59'
        GROUP BY p.NORM
        ORDER BY or2.TANGGAL DESC";
    } else {
        // QUERY DILAYANI & SELESAI
        $sql = "
        SELECT
            p.NORM,
            k.NOPEN,
            p2.NAMA,
            DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL,
            -- LOGIKA ASAL RUANGAN: Hanya Poli/UGD, Abaikan Lab/Radiologi
            (SELECT r2.DESKRIPSI FROM pendaftaran.kunjungan k2
             JOIN master.ruangan r2 ON r2.ID = k2.RUANGAN
             WHERE k2.NOPEN = k.NOPEN
             AND k2.RUANGAN != '$IDRUANGAN'
             AND r2.DESKRIPSI NOT LIKE '%Laboratorium%'
             AND r2.DESKRIPSI NOT LIKE '%Radiologi%'
             AND (r2.DESKRIPSI LIKE '%Poli%' OR r2.DESKRIPSI LIKE '%REHAB%' OR r2.DESKRIPSI LIKE '%IGD%' OR r2.DESKRIPSI LIKE '%HEMO%')
             ORDER BY k2.MASUK DESC LIMIT 1) AS ASAL_RUANGAN,
            -- Ambil Status Racikan
            (
    SELECT MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END)
    FROM layanan.order_resep or2
    JOIN layanan.order_detil_resep odr
        ON odr.ORDER_ID = or2.NOMOR
    JOIN pendaftaran.kunjungan k2
        ON k2.NOMOR = or2.KUNJUNGAN
    WHERE k2.NOPEN = k.NOPEN
) AS RACIKAN,
            -- CARI NOMOR ANTREAN BERDASARKAN NORM
            (SELECT CONCAT(res.POLI_BPJS, '-', LPAD(res.ANTRIAN_POLI, 3, '0'))
             FROM regonline.reservasi res
             WHERE res.NORM = p.NORM
             AND DATE(res.TANGGALKUNJUNGAN) = CURDATE()
             ORDER BY res.ID ASC LIMIT 1) AS NO_ANTRIAN
        FROM pendaftaran.kunjungan k
        LEFT JOIN pendaftaran.pendaftaran p ON p.NOMOR = k.NOPEN
        LEFT JOIN master.pasien p2 ON p.NORM = p2.NORM
        WHERE k.RUANGAN = '$IDRUANGAN'
          AND k.STATUS = $status        
         AND k.MASUK between '$tanggal 00:00:00' AND '$tanggal 23:59:59'
        GROUP BY p.NORM
        ORDER BY k.MASUK DESC";
    }

    #echo $sql;

    #die();

    $result = $koneksi->query($sql);
    $data   = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Pastikan NO_ANTRIAN dan ASAL_RUANGAN tidak kosong
            if (! $row['NO_ANTRIAN']) {
                $row['NO_ANTRIAN'] = "UMUM";
            }

            if (! $row['ASAL_RUANGAN']) {
                $row['ASAL_RUANGAN'] = "Unit Terkait";
            }

            $data[] = $row;
        }
    }
    return $data;
}

$response = [
    'belum_diterima' => ambilDataFarmasi(1, $koneksi, $IDRUANGAN, true),
    'dilayani'       => ambilDataFarmasi(1, $koneksi, $IDRUANGAN, false),
    'selesai'        => ambilDataFarmasi(2, $koneksi, $IDRUANGAN, false),
];

$response['total'] = [
    'belum_diterima' => count($response['belum_diterima']),
    'dilayani'       => count($response['dilayani']),
    'selesai'        => count($response['selesai']),
];

header('Content-Type: application/json');
echo json_encode($response);
