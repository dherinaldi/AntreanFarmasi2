<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Live Antrian Farmasi - RSUD LAWANG</title>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/animate.min.css" />
    <link href="assets/css/css2.css" rel="stylesheet">

    <style>
    :root {
        --bg-dark: #064e40;
        --primary-green: #0a5d4e;
        --accent-yellow: #facc15;
        --card-bg: #ffffff;
        --text-main: #1e293b;
    }

    * {
        box-sizing: border-box;
    }

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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
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
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #nama-dipanggil {
        background: linear-gradient(135deg, #0a5d4e 0%, #064e40 100%);
        border-radius: 20px;
        text-align: center;
        padding: 20px;
        color: white;
        border-top: 6px solid var(--accent-yellow);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    #antrean-panggilan {
        font-size: 90px;
        font-weight: 900;
        color: var(--accent-yellow);
        line-height: 1;
        margin: 10px 0;
    }

    #nama-panggilan {
        font-size: 38px;
        font-weight: 800;
        text-transform: uppercase;
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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
        color: var(--accent-yellow);
        line-height: 1;
    }

    .column-content {
        flex: 1;
        position: relative;
        overflow: hidden;
        padding: 15px;
    }

    /* LOGIKA SCROLLING SEAMLESS */
    .scroll-wrapper {
        position: absolute;
        width: calc(100% - 30px);
        display: flex;
        flex-direction: column;
        animation: moveUp linear infinite;
    }

    @keyframes moveUp {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-50%);
        }

        /* Naik tepat setengah dari total tinggi wrapper */
    }

    .item {
        background: white;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 15px;
        border-left: 10px solid #cbd5e1;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        position: relative;
        min-height: 110px;
    }

    .status-belum {
        border-left-color: #f59e0b;
    }

    .status-dilayani {
        border-left-color: var(--primary-green);
    }

    .status-selesai {
        border-left-color: #22c55e;
        background: #f0fdf4;
    }

    .item .patient-name {
        font-size: 20px;
        font-weight: 800;
        text-transform: uppercase;
        padding-right: 120px;
    }

    .item .room-origin {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
    }

    .item .time-info {
        font-size: 12px;
        color: #94a3b8;
    }

    .no-antrian-box {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--accent-yellow);
        color: #000;
        font-size: 22px;
        font-weight: 900;
        padding: 5px 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border: 3px solid #ca8a04;
    }

    .badge {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 5px;
    }

    .badge-r {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-nr {
        background: #dcfce7;
        color: #16a34a;
    }
    </style>
</head>

<body>

    <div id="header-top">
        <span>ANTRIAN FARMASI RSUD LAWANG</span>
        <button id="aktifkan-suara" class="btn btn-sm btn-primary">Aktifkan
            Suara</button>
        <span id="live-time">00:00:00</span>
    </div>

    <div class="main-layout">
        <div class="left-section">
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/TK6qVT2w0W0?autoplay=0&mute=0&loop=0&playlist=TK6qVT2w0W0"
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
            <div id="nama-dipanggil" class="animate__animated animate__fadeIn">
                <div class="label-panggil">PASIEN DIPANGGIL:</div>
                <div id="antrean-panggilan">-</div>
                <div id="nama-panggilan">-</div>
                <div id="deskPangil">NOMOR RESEP: -</div>
            </div>
        </div>

        <div class="antrian-container">
            <div class="column">
                <div class="col-header"><span class="counter" id="jumlah-belum">000</span><span class="label">Belum
                        Dilayani</span></div>
                <div class="column-content" id="cont-belum"></div>
            </div>
            <div class="column">
                <div class="col-header"><span class="counter" id="jumlah-dilayani">000</span><span class="label">Sedang
                        Dilayani</span></div>
                <div class="column-content" id="cont-dilayani"></div>
            </div>
            <div class="column">
                <div class="col-header"><span class="counter" id="jumlah-selesai">000</span><span class="label">Selesai
                        </span></div>
                <div class="column-content" id="cont-selesai"></div>
            </div>
        </div>
    </div>

    <script>
    let memoryAsal = {};
    let memoryNoAntrian = {};
    let lastDataJSON = ""; // Untuk menyimpan sidik jari data terakhir
    let suaraDiaktifkan = false;

    function updateClock() {
        const now = new Date();
        document.getElementById("live-time").textContent = now.toLocaleTimeString("id-ID");
    }
    setInterval(updateClock, 1000);

    $('#aktifkan-suara').on('click', function() {
        suaraDiaktifkan = true;
        alert("✅ Suara aktif — sistem siap memanggil antrian.");
        $(this).hide(); // sembunyikan tombol        
    });

    function loadAntrian() {
        fetch("get_antrian.php")
            .then(res => res.json())
            .then(data => {
                // --- LOGIKA ANTI-RESET SCROLL ---
                // Kita bandingkan data baru dengan data lama dalam bentuk string
                let currentDataJSON = JSON.stringify(data);
                if (currentDataJSON === lastDataJSON) {
                    // Jika data identik, jangan lakukan apa-apa. 
                    // Biarkan animasi scroll yang sedang jalan tetap lanjut.
                    return;
                }
                // Simpan data terbaru untuk perbandingan berikutnya
                lastDataJSON = currentDataJSON;

                const categories = {
                    "belum_diterima": "#cont-belum",
                    "dilayani": "#cont-dilayani",
                    "selesai": "#cont-selesai"
                };

                Object.keys(categories).forEach(key => {
                    const container = document.querySelector(categories[key]);
                    let itemsHtml = "";
                    let filteredList = [];

                    if (data[key]) {
                        filteredList = data[key].filter(item => {
                            let asal = (item.ASAL_RUANGAN || "").toUpperCase();
                            return !asal.includes("UNIT TERKAIT") && !asal.includes("PERAWATAN") &&
                                !asal.includes("INAP");
                        });
                    }

                    if (filteredList.length > 0) {
                        filteredList.forEach(item => {
                            memoryAsal[item.NOPEN] = item.ASAL_RUANGAN;
                            memoryNoAntrian[item.NOPEN] = item.NO_ANTRIAN;

                            let badge = item.RACIKAN == "1" ?
                                '<span class="badge badge-r">RACIKAN</span>' :
                                '<span class="badge badge-nr">NON RACIK</span>';
                            let nomorPoli = item.NO_ANTRIAN || "-";

                            itemsHtml += `
                        <div class="item status-${key.split('_')[0]}">
                            ${badge}
                            <div class="no-antrian-box">${nomorPoli}</div>
                            <div class="patient-name">${item.NAMA}</div>
                            <div class="room-origin">${item.ASAL_RUANGAN}</div>
                            <div class="time-info">${item.TANGGAL}</div>
                        </div>`;
                        });

                        if (filteredList.length > 4) {
                            let speed = filteredList.length * 4; // Sedikit dilambatkan biar enak dibaca
                            container.innerHTML = `<div class="scroll-wrapper" style="animation-duration: ${speed}s">
                        ${itemsHtml} ${itemsHtml}
                    </div>`;
                        } else {
                            container.innerHTML = itemsHtml;
                        }
                    } else {
                        container.innerHTML =
                            `<div style="text-align:center; padding:20px; color:#94a3b8;">Tidak ada antrian</div>`;
                    }
                });

                document.getElementById("jumlah-belum").textContent = String(data.total.belum_diterima).padStart(3,
                    '0');
                document.getElementById("jumlah-dilayani").textContent = String(data.total.dilayani).padStart(3,
                    '0');
                document.getElementById("jumlah-selesai").textContent = String(data.total.selesai).padStart(3, '0');
            });
    }

    // Refresh data setiap 5 detik (lebih cepat gapapa karena sudah ada proteksi return di atas)
    setInterval(loadAntrian, 5000);
    loadAntrian();

    // --- LOGIKA CEK PANGGILAN (Tetap sama) ---
    let lastHash = "";

    function cekPanggilan() {
        fetch("last_panggilan.txt?rnd=" + Math.random()).then(res => res.text()).then(txt => {
            if (!txt.trim()) return;
            let data;
            try {
                data = JSON.parse(txt);
            } catch (e) {
                return;
            }
            const hash = data.nopen + data.waktu;
            if (hash !== lastHash) {
                lastHash = hash;
                let noAntreanPoli = memoryNoAntrian[data.nopen] || "-";
                document.getElementById("nama-panggilan").innerHTML = data.nama;
                document.getElementById("antrean-panggilan").innerHTML = noAntreanPoli;
                document.getElementById("deskPangil").innerHTML = "NOMOR RESEP: " + data.nopen;
                const el = document.getElementById("nama-dipanggil");
                el.classList.remove("animate__fadeIn");
                void el.offsetWidth;
                el.classList.add("animate__fadeIn");

                // Panggil Suara
                panggilSuara(data.nama, memoryAsal[data.nopen], noAntreanPoli);
            }
        });
    }
    setInterval(cekPanggilan, 3000);

    function formatSuara(teks) {
        if (!teks) return "";
        let hasil = teks.toUpperCase();
        hasil = hasil.replace(/\bUGD\b/g, "Unit Gawat Darurat").replace(/\bIGD\b/g, "Instalasi Gawat Darurat").replace(
            /\bPOLI\b/g, "Poli");
        return hasil.toLowerCase().split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ');
    }

    function ejaNomorAntrean(nomor) {
        if (!nomor || nomor === "-") return "";
        let parts = nomor.split('-');
        let prefix = parts[0].split('').join(' , ').replace("1", "satu").replace("2", "dua");
        let angka = parts[1] || "";
        let ejaAngka = angka.split('').join(' , ').replace(/0/g, "nol");
        return prefix + " , " + ejaAngka;
    }

    function panggilSuara(nama, asal, nomor) {
        let kalimat =
            `Nomor Antrean , ${ejaNomorAntrean(nomor)} , , Keluarga pasien , ${formatSuara(nama)} , dari , ${formatSuara(asal)} , silakan mengambil obat di loket apotek.`;
        const u = new SpeechSynthesisUtterance(kalimat);
        u.lang = "id-ID";
        u.rate = 0.8;
        speechSynthesis.cancel();
        speechSynthesis.speak(u);
    }
    </script>
</body>

</html>