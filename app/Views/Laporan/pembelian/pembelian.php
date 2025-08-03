<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Pembelian Asset</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container mt-5">
  <h5 class="mb-4"><strong>Laporan Pembelian Asset</strong></h5>

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
              if (!empty($pembelian)) {
                foreach ($pembelian as $row) {
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
            <select class="form-select" name="kategori" id="kategori">
              <option value="">Semua Kategori</option>
              <?php
              $kategoriList = [];
              if (!empty($pembelian)) {
                foreach ($pembelian as $row) {
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
          <div class="col-md-4">
            <label for="subkategori" class="form-label">Sub Kategori</label>
            <select class="form-select" name="subkategori" id="subkategori">
              <option value="">Semua Sub Kategori</option>
              <?php
              $subkategoriList = [];
              if (!empty($pembelian)) {
                foreach ($pembelian as $row) {
                  if (!empty($row['nama_sub_kategori'])) {
                    $subkategoriList[] = $row['nama_sub_kategori'];
                  }
                }
                $subkategoriList = array_unique($subkategoriList);
                sort($subkategoriList);
                foreach ($subkategoriList as $sub) {
                  echo '<option value="' . esc($sub) . '">' . esc($sub) . '</option>';
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="button" id="exportExcel" class="btn btn-success me-2">
              <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button type="button" id="exportPdf" class="btn btn-danger">
              <i class="fas fa-file-pdf"></i> Cetak PDF
            </button>
          </div>
        </div>
      </form>
      <div class="table-responsive">
        <table id="pembelianTable" class="table table-bordered table-striped">
          <thead class="table-light">
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
              <?php foreach ($pembelian as $key => $row) : ?>
                  <tr>
                      <td><?= $key + 1 ?></td>
                      <td><?= esc($row['nama_barang'] ?? '') ?></td>
                      <td><?= esc($row['nama_kategori'] ?? '') ?></td>
                      <td><?= esc($row['nama_sub_kategori'] ?? '') ?></td>
                      <td><?= esc($row['tanggal_pembelian'] ?? '') ?></td>
                      <td><?= esc($row['jumlah_dibeli'] ?? '') ?></td>
                      <td><?= number_format($row['harga_satuan'] ?? 0, 0, ',', '.') ?></td>
                      <td><?= number_format($row['total_harga'] ?? 0, 0, ',', '.') ?></td>
                      <td><?= esc($row['deskripsi_barang'] ?? '') ?></td>
                  </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="9" class="text-center">Tidak ada data pembelian</td>
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
    <!-- PDFMake -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
    $(document).ready(function () {
      var table = $('#pembelianTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excelHtml5',
            title: 'Laporan Pembelian Aset',
            exportOptions: { columns: ':visible' },
            className: 'd-none'
          },
          {
            extend: 'pdfHtml5',
            title: '',
            orientation: 'portrait',
            pageSize: 'A4',
            exportOptions: { columns: ':visible' },
            className: 'd-none',
            customize: function (doc) {
              var skpd = 'Nama SKPD Anda'; // Ganti sesuai kebutuhan
              var tahun = $('#tahun').val() || 'Semua';
              var tglCetak = new Date().toLocaleDateString('id-ID');

              doc.content.splice(0, 0, {
                alignment: 'center',
                text: [
                  { text: 'LAPORAN PEMBELIAN ASET\n', fontSize: 14, bold: true },
                  { text: 'SKPD: ' + skpd + '\n', fontSize: 11 },
                  { text: 'Tahun: ' + tahun + '\n', fontSize: 11 },
                  { text: 'Tanggal Cetak: ' + tglCetak + '\n\n', fontSize: 11 }
                ],
                margin: [0, 0, 0, 10]
              });

              // Set lebar kolom agar muat di A4 Portrait
              doc.content[1].table.widths = [20, 60, 50, 50, 45, 35, 50, 50, 75];

              // Ubah header kolom
              doc.content[1].table.body[0] = [
                'No', 'Nama Asset', 'Kategori', 'Sub Kategori', 'Tanggal Masuk',
                'Jumlah', 'Harga Satuan', 'Total Harga', 'Deskripsi'
              ];

              doc.styles.tableHeader = {
                bold: true,
                fontSize: 9,
                color: 'black',
                fillColor: '#eeeeee',
                alignment: 'center'
              };

              doc.styles.tableBodyEven = { fontSize: 8 };
              doc.styles.tableBodyOdd = { fontSize: 8 };

              doc.content[1].layout = {
                hLineWidth: function (i) { return 0.5; },
                vLineWidth: function (i) { return 0.5; },
                hLineColor: function (i) { return '#000'; },
                vLineColor: function (i) { return '#000'; }
              };

              doc.footer = function (currentPage, pageCount) {
                return {
                  text: 'Halaman ' + currentPage.toString() + ' dari ' + pageCount,
                  alignment: 'right',
                  margin: [0, 10, 40, 0],
                  fontSize: 8
                };
              };
            }
          }
        ]
      });
      // Filter berdasarkan Tahun
      $('#tahun').on('change', function () {
        table.column(4).search(this.value).draw();
      });
      
      // Filter berdasarkan Kategori
      $('#kategori').on('change', function () {
        table.column(2).search(this.value).draw();
      });

      // Filter berdasarkan Sub Kategori
      $('#subkategori').on('change', function () {
        table.column(3).search(this.value).draw();
      });

      // Tombol Export manual
      $('#exportExcel').on('click', function () {
        table.button(0).trigger();
      });

      $('#exportPdf').on('click', function () {
        table.button(1).trigger();
      });
    });
    </script>


</body>
</html>
<?= $this->endSection(); ?>
