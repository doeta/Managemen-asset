<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Kendaraan Dinas</title>
  <style>
    @page {
        size: A4 portrait;
        margin: 20mm;
    }
    body {
      font-family: 'Times New Roman', Times, serif;
      margin: 20px;
      font-size: 12px;
    }
    
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .header h2 {
      margin: 5px 0;
      font-size: 14px;
      font-weight: bold;
      line-height: 1.2;
    }
    
    .info-section {
      margin-bottom: 20px;
    }
    
    .info-row {
      margin-bottom: 5px;
    }
    
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-bottom: 30px;
    }
    
    th, td { 
      border: 1px solid #000; 
      padding: 6px; 
      text-align: center;
      vertical-align: middle;
    }
    
    th {
      background: #f5f5f5;
      font-weight: bold;
      font-size: 10px;
    }
    
    td {
      font-size: 9px;
    }
    
    .no-col {
      width: 5%;
    }
    
    .jenis-col {
      width: 8%;
    }
    
    .nama-col {
      width: 15%;
      text-align: center;
      font-size: 9px;
    }
    
    .merk-col {
      width: 12%;
      text-align: center;
      font-size: 9px;
    }
    
    .warna-col {
      width: 10%;
    }
    
    .nopol-col {
      width: 12%;
    }
    
    .tahun-col {
      width: 8%;
    }
    
    .lokasi-col {
      width: 15%;
      text-align: center;
      font-size: 9px;
    }
    
    .pemilik-col {
      width: 15%;
      text-align: center;
      font-size: 9px;
    }
    
    .signature-section {
      margin-top: 40px;
      text-align: right;
    }
    
    .signature-section p {
      margin: 5px 0;
      font-size: 11px;
    }
    
    .signature-name {
      font-weight: bold;
      margin-top: 60px;
    }
    
    .signature-title {
      font-style: italic;
    }
    
    .signature-nip {
      font-size: 10px;
    }
    
    .no-data {
      text-align: center;
      font-style: italic;
    }
  </style>
</head>
<body>
  <!-- DEBUG: Tampilkan data untuk memastikan -->
  <?php if (!empty($kendaraan)): ?>
    <div style="display: none;">
      <?php foreach($kendaraan as $key => $row): ?>
        <!-- Debug: <?= $key ?>: Lokasi=<?= $row['nama_lokasi'] ?? 'NULL' ?>, Pemilik=<?= $row['nama_pengguna'] ?? 'NULL' ?> -->
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- Header Section -->
  <div class="header">
    <h2>LAPORAN KENDARAAN DINAS</h2>
    <h2>TAHUN <?= isset($tahun) ? esc($tahun) : date('Y') ?></h2>
  </div>

  <!-- Info Section -->
  <div class="info-section">
    <div class="info-row">
      <strong>SKPD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Dinas Komunikasi dan Informatika</strong>
    </div>
    <div class="info-row">
      <strong>Tanggal Cetak &nbsp;&nbsp;&nbsp;: <?= date('d-m-Y') ?></strong>
    </div>
    <div class="info-row">
      <strong>Jenis Kendaraan &nbsp;: <?= isset($jenis) && $jenis !== 'Semua' ? ucfirst($jenis) : 'Semua Jenis' ?></strong>
    </div>
  </div>

  <!-- Main Table -->
  <table>
    <thead>
      <tr>
        <th class="no-col">No</th>
        <th class="jenis-col">Jenis</th>
        <th class="nama-col">Nama Kendaraan</th>
        <th class="merk-col">Merk/Type</th>
        <th class="warna-col">Warna</th>
        <th class="nopol-col">Nomor Polisi</th>
        <th class="tahun-col">Tahun</th>
        <th class="lokasi-col">Lokasi</th>
        <th class="pemilik-col">Pemilik</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($kendaraan)) : ?>
        <?php foreach ($kendaraan as $key => $row) : ?>
          <tr>
            <td><?= $key + 1 ?></td>
            <td><?= esc(ucwords($row['model_kendaraan'] ?? 'N/A')) ?></td>
            <td><?= esc($row['nama_kendaraan'] ?? 'N/A') ?></td>
            <td><?= esc($row['merk_kendaraan'] ?? 'N/A') ?></td>
            <td><?= esc($row['warna'] ?? 'N/A') ?></td>
            <td><?= esc($row['no_polisi'] ?? 'N/A') ?></td>
            <td><?= esc($row['tahun_kendaraan'] ?? 'N/A') ?></td>
            <td><?= isset($row['nama_lokasi']) ? esc($row['nama_lokasi']) : 'Tidak Ada' ?></td>
            <td><?= isset($row['nama_pengguna']) ? esc($row['nama_pengguna']) : 'Tidak Ada' ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="9" class="no-data">Tidak ada data kendaraan</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Summary Section (Optional) -->
  <?php if (!empty($kendaraan)) : ?>
    <div style="margin-bottom: 20px;">
      <strong>Total Kendaraan: <?= count($kendaraan) ?> unit</strong>
    </div>
  <?php endif; ?>

  <!-- Signature Section -->
  <div class="signature-section">
    <p>Kebumen, <?= date('d') ?> <?= 
      ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n')] 
    ?> <?= date('Y') ?></p>
    <p>Mahasiswa Magang Dinas Komunikasi dan Informatika Kabupaten Kebumen</p>
    
    <div style="margin-top: 60px;">
      <p class="signature-name">Duta Adi Pamungkas</p>
      <p class="signature-title">Mahasiswa Undip</p>
      <p class="signature-nip">24060123140174</p>
    </div>
  </div>
</body>
</html>