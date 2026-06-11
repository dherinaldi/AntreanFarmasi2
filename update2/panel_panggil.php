<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Panggilan Farmasi Modern</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --success: #22c55e;
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

        .container { max-width: 1300px; margin: auto; }

        .header-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            gap: 20px;
        }

        h2 { margin: 0; color: var(--primary); font-weight: 800; font-size: 24px; white-space: nowrap; }

        .filter-group { display: flex; align-items: center; gap: 15px; flex-grow: 1; justify-content: flex-end; }

        .select-filter {
            padding: 10px 15px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-weight: 600;
            color: var(--text-main);
            outline: none;
            min-width: 250px;
            cursor: pointer;
        }

        .btn-refresh {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        .section-header { margin-top: 30px; margin-bottom: 12px; }
        .section-header h3 { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px; margin: 0; }
        
        .card-table { background: var(--card-bg); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: var(--secondary); text-transform: uppercase; font-size: 11px; font-weight: 700; padding: 15px 20px; text-align: left; border-bottom: 2px solid #f1f5f9; }
        td { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        
        .row-number { font-weight: 800; color: #cbd5e1; font-size: 14px; }
        .badge-antrean { background: #fef08a; color: #854d0e; padding: 4px 12px; border-radius: 8px; font-size: 15px; font-weight: 900; border: 1px solid #facc15; display: inline-block; min-width: 70px; text-align: center; }
        .badge-ruangan { background: #e0e7ff; color: #4338ca; padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; }
        
        .btn-panggil { background: var(--success); color: white; border: none; padding: 8px 15px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-panggil:hover { background: #16a34a; transform: scale(1.05); }

        .pulse-dot { height: 10px; width: 10px; background: var(--success); border-radius: 50%; display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0px rgba(34, 197, 94, 0.5); } 70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); } 100% { box-shadow: 0 0 0 0px rgba(34, 197, 94, 0); } }
    </style>
</head>

<body>
<div class="container">
    <div class="header-panel">
        <h2><i class="fas fa-hospital-user"></i> Panel Farmasi</h2>
        
        <div class="filter-group">
            <label style="font-weight: 700; color: var(--secondary);">Filter Poli:</label>
            <select id="filterPoli" class="select-filter" onchange="renderSemua()">
                <option value="SEMUA">-- Tampilkan Semua Poli --</option>
            </select>
            <button class="btn-refresh" onclick="loadData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <div class="section-header">
        <h3 style="color: var(--warning)"><i class="fas fa-inbox"></i> Belum Diterima</h3>
    </div>
    <div class="card-table">
        <table>
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="10%">ANTREAN</th>
                    <th width="15%">NOPEN</th>
                    <th>Nama Pasien</th>
                    <th>Asal Ruangan</th>
                    <th width="18%">Waktu</th>
                    <th width="10%">Aksi</th>
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
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="10%">ANTREAN</th>
                    <th width="15%">NOPEN</th>
                    <th>Nama Pasien</th>
                    <th>Asal Ruangan</th>
                    <th width="18%">Waktu</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody id="dilayani"></tbody>
        </table>
    </div>

    <div class="section-header">
        <h3 style="color: var(--success)"><i class="fas fa-check-double"></i> Selesai / Siap</h3>
    </div>
    <div class="card-table">
        <table>
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="10%">ANTREAN</th>
                    <th width="15%">NOPEN</th>
                    <th>Nama Pasien</th>
                    <th>Asal Ruangan</th>
                    <th width="18%">Waktu</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody id="selesai"></tbody>
        </table>
    </div>
</div>

<script>
let globalData = { belum_diterima: [], dilayani: [], selesai: [] };
let poliList = new Set();

function loadData() {
    $.getJSON("get_antrian.php", function(data) {
        globalData = data;
        
        // Update Daftar Poli untuk Dropdown (Hanya poli yang ada pasiennya)
        const semuaPasien = [...data.belum_diterima, ...data.dilayani, ...data.selesai];
        semuaPasien.forEach(p => {
            if (p.ASAL_RUANGAN && p.ASAL_RUANGAN !== '-' && p.ASAL_RUANGAN !== 'Unit Terkait') {
                if (!poliList.has(p.ASAL_RUANGAN)) {
                    poliList.add(p.ASAL_RUANGAN);
                    $("#filterPoli").append(`<option value="${p.ASAL_RUANGAN}">${p.ASAL_RUANGAN}</option>`);
                }
            }
        });

        renderSemua();
    });
}

function renderSemua() {
    const filter = $("#filterPoli").val();
    renderTable("#belum", filterData(globalData.belum_diterima, filter));
    renderTable("#dilayani", filterData(globalData.dilayani, filter));
    renderTable("#selesai", filterData(globalData.selesai, filter));
}

function filterData(list, filter) {
    if (filter === "SEMUA") return list;
    return list.filter(item => item.ASAL_RUANGAN === filter);
}

function renderTable(targetId, list) {
    let html = "";
    if (!list || list.length === 0) {
        html = `<tr><td colspan="7" style="text-align:center; padding:30px; color:#94a3b8; font-style:italic">Tidak ada antrean</td></tr>`;
    } else {
        list.forEach((x, index) => {
            let noUrut = index + 1; // NOMOR URUT 1, 2, 3...
            html += `
                <tr>
                    <td class="row-number">${noUrut}</td>
                    <td><span class="badge-antrean">${x.NO_ANTRIAN || '-'}</span></td>
                    <td><code style="font-weight:700; color:var(--primary)">${x.NOPEN}</code></td>
                    <td><div style="font-weight:700; text-transform: uppercase;">${x.NAMA}</div></td>
                    <td><span class="badge-ruangan">${x.ASAL_RUANGAN || '-'}</span></td>
                    <td style="color:var(--secondary); font-size:13px">${x.TANGGAL}</td>
                    <td>
                        <button class="btn-panggil" onclick="panggil('${x.NOPEN}', '${x.NAMA}')">
                            <i class="fas fa-volume-up"></i> Panggil
                        </button>
                    </td>
                </tr>`;
        });
    }
    $(targetId).html(html);
}

function panggil(nopen, nama) {
    $.ajax({
        url: "panggil_pasien.php",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({ nopen: nopen, nama: nama }),
        success: function() { 
            console.log("Panggil: " + nama); 
            // Opsional: Kasih alert kecil atau toast kalau berhasil dipanggil
        }
    });
}

loadData();
setInterval(loadData, 10000); // Auto refresh setiap 10 detik
</script>
</body>
</html>