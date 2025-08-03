<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Pemakaian Aset</title>
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

  <!-- Header -->
  <div class="header">
    <h2>LAPORAN PEMAKAIAN ASET</h2>
    <h2>TAHUN <?= esc($tahun ?? date('Y')) ?></h2>
  </div>

  <!-- Info -->
  <div class="info-section">
    <div class="info-row"><strong>SKPD</strong> : Dinas Komunikasi dan Informatika</div>
    <div class="info-row"><strong>Tanggal Cetak</strong> : <?= date('d-m-Y') ?></div>
    <div class="info-row"><strong>Kategori</strong> : <?= esc($kategori ?? 'Semua Kategori') ?></div>
  </div>

  <!-- Table -->
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Kode Barang</th>
        <th>Kategori</th>
        <th>Sub Kategori</th>
        <th>Lokasi</th>
        <th>Pengguna</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Tanggal Pemakaian</th>
        <th>Tanggal Selesai</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($pemakaian)) : ?>
        <?php $no = 1; foreach ($pemakaian as $p) : ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($p['nama_barang'] ?? '-') ?></td>
            <td><?= esc($p['kode_barang'] ?? '-') ?></td>
            <td><?= esc($p['kode_kategori'] ?? '-') ?> - <?= esc($p['nama_kategori'] ?? '-') ?></td>
            <td><?= esc($p['kode_sub_kategori'] ?? '-') ?> - <?= esc($p['nama_sub_kategori'] ?? '-') ?></td>
            <td><?= esc($p['nama_lokasi'] ?? '-') ?></td>
            <td><?= esc($p['nama_pengguna'] ?? '-') ?></td>
            <td><?= esc($p['jumlah_digunakan'] ?? '-') ?></td>
            <td><?= esc($p['satuan_penggunaan'] ?? '-') ?></td>
            <td><?= esc($p['tanggal_mulai'] ?? '-') ?></td>
            <td><?= esc($p['tanggal_selesai'] ?? '-') ?></td>
            <td><?= ucfirst(esc($p['status'] ?? '-')) ?></td>
            <td><?= esc($p['keterangan'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr><td colspan="13">Tidak ada data pemakaian</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Signature -->
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
