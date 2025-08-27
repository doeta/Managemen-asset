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
  <h3 class="pb-2 mb-3">Laporan Kendaraan Dinas</h3>

  <!-- Filter Section -->
  <div class="row mb-4">
    <div class="col-md-4 mb-2">
      <label for="tahun" class="form-label">Filter Tahun</label>
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
          rsort($tahunList);
          foreach ($tahunList as $tahun) {
            echo '<option value="'.esc($tahun).'">'.esc($tahun).'</option>';
          }
        }
        ?>
      </select>
    </div>
    <div class="col-md-4 mb-2">
      <label for="jenis" class="form-label">Filter Jenis Kendaraan</label>
      <select class="form-select" name="jenis" id="jenis">
        <option value="">Semua Jenis</option>
        <?php
        $jenisList = ['mobil', 'motor'];
        foreach ($jenisList as $jenis) {
          echo '<option value="'.esc($jenis).'">'.esc(ucwords($jenis)).'</option>';
        }
        ?>
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
        <strong>Total Data Tersedia: </strong><span id="totalData"><?= count($kendaraan ?? []) ?></span> item
        <span id="filteredInfo" style="display: none;"> | <strong>Data Setelah Filter: </strong><span id="filteredCount">0</span> item</span>
      </div>
    </div>
  </div>

  <!-- Preview Data -->
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Preview Data Kendaraan</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="kendaraanTable" class="table table-bordered table-striped table-sm">
              <thead class="table-light">
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
                    <td colspan="9" class="text-center">Tidak ada data kendaraan</td>
                  </tr>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
$(document).ready(function () {
  const table = $('#kendaraanTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    }
  });

  function updateExportLinks() {
    const tahun = $('#tahun').val() || 'Semua';
    const jenis = $('#jenis').val() || 'Semua';

    $('#pdfExportBtn').attr('href', `/laporan/kendaraan/pdf/${tahun}?jenis=${encodeURIComponent(jenis)}`);
    $('#excelExportBtn').attr('href', `/laporan/kendaraan/excel/${tahun}?jenis=${encodeURIComponent(jenis)}`);
  }

  function updateFilterInfo() {
    const activeFilters = [];
    const tahun = $('#tahun').val();
    const jenis = $('#jenis').val();

    if (tahun) activeFilters.push('Tahun: ' + tahun);
    if (jenis) activeFilters.push('Jenis: ' + jenis);

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
    
    if (table.search() || $('#tahun').val() || $('#jenis').val()) {
      $('#filteredInfo').show();
    } else {
      $('#filteredInfo').hide();
    }
  }

  // Initialize
  updateExportLinks();
  updateFilterInfo();
  updateFilteredCount();

  // Event filter
  $('#tahun, #jenis').on('change', function () {
    updateExportLinks();
    updateFilterInfo();

    const tahun = $('#tahun').val();
    const jenis = $('#jenis').val();

    table.columns(6).search(tahun); // Kolom ke-6 = Tahun
    table.columns(1).search(jenis); // Kolom ke-1 = Jenis
    table.draw();
    
    updateFilteredCount();
  });

  // Reset filters
  $('#resetFilters').on('click', function() {
    $('#tahun').val('');
    $('#jenis').val('');
    
    table.columns().search('').draw();
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