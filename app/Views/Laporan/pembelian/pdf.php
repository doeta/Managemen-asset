<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Pembelian Aset</title>
  <style>
    body { font-family: sans-serif; font-size: 11px; }
    h3 { text-align: center; margin-bottom: 5px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    th { background: #eee; }
  </style>
</head>
<body>
  <h3>LAPORAN PEMBELIAN ASET<br>DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN KEBUMEN<br>TAHUN <?= date('Y') ?></h3>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Asset</th>
        <th>Kategori</th>
        <th>Sub Kategori</th>
        <th>Tanggal Masuk</th>
        <th>Jumlah</th>
        <th>Harga Satuan</th>
        <th>Total Harga</th>
        <th>Deskripsi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($pembelian)) : ?>
        <?php foreach ($pembelian as $i => $row) : ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($row['nama_barang']) ?></td>
            <td><?= esc($row['nama_kategori']) ?></td>
            <td><?= esc($row['nama_sub_kategori']) ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_pembelian'])) ?></td>
            <td><?= $row['jumlah_dibeli'] ?></td>
            <td><?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
            <td><?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            <td><?= esc($row['deskripsi_barang']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="9">Tidak ada data pembelian</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
