<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = "10.33.7.6"; $user = "admin"; $pass = "S!MGos2@kemkes.go.id"; $db = "regonline";
$koneksi = new mysqli($host, $user, $pass, $db);

echo "<h2>Debug Data Antrian Hari Ini (" . date('Y-m-d') . ")</h2>";

// Cek 5 Data terakhir di tabel Order Resep
echo "<h3>1. Sample Data Order Resep (layanan.order_resep)</h3>";
$res1 = $koneksi->query("SELECT NOMOR, KUNJUNGAN, TUJUAN, STATUS, TANGGAL FROM layanan.order_resep WHERE DATE(TANGGAL) = CURDATE() LIMIT 5");
if($res1->num_rows > 0) {
    echo "<table border='1'><tr><th>NOMOR</th><th>KUNJUNGAN</th><th>TUJUAN</th><th>STATUS</th><th>TANGGAL</th></tr>";
    while($row = $res1->fetch_assoc()) echo "<tr><td>".$row['NOMOR']."</td><td>".$row['KUNJUNGAN']."</td><td>".$row['TUJUAN']."</td><td>".$row['STATUS']."</td><td>".$row['TANGGAL']."</td></tr>";
    echo "</table>";
} else { echo "Data Order Resep Kosong Hari Ini."; }

// Cek 5 Data terakhir di tabel Kunjungan Apotek
echo "<h3>2. Sample Data Kunjungan Apotek (pendaftaran.kunjungan)</h3>";
$res2 = $koneksi->query("SELECT NOMOR, NOPEN, RUANGAN, STATUS, MASUK FROM pendaftaran.kunjungan WHERE RUANGAN = '101090101' AND DATE(MASUK) = CURDATE() LIMIT 5");
if($res2->num_rows > 0) {
    echo "<table border='1'><tr><th>NOMOR</th><th>NOPEN</th><th>RUANGAN</th><th>STATUS</th><th>MASUK</th></tr>";
    while($row = $res2->fetch_assoc()) echo "<tr><td>".$row['NOMOR']."</td><td>".$row['NOPEN']."</td><td>".$row['RUANGAN']."</td><td>".$row['STATUS']."</td><td>".$row['MASUK']."</td></tr>";
    echo "</table>";
} else { echo "Data Kunjungan Apotek Kosong Hari Ini."; }
?>