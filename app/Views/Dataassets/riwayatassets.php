<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h4 class="mb-3">Riwayat Penggunaan Aset: <?= esc($barang['nama_barang'] ?? '-') ?></h4>

  <?php if (!empty($riwayat)) : ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Pengguna</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($riwayat as $row): ?>
            <tr>
            <td><?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?></td>
            <td><?= $row['tanggal_selesai'] ? date('d-m-Y', strtotime($row['tanggal_selesai'])) : '-' ?></td>
            <td><?= esc($row['nama_pengguna'] ?? '-') ?></td>
            <td><?= esc($row['jumlah_digunakan']) ?></td>
            <td><?= esc($row['satuan_penggunaan']) ?></td>
            <td><?= esc($row['keterangan']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
  <?php else : ?>
    <div class="alert alert-info">Belum ada riwayat penggunaan untuk aset ini.</div>   
  <?php endif; ?>

  <a href="<?= base_url('/dataassets/detail/' . $barang['kode_sub_kategori']) ?>" class="btn btn-secondary mt-3">Kembali</a>
</div>

<?= $this->endSection() ?>
