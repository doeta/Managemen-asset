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
    <h3 class="pb-2 mb-3">Laporan Pemakaian Aset</h3>

    <!-- Filter Section -->
    <div class="row mb-4">
      <div class="col-md-4 mb-2">
        <label for="tahun" class="form-label">Filter Tahun</label>
        <select class="form-select" name="tahun" id="tahun">
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
            rsort($tahunList);
            foreach ($tahunList as $tahun) {
              echo '<option value="'.esc($tahun).'">'.esc($tahun).'</option>';
            }
          }
          ?>
        </select>
      </div>
      <div class="col-md-4 mb-2">
        <label for="kategori" class="form-label">Filter Kategori</label>
        <select class="form-select" name="kategori" id="kategori">
          <option value="">Semua Kategori</option>
          <?php
          $kategoriList = [];
          if (!empty($pemakaian)) {
            foreach ($pemakaian as $row) {
              if (!empty($row['nama_kategori'])) {
                $kategoriList[$row['kode_kategori']] = $row['nama_kategori'];
              }
            }
            asort($kategoriList);
            foreach ($kategoriList as $kode => $nama) {
              echo '<option value="'.esc($kode).'">'.esc($nama).'</option>';
            }
          }
          ?>
        </select>
      </div>
      <div class="col-md-4 mb-2">
        <label for="sub_kategori" class="form-label">Filter Sub Kategori</label>
        <select class="form-select" name="sub_kategori" id="sub_kategori" disabled>
          <option value="">Semua Sub Kategori</option>
        </select>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="d-flex flex-wrap align-items-center gap-2">
          <a id="pdfExportBtn" href="#" class="btn btn-danger btn-sm" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
          </a>
          <a id="excelExportBtn" href="#" class="btn btn-success btn-sm" target="_blank">
            <i class="fas fa-file-excel"></i> Excel
          </a>
          <button type="button" id="resetFilters" class="btn btn-secondary btn-sm">
            <i class="fas fa-refresh"></i> Reset Filter
          </button>
        </div>
      </div>
      <div class="col-md-6">
        <div id="filterInfo" class="alert alert-info mb-0" style="display: none;">
          <strong>Filter Aktif:</strong> <span id="activeFilters"></span>
        </div>
      </div>
    </div>

    <!-- Info Total -->
    <div class="row mt-3">
      <div class="col-md-12">
        <div class="alert alert-secondary">
          <strong>Keterangan:</strong> Pilih filter yang diinginkan, kemudian klik tombol export untuk mengunduh laporan dalam format PDF atau Excel.
          <br>
          <strong>Total Data Tersedia: </strong><span id="totalData"><?= count($pemakaian ?? []) ?></span> item
          <span id="filteredInfo" style="display: none;"> | <strong>Data Setelah Filter: </strong><span id="filteredCount">0</span> item</span>
        </div>
      </div>
    </div>

    <!-- Preview Data -->
    <div class="row mt-3">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Preview Data Pemakaian</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="pemakaianTable" class="table table-bordered table-striped table-sm">
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
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
  $(document).ready(function () {
    const table = $('#pemakaianTable').DataTable({
      pageLength: 10,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
      }
    });

    var subKategoriByKategori = {};
    <?php if (!empty($pemakaian)) : ?>
      <?php 
      $subKategoriMap = [];
      foreach ($pemakaian as $row) {
        if (!empty($row['kode_kategori']) && !empty($row['kode_sub_kategori']) && !empty($row['nama_sub_kategori'])) {
          $subKategoriMap[$row['kode_kategori']][$row['kode_sub_kategori']] = $row['nama_sub_kategori'];
        }
      }
      ?>
      subKategoriByKategori = <?= json_encode($subKategoriMap) ?>;
    <?php endif; ?>

    function updateSubKategori() {
      var kategoriKode = $('#kategori').val();
      var $subKategori = $('#sub_kategori');
      $subKategori.empty().append('<option value="">Semua Sub Kategori</option>');
      if (kategoriKode && subKategoriByKategori[kategoriKode]) {
        $subKategori.prop('disabled', false);
        var subKategoriList = [];
        for (var kode in subKategoriByKategori[kategoriKode]) {
          subKategoriList.push({
            kode: kode,
            nama: subKategoriByKategori[kategoriKode][kode]
          });
        }
        subKategoriList.sort(function(a, b) {
          return a.nama.localeCompare(b.nama);
        });
        subKategoriList.forEach(function(item) {
          $subKategori.append('<option value="' + item.kode + '">' + item.nama + '</option>');
        });
      } else {
        $subKategori.prop('disabled', true);
      }
    }

    function updateExportLinks() {
      const tahun = $('#tahun').val() || '';
      const kategoriKode = $('#kategori').val() || '';
      const subKategoriKode = $('#sub_kategori').val() || '';
      $('#pdfExportBtn').attr('href', '/laporan/pemakaian/pdf?tahun=' + tahun + '&kategori=' + kategoriKode + '&sub_kategori=' + subKategoriKode);
      $('#excelExportBtn').attr('href', '/laporan/pemakaian/excel?tahun=' + tahun + '&kategori=' + kategoriKode + '&sub_kategori=' + subKategoriKode);
    }

    function updateFilterInfo() {
      const activeFilters = [];
      const tahun = $('#tahun').val();
      const kategori = $('#kategori option:selected').text();
      const subKategori = $('#sub_kategori option:selected').text();
      if (tahun) activeFilters.push('Tahun: ' + tahun);
      if (kategori && kategori !== 'Semua Kategori') activeFilters.push('Kategori: ' + kategori);
      if (subKategori && subKategori !== 'Semua Sub Kategori') activeFilters.push('Sub Kategori: ' + subKategori);
      if (activeFilters.length > 0) {
        $('#activeFilters').text(activeFilters.join(', '));
        $('#filterInfo').show();
      } else {
        $('#filterInfo').hide();
      }
    }

    function updateFilteredCount() {
      const filteredCount = table.rows({ search: 'applied' }).count();
      $('#filteredCount').text(filteredCount);
      if (table.search() || $('#tahun').val() || $('#kategori').val() || $('#sub_kategori').val()) {
        $('#filteredInfo').show();
      } else {
        $('#filteredInfo').hide();
      }
    }

    // Initialize
    updateSubKategori();
    updateExportLinks();
    updateFilterInfo();
    updateFilteredCount();

    // Event filter
    $('#tahun, #kategori, #sub_kategori').on('change', function () {
      updateSubKategori();
      updateExportLinks();
      updateFilterInfo();
      const tahun = $('#tahun').val();
      const kategoriNama = $('#kategori option:selected').text();
      const subKategoriNama = $('#sub_kategori option:selected').text();
      table.columns().search('').draw();
      if (tahun) table.columns(9).search(tahun);
      if (kategoriNama && kategoriNama !== 'Semua Kategori') table.columns(3).search(kategoriNama);
      if (subKategoriNama && subKategoriNama !== 'Semua Sub Kategori') table.columns(4).search(subKategoriNama);
      table.draw();
      updateFilteredCount();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
      $('#tahun').val('');
      $('#kategori').val('');
      $('#sub_kategori').val('');
      table.columns().search('').draw();
      updateSubKategori();
      updateExportLinks();
      updateFilterInfo();
      updateFilteredCount();
    });

    // Update filtered count on search
    table.on('draw', function() {
      updateFilteredCount();
    });
  });
  </script>
  <?= $this->endSection(); ?>
