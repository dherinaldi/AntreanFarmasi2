<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Panggilan Farmasi Modern</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary: #2563eb;
        --secondary: #64748b;
        --success: #22c55e;
        --danger: #ef4444;
        --warning: #f59e0b;
        --background: #f1f5f9;
        --card-bg: #ffffff;
        --text-main: #1e293b;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--background);
        color: var(--text-main);
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 1300px;
        margin: auto;
    }

    /* Header */
    .header-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background: white;
        padding: 20px 30px;
        border-radius: 15px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    h2 {
        margin: 0;
        color: var(--primary);
        font-weight: 800;
        font-size: 26px;
        letter-spacing: -0.5px;
    }

    .btn-refresh {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-refresh:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    /* Section Title */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 35px;
        margin-bottom: 15px;
        padding: 0 10px;
    }

    .section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Table Card */
    .card-table {
        background: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fafc;
        color: var(--secondary);
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        padding: 18px 20px;
        text-align: left;
        border-bottom: 2px solid #f1f5f9;
    }

    td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    /* Badge Ruangan */
    .badge-ruangan {
        background: #e0e7ff;
        color: #4338ca;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    /* Call Button */
    .btn-panggil {
        background: var(--success);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .btn-cetak {
        background: #0adaed;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .btn-panggil:hover {
        background: #16a34a;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        transform: scale(1.02);
    }

    /* Pulse Indicator */
    .pulse-dot {
        height: 10px;
        width: 10px;
        background: var(--success);
        border-radius: 50%;
        display: inline-block;
        animation: pulse-animation 2s infinite;
    }

    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0px rgba(34, 197, 94, 0.5);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
        }

        100% {
            box-shadow: 0 0 0 0px rgba(34, 197, 94, 0);
        }
    }

    .empty-state {
        padding: 40px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .action-buttons button {
        min-width: 90px;
        height: 36px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-panel">
            <h2><i class="fas fa-hospital-user"></i> Panel Antrian Farmasi</h2>
            <button class="btn-refresh" onclick="loadData()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>

        <div class="section-header">
            <h3 style="color: var(--warning)"><i class="fas fa-inbox"></i> Belum Diterima</h3>
        </div>
        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>NOPEN</th>
                        <th>ANTRIAN</th>
                        <th>OBAT</th>
                        <th>Nama Pasien</th>
                        <th>Asal Ruangan</th>
                        <th width="20%">Waktu Order</th>
                        <th width="10%" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="belum"></tbody>
            </table>
        </div>

        <div class="section-header">
            <h3 style="color: var(--primary)"><span class="pulse-dot"></span> Sedang Dilayani</h3>
        </div>
        <div class="card-table">
            <table>
                <tbody id="dilayani"></tbody>
            </table>
        </div>

        <div class="section-header">
            <h3 style="color: var(--success)">
                <i class="fas fa-check-double"></i> Selesai (Siap Ambil)
            </h3>
        </div>

        <div style="margin-bottom:15px;">
            <input type="text" id="searchSelesai" placeholder="Cari NOPEN / Nama Pasien..." style="
                width:100%;
                padding:12px 15px;
                border:1px solid #cbd5e1;
                border-radius:10px;
                font-size:14px;
                outline:none;
           ">
        </div>

        <div class="card-table">
            <table>
                <tbody id="selesai"></tbody>
            </table>
        </div>
    </div>

    <script>
    let asalMap = {}; // Penyimpan memori asal ruangan secara global
    let selesaiData = [];

    function loadData() {
        $.getJSON("get_antrian.php", function(data) {

            // 1. Update Map (Kamus Asal) agar data asal ruangan tidak hilang saat pindah status
            const gabungan = [...data.belum_diterima, ...data.dilayani, ...data.selesai];
            gabungan.forEach(item => {
                if (item.ASAL_RUANGAN && item.ASAL_RUANGAN !== '-') {
                    asalMap[item.NOPEN] = item.ASAL_RUANGAN;
                }
            });

            // 2. Render masing-masing tabel
            renderTable("#belum", data.belum_diterima, true); // True berarti tampilkan header jika kosong
            renderTable("#dilayani", data.dilayani, false);

            selesaiData = data.selesai;
            renderTable("#selesai", selesaiData, false);
        });
    }

    function renderTable(targetId, list, isMain) {
        let html = "";

        if (list.length === 0) {
            html = `<tr><td colspan="6" class="empty-state">Belum ada pasien di tahap ini</td></tr>`;
        } else {
            list.forEach((x, i) => {
                // Ambil asal ruangan dari x atau dari kamus asalMap
                let asalFix = x.ASAL_RUANGAN || asalMap[x.NOPEN] || "-";

                // Tombol berdasarkan target table
                let actionButton = '';

                if (targetId === '#belum') {
                    actionButton = `
                    <button class="btn-cetak" data-nopen="${x.NOPEN}" data-no_antrian="${x.NO_ANTRIAN}" data-racikan="${x.RACIKAN}"  data-rm="${x.NORM}" data-nama="${x.NAMA}"  onclick="cetak(this)">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                `;
                } else {
                    actionButton = `
                    <button class="btn-cetak" data-nopen="${x.NOPEN}" data-no_antrian="${x.NO_ANTRIAN}" data-racikan="${x.RACIKAN}"  data-rm="${x.NORM}" data-nama="${x.NAMA}"  onclick="cetak(this)">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <button class="btn-panggil" onclick="panggil('${x.NOPEN}', '${x.NAMA}')">
                        <i class="fas fa-volume-up"></i> Panggil
                    </button>
                `;
                }

                html += `
                <tr>
                    <td><span style="font-weight:700; color:#cbd5e1">${i+1}</span></td>
                    <td><code style="font-weight:700; color:var(--primary); background:#eff6ff; padding:4px 8px; border-radius:5px">${x.NOPEN}</code></td>
                    <td width="10%"><code style="font-weight:700; color:var(--primary); background:#eff6ff; padding:4px 8px; border-radius:5px">${x.NO_ANTRIAN} </code></td>
                    <td><code style="
        font-weight:700;
        color:${x.RACIKAN == 0 ? '#92400e' : '#166534'};
        background:${x.RACIKAN == 0 ? '#fef3c7' : '#dcfce7'};
        padding:4px 8px;
        border-radius:5px;
    ">
        ${x.RACIKAN == 0 ? 'NR' : 'R'}
    </code></td>
                    <td><div style="font-weight:700; font-size:15px">(${x.NORM}) ${x.NAMA}</div></td>
                    <td><span class="badge-ruangan"><i class="fas fa-door-open"></i> ${asalFix}</span></td>
                    <td style="color:var(--secondary); font-size:13px"><i class="far fa-clock"></i> ${x.TANGGAL}</td>
                    <td style="text-align: center">
                        <div class="action-buttons">
                        ${actionButton}
                        </div>
                    </td>
                </tr>`;
            });
        }
        $(targetId).html(html);
    }

    $("#searchSelesai").on("keyup", function() {

        const keyword = $(this).val().toLowerCase();

        const filtered = selesaiData.filter(item => {

            const nopen = (item.NOPEN || "").toLowerCase();
            const nama = (item.NAMA || "").toLowerCase();
            const norm = (item.NORM || "").toLowerCase();

            return nopen.includes(keyword) ||
                nama.includes(keyword) ||
                norm.includes(keyword);
        });

        renderTable("#selesai", filtered, false);
    });

    // Inisialisasi
    loadData();
    //setInterval(loadData, 5000); // Auto refresh setiap 5 detik

    function panggil(nopen, nama) {
        $.ajax({
            url: "panggil_pasien.php",
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({
                nopen: nopen,
                nama: nama
            }),
            success: function(res) {
                if (res.status === "ok") {
                    console.log("Memanggil: " + nopen);
                } else {
                    alert("Gagal memanggil: " + (res.msg ?? 'Error tidak diketahui'));
                }
            },
            error: function() {
                alert("Terjadi kesalahan koneksi saat memanggil pasien.");
            }
        });
    }

    function cetak(btn) {
        const nopen = btn.dataset.nopen;
        const rm = btn.dataset.rm;
        const nama = btn.dataset.nama;
        const racikan = btn.dataset.racikan;
        const no_antrian = btn.dataset.no_antrian;

        console.log('NOPEN:', nopen);
        console.log('RM:', rm);

        window.open(
            `cetak.php?cetak&nopen=${encodeURIComponent(nopen)}&rm=${encodeURIComponent(rm)}&racikan=${encodeURIComponent(racikan)}&no_antrian=${encodeURIComponent(no_antrian)}&nama=${encodeURIComponent(nama)}`,
            '_blank'
        );
    }


    $('#tabel-antrian tbody').on('click', 'button.cetak', function() {
        var data = table.row($(this).parents('tr')).data();

        $.ajax({
            type: 'POST', // mengirim data dengan method POST
            url: '../nomor-antrian/cetak.php', // url file proses insert data
            data: {
                antrian: data["no_antrian"],
                jenis: data["jenis"],
                tanggal: data['tanggal'],
                cetak: 'cetak'
            },
            success: function(result) { // ketika proses insert data selesai
                // jika berhasil
                var printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write(result);
                printWindow.document.close();
                printWindow.print();
            },
        });

    });

    function cetak1(nopen) {
        const url = `../report/cetak_antrian.php?nopen=${encodeURIComponent(nopen)}`;
        window.open(url, '_blank');
    }
    </script>
</body>

</html>
