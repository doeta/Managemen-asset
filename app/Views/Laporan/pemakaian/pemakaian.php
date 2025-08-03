<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Pemakaian Aset</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>

<div class="container mt-5">
  <h5 class="mb-4"><strong>Laporan Pemakaian Aset</strong></h5>

  <div class="card shadow-sm">
    <div class="card-body">
      <form class="mb-4" method="post">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <select class="form-select" id="tahun">
              <option value="">Semua Tahun</option>
              <?php
              $tahunList = [];
              if (!empty($pemakaian)) {
                foreach ($pemakaian as $row) {
                  if (!empty($row['tanggal_mulai'])) {
                    $tahunList[] = date('Y', strtotime($row['tanggal_mulai']));
                  }
                }
                $tahunList = array_unique($tahunList);
                sort($tahunList);
                foreach ($tahunList as $tahun) {
                  echo '<option value="'.esc($tahun).'">'.esc($tahun).'</option>';
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori">
              <option value="">Semua Kategori</option>
              <?php
              $kategoriList = [];
              if (!empty($pemakaian)) {
                foreach ($pemakaian as $row) {
                  if (!empty($row['nama_kategori'])) {
                    $kategoriList[] = $row['nama_kategori'];
                  }
                }
                $kategoriList = array_unique($kategoriList);
                sort($kategoriList);
                foreach ($kategoriList as $kategori) {
                  echo '<option value="'.esc($kategori).'">'.esc($kategori).'</option>';
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-8 d-flex align-items-end">
            <div class="d-flex gap-2">
              <a id="pdfExportBtn" href="#" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Cetak PDF
              </a>
              <a id="excelExportBtn" href="#" class="btn btn-success" target="_blank">
                <i class="fas fa-file-excel"></i> Export Excel
              </a>
            </div>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <table id="pemakaianTable" class="table table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Kode</th>
              <th>Kategori</th>
              <th>Sub Kategori</th>
              <th>Lokasi</th>
              <th>Pengguna</th>
              <th>Jumlah</th>
              <th>Satuan</th>
              <th>Tgl Mulai</th>
              <th>Tgl Selesai</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pemakaian)) : ?>
              <?php foreach ($pemakaian as $key => $row) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= esc($row['nama_barang'] ?? '') ?></td>
                  <td><?= esc($row['kode_barang'] ?? '') ?></td>
                  <td><?= esc($row['nama_kategori'] ?? '') ?></td>
                  <td><?= esc($row['nama_sub_kategori'] ?? '') ?></td>
                  <td><?= esc($row['nama_lokasi'] ?? '') ?></td>
                  <td><?= esc($row['nama_pengguna'] ?? '') ?></td>
                  <td><?= esc($row['jumlah_digunakan'] ?? '') ?></td>
                  <td><?= esc($row['satuan_penggunaan'] ?? '') ?></td>
                  <td><?= esc($row['tanggal_mulai'] ?? '') ?></td>
                  <td><?= esc($row['tanggal_selesai'] ?? '-') ?></td>
                  <td><?= esc(ucwords($row['status'] ?? '-')) ?></td>
                  <td><?= esc($row['keterangan'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr><td colspan="13" class="text-center">Tidak ada data pemakaian</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
  var table = $('#pemakaianTable').DataTable({ dom: 'lrtip' });

  function updateExportLinks() {
    const tahun = $('#tahun').val() || 'Semua';
    const kategori = $('#kategori').val() || 'Semua';
    $('#pdfExportBtn').attr('href', '/laporan/pemakaian/pdf?tahun=' + tahun + '&kategori=' + encodeURIComponent(kategori));
    $('#excelExportBtn').attr('href', '/laporan/pemakaian/excel?tahun=' + tahun + '&kategori=' + encodeURIComponent(kategori));
  }

  // Saat halaman load
  updateExportLinks();

  $('#tahun, #kategori').on('change', function () {
    updateExportLinks();

    // Filter tabel berdasarkan tahun dan kategori
    let tahun = $('#tahun').val();
    let kategori = $('#kategori').val();

    table.columns(3).search(kategori).columns(9).search(tahun).draw();
  });
});
</script>

<?= $this->endSection(); ?>
