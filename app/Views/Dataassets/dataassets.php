<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<head>
  <meta charset="UTF-8">
  <title>Laporan Pemakaian Aset</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<div class="my-3 p-3 bg-body rounded shadow-sm">
  <h3 class="pb-2 mb-3">Daftar Sub Kategori Barang</h3>

  <!-- Filter Kategori -->
  <div class="row mb-3">
    <div class="col-md-4">
      <form method="GET" action="<?= base_url('dataassets') ?>">
        <div class="form-group">
          <label for="kategori">Filter Kategori:</label>
          <select class="form-control" id="kategori" name="kategori" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php if (!empty($kategori)) : ?>
              <?php foreach ($kategori as $kat) : ?>
                <option value="<?= esc($kat['nama_kategori']) ?>" <?= ($selected_kategori == $kat['nama_kategori']) ? 'selected' : '' ?>>
                  <?= esc($kat['nama_kategori']) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
      </form>
    </div>
    <div class="col-md-8 d-flex align-items-end">
      <?php if ($selected_kategori) : ?>
        <div class="alert alert-info mb-0">
          Menampilkan data untuk kategori: <strong><?= esc($selected_kategori) ?></strong>
          <a href="<?= base_url('dataassets') ?>" class="btn btn-sm btn-secondary ml-2">Reset Filter</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Tabel Sub Kategori -->
  <table class="table table-bordered table-striped">
    <thead class="bg-black text-white">
      <tr>
        <th>No</th>
        <th>Kode Sub Kategori</th>
        <th>Nama Sub Kategori</th>
        <th>Kategori</th>
        <th>Jumlah Barang</th>
        <th>Aksi</th>
      </tr>
    </thead>
      <tbody>
        <?php if (!empty($sub_kategori)) : ?>
          <?php foreach ($sub_kategori as $i => $sub) : ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($sub['kode_sub_kategori']) ?></td>
              <td><?= esc($sub['nama_sub_kategori']) ?></td>
              <td><?= esc($sub['nama_kategori']) ?></td>
              <td><?= esc($sub['jumlah_barang'] ?? 0) ?></td>
              <td>
                <a href="<?= base_url('dataassets/detailSubKategori/' . $sub['kode_sub_kategori']) ?>" class="btn btn-info btn-sm">
                  <i class="fas fa-eye"></i> Lihat Detail
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="6" class="text-center">Tidak ada data sub kategori yang tersedia.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Statistik -->
  <div class="mt-3">
    <div class="alert alert-secondary">
      <strong>Statistik:</strong> Total: 
      <span class="badge badge-primary"><?= count($sub_kategori) ?></span>
      <?php if ($selected_kategori) : ?>
        | Filter Aktif: <span class="badge badge-info"><?= esc($selected_kategori) ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#dataassetsTable').DataTable();
  });
</script>

<?= $this->endSection(); ?>
