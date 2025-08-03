<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      font-size: 11px;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

<h3>Laporan Kendaraan Dinas</h3>
<p>Tahun: <?= esc($tahun ?? 'Semua') ?></p>
<p>SKPD: Dinas Komunikasi dan Informatika</p>
<p>Tanggal Cetak: <?= date('d-m-Y') ?></p>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Jenis</th>
      <th>Nama Kendaraan</th>
      <th>Merk/Type</th>
      <th>Warna</th>
      <th>Nomor Polisi</th>
      <th>Tahun</th>
      <th>Lokasi</th>
      <th>Pemilik</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($kendaraan)) : ?>
      <?php foreach ($kendaraan as $i => $row) : ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= esc($row['model_kendaraan'] ?? '-') ?></td>
          <td><?= esc($row['nama_kendaraan'] ?? '-') ?></td>
          <td><?= esc($row['merk_kendaraan'] ?? '-') ?></td>
          <td><?= esc($row['warna'] ?? '-') ?></td>
          <td><?= esc($row['no_polisi'] ?? '-') ?></td>
          <td><?= esc($row['tahun_kendaraan'] ?? '-') ?></td>
          <td><?= esc($row['nama_lokasi'] ?? '-') ?></td>
          <td><?= esc($row['nama_pengguna'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="9">Tidak ada data</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>
