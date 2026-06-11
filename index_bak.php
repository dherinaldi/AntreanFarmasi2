<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Live Antrian Farmasi - RSUD GIRI EMAS</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #064e40;
            --primary-green: #0a5d4e;
            --accent-yellow: #facc15;
            --card-bg: #ffffff;
            --text-main: #1e293b;
        }

        * { box-sizing: border-box; }

        body {
            background: radial-gradient(circle, #0a5d4e 0%, #063d33 100%);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; 
        }

        #header-top {
            background: var(--bg-dark);
            color: white;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 800;
            border-bottom: 5px solid var(--accent-yellow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .main-layout {
            display: flex;
            height: calc(100vh - 65px);
            padding: 20px;
            gap: 20px;
        }

        .left-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .video-container {
            flex: 1;
            background: black;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            position: relative;
        }

        .video-container iframe {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }

        #nama-dipanggil {
            background: linear-gradient(135deg, #0a5d4e 0%, #064e40 100%);
            border-radius: 20px;
            text-align: center;
            padding: 30px;
            color: white;
            border-top: 6px solid var(--accent-yellow);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .antrian-container {
            flex: 2;
            display: flex;
            gap: 20px;
        }

        .column {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .col-header {
            text-align: center;
            padding: 15px;
            background: var(--primary-green);
            color: white;
        }

        .col-header .counter {
            font-size: 60px;
            font-weight: 800;
            display: block;
            line-height: 1;
            margin-bottom: 5px;
            color: var(--accent-yellow);
        }

        .col-header .label {
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .column-content {
            flex: 1;
            position: relative;
            overflow: hidden; 
            padding: 15px;
        }

        .scroll-wrapper {
            position: absolute;
            width: calc(100% - 30px);
            animation: moveUp 25s linear infinite;
        }

        .scroll-wrapper:hover { animation-play-state: paused; }

        @keyframes moveUp {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }

        .item {
            background: white;
            margin-bottom: 15px;
            padding: 18px;
            border-radius: 15px;
            border-left: 10px solid #cbd5e1;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .status-belum { border-left-color: #f59e0b; }
        .status-dilayani { border-left-color: var(--primary-green); }
        .status-selesai { border-left-color: #22c55e; background: #f0fdf4; }

        .item .patient-name {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 5px 0;
            text-transform: uppercase;
        }

        .item .room-origin { font-size: 14px; color: #64748b; font-weight: 600; }
        .item .time-info { font-size: 12px; color: #94a3b8; }

        .badge {
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 5px;
        }
        .badge-r { background: #fee2e2; color: #dc2626; }
        .badge-nr { background: #dcfce7; color: #16a34a; }

        .cito {
            background: #fff1f2 !important;
            border-left-color: #e11d48 !important;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; }
        }
    </style>
</head>

<body>

    <div id="header-top">
        <span id="digital-clock">ANTRIAN FARMASI RSUD GIRI EMAS</span>
        <span id="live-time">00:00:00</span>
    </div>

    <div class="main-layout">
        <div class="left-section">
            <div class="video-container">
                <iframe 
                    src="https://www.youtube.com/embed/TK6qVT2w0W0?autoplay=1&mute=1&loop=1&playlist=TK6qVT2w0W0" 
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                </iframe>
            </div>

            <div id="nama-dipanggil" class="animate__animated animate__fadeIn">
                <div style="font-size: 18px; color: var(--accent-yellow); font-weight: 600; margin-bottom: 10px;">PASIEN DIPANGGIL:</div>
                <div id="nama-panggilan" style="font-size: 42px; font-weight: 800;">-</div>
                <div id="deskPangil" style="font-size: 26px; margin-top: 15px; opacity: 0.9;">NOMOR RESEP: -</div>
            </div>
        </div>

        <div class="antrian-container">
            <div class="column" id="col-belum">
                <div class="col-header">
                    <span class="counter" id="jumlah-belum">000</span>
                    <span class="label">Belum Dilayani</span>
                </div>
                <div class="column-content" id="cont-belum"></div>
            </div>

            <div class="column" id="col-dilayani">
                <div class="col-header">
                    <span class="counter" id="jumlah-dilayani">000</span>
                    <span class="label">Sedang Dilayani</span>
                </div>
                <div class="column-content" id="cont-dilayani"></div>
            </div>

            <div class="column" id="col-selesai">
                <div class="col-header">
                    <span class="counter" id="jumlah-selesai">000</span>
                    <span class="label">Selesai / Siap</span>
                </div>
                <div class="column-content" id="cont-selesai"></div>
            </div>
        </div>
    </div>

<script>
// Memori lokal untuk asal ruangan
let memoryAsal = {};

function updateClock() {
    const now = new Date();
    const jam = now.toLocaleTimeString("id-ID", { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById("live-time").textContent = jam;
}
setInterval(updateClock, 1000);

function loadAntrian() {
    fetch("get_antrian.php")
    .then(res => res.json())
    .then(data => {
        const categories = {
            "belum_diterima": "#cont-belum",
            "dilayani": "#cont-dilayani",
            "selesai": "#cont-selesai"
        };

        Object.keys(categories).forEach(key => {
            const container = document.querySelector(categories[key]);
            let itemsHtml = "";

            if(data[key] && data[key].length > 0) {
                data[key].forEach(item => {
                    if (item.ASAL_RUANGAN && item.ASAL_RUANGAN !== '-') {
                        memoryAsal[item.NOPEN] = item.ASAL_RUANGAN;
                    }

                    let badge = item.RACIKAN == "1" 
                        ? '<span class="badge badge-r">RACIKAN</span>' 
                        : '<span class="badge badge-nr">NON RACIK</span>';
                    
                    let isCito = (key === "belum_diterima" && item.CITO == "1") ? "cito" : "";
                    let asal = item.ASAL_RUANGAN || "Poliklinik";

                    itemsHtml += `
                        <div class="item status-${key.split('_')[0]} ${isCito}">
                            ${badge}
                            <div class="patient-name">${item.NAMA}</div>
                            <div class="room-origin">${asal}</div>
                            <div class="time-info">${item.TANGGAL}</div>
                        </div>`;
                });

                if (data[key].length > 4) {
                    container.innerHTML = `<div class="scroll-wrapper" style="animation-duration: ${data[key].length * 5}s">
                        ${itemsHtml} ${itemsHtml}
                    </div>`;
                } else {
                    container.innerHTML = itemsHtml;
                }
            } else {
                container.innerHTML = `<div style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada antrian</div>`;
            }
        });

        document.getElementById("jumlah-belum").textContent = String(data.total.belum_diterima || 0).padStart(3,'0');
        document.getElementById("jumlah-dilayani").textContent = String(data.total.dilayani || 0).padStart(3,'0');
        document.getElementById("jumlah-selesai").textContent = String(data.total.selesai || 0).padStart(3,'0');
    });
}
setInterval(loadAntrian, 15000);
loadAntrian();

// =============================================
// FORMAT SUARA (DIPERBAIKI UNTUK UGD/IGD)
// =============================================
// GANTI FUNGSI formatSuara DI SCRIPT ABANG DENGAN INI
function formatSuara(teks) {
    if (!teks) return "";
    
    let hasil = teks.toUpperCase();
    
    // 1. TERJEMAHKAN KELAS ROMAWI (Agar dibaca Angka)
    // Gunakan regex agar tidak salah ganti kata lain
    hasil = hasil.replace(/\bKELAS III\b/g, "Kelas Tiga");
    hasil = hasil.replace(/\bKELAS II\b/g, "Kelas Dua");
    hasil = hasil.replace(/\bKELAS I\b/g, "Kelas Satu");
    hasil = hasil.replace(/\bVIP\b/g, "Vi Ai Pi"); // Biar lebih jelas

    // 2. TERJEMAHKAN SINGKATAN MEDIS
    hasil = hasil.replace(/\bUGD\b/g, "Unit Gawat Darurat");
    hasil = hasil.replace(/\bIGD\b/g, "Instalasi Gawat Darurat");
    hasil = hasil.replace(/\bPOLI\b/g, "Poliklinik");
    hasil = hasil.replace(/\bRANAP\b/g, "Rawat Inap");
    hasil = hasil.replace(/\bRAJAL\b/g, "Rawat Jalan");
    
    // 3. Hapus karakter non-huruf (titik, koma, dsb) agar tidak dieja
    hasil = hasil.replace(/[^a-zA-Z0-9\s]/g, " ");
    
    // 4. Ubah ke Proper Case (Huruf Depan Besar) 
    // AI suara lebih lancar baca "Gusti Ayu" daripada "GUSTI AYU"
    return hasil.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ');
}

let lastHash = "";
let selectedVoice = null;

function loadVoices() {
    let voices = speechSynthesis.getVoices();
    selectedVoice = voices.find(v => v.lang === "id-ID" && v.name.includes("Google"));
    if (!selectedVoice) selectedVoice = voices.find(v => v.lang === "id-ID");
}
if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadVoices;
}

function cekPanggilan() {
    fetch("last_panggilan.txt?rnd=" + Math.random())
    .then(res => res.text())
    .then(txt => {
        if (!txt.trim()) return;
        let data;
        try { data = JSON.parse(txt); } catch(e) { return; }

        const hash = data.nopen + data.nama + data.waktu;
        if (hash !== lastHash) {
            lastHash = hash;
            document.getElementById("nama-panggilan").innerHTML = data.nama;
            document.getElementById("deskPangil").innerHTML = "NOMOR RESEP: " + data.nopen;
            
            const el = document.getElementById("nama-dipanggil");
            el.classList.remove("animate__fadeIn");
            void el.offsetWidth; 
            el.classList.add("animate__fadeIn");

            let asalRuangan = memoryAsal[data.nopen] || "";
            panggilSuara(data.nama, asalRuangan);
        }
    });
}
setInterval(cekPanggilan, 3000);

function panggilSuara(namaAsli, asalRuangan) {
    let namaBersih = formatSuara(namaAsli);
    let asalBersih = formatSuara(asalRuangan);
    
    let kalimat = `Keluarga pasien , ${namaBersih} , `;
    if (asalBersih && asalBersih !== "-") {
        kalimat += `dari , ${asalBersih} , `;
    }
    kalimat += `silakan mengambil obat di loket apotek.`;
    
    const u = new SpeechSynthesisUtterance(kalimat);
    u.lang = "id-ID";
    u.rate = 0.85; 
    u.pitch = 1.0;
    
    if (selectedVoice) u.voice = selectedVoice;
    
    speechSynthesis.cancel();
    speechSynthesis.speak(u);
    u.onend = () => fetch("reset_panggilan.php", { method:"POST" });
}

loadVoices();
</script>
</body>
</html>