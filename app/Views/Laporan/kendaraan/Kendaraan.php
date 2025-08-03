<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Persediaan Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
</head>
<body>


<div class="container mt-5">
  <h5 class="mb-4"><strong>Laporan Kendaraan Dinas</strong></h5>

  <div class="card shadow-sm">
    <div class="card-body">
      <form class="mb-4" method="post">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <select class="form-select" name="tahun" id="tahun">
              <option value="">Semua Tahun</option>
              <?php
              $tahunList = [];
              if (!empty($kendaraan)) {
                foreach ($kendaraan as $row) {
                  if (!empty($row['tahun_kendaraan'])) {
                    $tahunList[] = $row['tahun_kendaraan'];
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
            <label for="jenis" class="form-label">Jenis Kendaraan</label>
            <select class="form-select" name="jenis" id="jenis">
              <option value="">Semua</option>
              <?php
              $jenisList = ['mobil', 'motor'];
              foreach ($jenisList as $jenis) {
                echo '<option value="'.esc($jenis).'">'.esc(ucwords($jenis)).'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-8 d-flex align-items-end">
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
        </div>
      </form>
      <div class="table-responsive">
        <table id="kendaraanTable" class="table table-bordered table-striped">
          <thead class="table-light">
            <h4>Data Kendaraan</h4>
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
              <?php foreach ($kendaraan as $key => $row) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <td><?= esc(ucwords($row['model_kendaraan'] ?? '')) ?></td>
                  <td><?= esc($row['nama_kendaraan']?? '') ?></td>
                  <td><?= esc($row['merk_kendaraan'] ?? '') ?></td>
                  <td><?= esc($row['warna'] ?? '') ?></td>
                  <td><?= esc($row['no_polisi'] ?? '') ?></td>
                  <td><?= esc($row['tahun_kendaraan'] ?? '') ?></td>
                  <td><?= esc($row['nama_lokasi'] ?? '') ?></td>
                  <td><?= esc($row['nama_pengguna'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="8" class="text-center">Tidak ada data kendaraan</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

    <!-- jQuery + DataTables + Buttons -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!-- PDFMake untuk PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function () {
      const table = $('#kendaraanTable').DataTable({ dom: 'lrtip' });

      function updateExportLinks() {
        const tahun = $('#tahun').val() || 'Semua';
        const jenis = $('#jenis').val() || 'Semua';

        $('#pdfExportBtn').attr('href', `/laporan/kendaraan/pdf/${tahun}?jenis=${encodeURIComponent(jenis)}`);
        $('#excelExportBtn').attr('href', `/laporan/kendaraan/excel/${tahun}?jenis=${encodeURIComponent(jenis)}`);
      }

      // Panggil saat halaman pertama kali load
      updateExportLinks();

      // Event filter
      $('#tahun, #jenis').on('change', function () {
        updateExportLinks(); // update link export

        const tahun = $('#tahun').val();
        const jenis = $('#jenis').val();

        table.columns(6).search(tahun); // Kolom ke-6 = Tahun
        table.columns(1).search(jenis); // Kolom ke-1 = Jenis
        table.draw();
      });

      // Fungsi pemicu ekspor (tidak perlu pakai window.open jika sudah pakai tombol link)
      window.submitExport = function(format) {
        const tahun = $('#tahun').val() || 'Semua';
        const jenis = $('#jenis').val() || 'Semua';
        const action = `/laporan/kendaraan/${format}/${tahun}?jenis=${encodeURIComponent(jenis)}`;
        window.open(action, '_blank');
      };
    });
    </script>


<?= $this->endSection(); ?>

