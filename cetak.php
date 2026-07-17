<?php
    date_default_timezone_set('Asia/Jakarta');
    $date    = date('Y-m-d');
    $tanggal = '';

    if (isset($_REQUEST['cetak'])) {
    $antrian = $_REQUEST['no_antrian'];
    $rm      = $_REQUEST['rm'];
    $nama    = $_REQUEST['nama'];
    $jenis   = ($_REQUEST['racikan'] == 0) ? 'NON RACIKAN' : 'RACIKAN';
    $tanggal = (isset($_REQUEST['tanggal']) && $_REQUEST['tanggal'] != '') ? $_REQUEST['tanggal'] : $date;
    }
?>
<html>
<style>
@media all {
    .page-break {
        display: none;
    }
}

@media print {
    .no-print {
        visibility: hidden;
    }

    .page-break {
        display: block;
        page-break-before: always;
    }

}

@page {
    size: 80mm auto;
    margin: 0;
}

@media print {

    html,
    body {
        width: 80mm;
        margin: 0;
        padding: 0;
    }

    .sheet {
        width: 72mm;
        /* area cetak efektif */
        margin: auto;
        text-align: center;
    }

    .page-break {
        page-break-before: always;
    }
}


p {
    margin: 0pt;
}

.antrian_head1{
    font-size:16pt;
    font-weight:bold;
    text-align:center;
}

.antrian_head2{
    font-size:13pt;
    font-weight:bold;
    text-align:center;
    margin-top:2px;
    margin-bottom:6px;
}

.antrian{
    font-size:48pt;
    font-weight:bold;
    text-align:center;
    line-height:1;
    margin:6px 0;
    font-family:Arial, sans-serif;
}

.antrian_nama{
    font-size:11pt;
    font-weight:bold;
    text-align:center;
    text-transform:uppercase;
    margin-top:5px;
    word-wrap:break-word;
}

.antrian_rm{
    font-size:10pt;
    text-align:center;
    margin-top:2px;
}

.antrian_tanggal{
    font-size:10pt;
    text-align:center;
    margin-top:5px;
}

.antrian_foot{
    font-size:9pt;
    text-align:center;
    margin-top:8px;
}
</style>

<script>
window.onload = function () {
    window.print();
}
</script>

<body>
    <div class="sheet">

    <div class="antrian_head1">
        ANTRIAN FARMASI
    </div>

    <div class="antrian_head2">
        <?php echo $jenis; ?>
    </div>

    <div class="antrian">
        <?php echo $antrian; ?>
    </div>

    <div class="antrian_nama">
        <?php echo $nama; ?>
    </div>

    <div class="antrian_rm">
        No. RM : <?php echo $rm; ?>
    </div>

    <div class="antrian_tanggal">
        <?php echo $tanggal; ?>
    </div>

    <div class="antrian_foot">
        Semoga Lekas Sembuh
    </div>

</div>

    <div class="page-break"></div>
    <div class="sheet">

    <div class="antrian_head1">
        ANTRIAN FARMASI
    </div>

    <div class="antrian_head2">
        <?php echo $jenis; ?>
    </div>

    <div class="antrian">
        <?php echo $antrian; ?>
    </div>

    <div class="antrian_nama">
        <?php echo $nama; ?>
    </div>

    <div class="antrian_rm">
        No. RM : <?php echo $rm; ?>
    </div>

    <div class="antrian_tanggal">
        <?php echo $tanggal; ?>
    </div>

    <div class="antrian_foot">
        Semoga Lekas Sembuh
    </div>
</body>

</html>