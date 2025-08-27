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
  <h3 class="pb-2 mb-3">Laporan Pembelian Asset</h3>

  <!-- Filter Section -->
  <div class="row mb-4">
    <div class="col-md-4 mb-2">
      <label for="tahun" class="form-label">Filter Tahun</label>
      <select class="form-select" id="tahun">
        <option value="">Semua Tahun</option>
        <?php
        $tahunList = [];
        if (!empty($pembelian)) {
          foreach ($pembelian as $row) {
            if (!empty($row['tanggal_pembelian'])) {
              $tahun = date('Y', strtotime($row['tanggal_pembelian']));
              $tahunList[] = $tahun;
            }
          }
          $tahunList = array_unique($tahunList);
          rsort($tahunList); // Urutkan dari tahun terbaru
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
        if (!empty($pembelian)) {
          foreach ($pembelian as $row) {
            if (!empty($row['kode_kategori']) && !empty($row['nama_kategori'])) {
              $kategoriList[$row['kode_kategori']] = $row['nama_kategori'];
            }
          }
          foreach ($kategoriList as $kode => $nama) {
            echo '<option value="'.esc($kode).'">'.esc($nama).'</option>';
          }
        }
        ?>
      </select>
    </div>
    <div class="col-md-4 mb-2">
      <label for="subkategori" class="form-label">Filter Sub Kategori</label>
      <select class="form-select" name="subkategori" id="subkategori">
        <option value="">Semua Sub Kategori</option>
        <!-- Subkategori akan diisi via JS -->
      </select>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a id="exportPdf" href="#" class="btn btn-danger btn-sm" target="_blank">
          <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a id="exportExcel" href="#" class="btn btn-success btn-sm" target="_blank">
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
        <strong>Total Data Tersedia: </strong><span id="totalData"><?= count($pembelian ?? []) ?></span> item
        <span id="filteredInfo" style="display: none;"> | <strong>Data Setelah Filter: </strong><span id="filteredCount">0</span> item</span>
      </div>
    </div>
  </div>

  <!-- Preview Data -->
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Preview Data Pembelian</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="pembelianTable" class="table table-bordered table-striped table-sm">
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
  </div>
</div>

<!-- Scripts -->
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
  const table = $('#pembelianTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    }
  });

  // Ambil data subkategori dari PHP
  const subkategoriData = {};
  <?php
    $subkategoriMap = [];
    if (!empty($pembelian)) {
      foreach ($pembelian as $row) {
        if (!empty($row['kode_kategori']) && !empty($row['kode_sub_kategori']) && !empty($row['nama_sub_kategori'])) {
          // Pastikan subkategori tidak duplikat per kategori
          if (!isset($subkategoriMap[$row['kode_kategori']])) {
            $subkategoriMap[$row['kode_kategori']] = [];
          }
          $alreadyExists = false;
          foreach ($subkategoriMap[$row['kode_kategori']] as $sub) {
            if ($sub['kode'] === $row['kode_sub_kategori']) {
              $alreadyExists = true;
              break;
            }
          }
          if (!$alreadyExists) {
            $subkategoriMap[$row['kode_kategori']][] = [
              'kode' => $row['kode_sub_kategori'],
              'nama' => $row['nama_sub_kategori']
            ];
          }
        }
      }
    }
    echo 'const subkategoriMap = ' . json_encode($subkategoriMap) . ";\n";
  ?>

  function updateSubkategoriDropdown() {
    const kategori = $('#kategori').val();
    const $subkategori = $('#subkategori');
    $subkategori.empty();
    $subkategori.append('<option value="">Semua Sub Kategori</option>');
    if (kategori && subkategoriMap[kategori]) {
      subkategoriMap[kategori].forEach(function(sub) {
        $subkategori.append('<option value="' + sub.kode + '">' + sub.nama + '</option>');
      });
    }
  }

  function updateExportLinks() {
    const tahun = $('#tahun').val();
    const kategori = $('#kategori').val();
    const subkategori = $('#subkategori').val();

    // Build query parameters hanya jika ada nilai
    const params = new URLSearchParams();
    if (kategori) params.append('kategori', kategori);
    if (subkategori) params.append('subkategori', subkategori);
    
    const queryString = params.toString();
    
    // Build PDF URL
    let pdfUrl = '<?= base_url("laporan/pembelian/pdf") ?>';
    if (tahun) {
      pdfUrl += '/' + tahun;
    }
    if (queryString) {
      pdfUrl += '?' + queryString;
    }
    
    // Build Excel URL
    let excelUrl = '<?= base_url("laporan/pembelian/excel") ?>';
    if (tahun) {
      excelUrl += '/' + tahun;
    }
    if (queryString) {
      excelUrl += '?' + queryString;
    }
    
    // Update href attributes
    $('#exportPdf').attr('href', pdfUrl);
    $('#exportExcel').attr('href', excelUrl);
  }

  function updateFilterInfo() {
    const activeFilters = [];
    const tahun = $('#tahun option:selected').text();
    const kategori = $('#kategori option:selected').text();
    const subkategori = $('#subkategori option:selected').text();

    if ($('#tahun').val()) activeFilters.push('Tahun: ' + tahun);
    if ($('#kategori').val()) activeFilters.push('Kategori: ' + kategori);
    if ($('#subkategori').val()) activeFilters.push('Sub Kategori: ' + subkategori);

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
    
    if (table.search() || $('#tahun').val() || $('#kategori').val() || $('#subkategori').val()) {
      $('#filteredInfo').show();
    } else {
      $('#filteredInfo').hide();
    }
  }

  // Initialize semua fungsi
  updateSubkategoriDropdown();
  updateExportLinks();
  updateFilterInfo();
  updateFilteredCount();

  // Event handler untuk filter tahun
  $('#tahun').on('change', function () {
    const selectedValue = $(this).val();
    
    if (selectedValue) {
      // Filter berdasarkan tahun dari tanggal di kolom ke-4
      table.column(4).search(selectedValue).draw();
    } else {
      table.column(4).search('').draw();
    }
    updateExportLinks();
    updateFilterInfo();
    updateFilteredCount();
  });

  // Event handler untuk filter kategori
  $('#kategori').on('change', function () {
    updateSubkategoriDropdown();
    const selectedValue = $(this).val();
    const selectedText = $(this).find('option:selected').text();
    
    if (selectedValue) {
      // Filter berdasarkan nama kategori (yang ditampilkan di tabel)
      table.column(2).search(selectedText).draw();
    } else {
      table.column(2).search('').draw();
    }
    updateExportLinks();
    updateFilterInfo();
    updateFilteredCount();
  });

  // Event handler untuk filter sub kategori
  $('#subkategori').on('change', function () {
    const selectedValue = $(this).val();
    const selectedText = $(this).find('option:selected').text();
    
    if (selectedValue) {
      // Filter berdasarkan nama sub kategori (yang ditampilkan di tabel)
      table.column(3).search(selectedText).draw();
    } else {
      table.column(3).search('').draw();
    }
    updateExportLinks();
    updateFilterInfo();
    updateFilteredCount();
  });

  // Event handler untuk reset filters
  $('#resetFilters').on('click', function() {
    // Reset semua dropdown ke nilai kosong
    $('#tahun').val('');
    $('#kategori').val('');
    updateSubkategoriDropdown();
    $('#subkategori').val('');
    
    // Clear semua filter di DataTable
    table.columns().search('').draw();
    
    // Update semua fungsi
    updateExportLinks();
    updateFilterInfo();
    updateFilteredCount();
  });

  // Update filtered count setiap kali DataTable di-draw
  table.on('draw', function() {
    updateFilteredCount();
  });

  // Event handler untuk tombol export (optional - untuk validasi)
  $('#exportPdf, #exportExcel').on('click', function(e) {
    const href = $(this).attr('href');
    // Jika ingin menambahkan loading indicator
    const buttonText = $(this).html();
    $(this).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    setTimeout(() => {
      $(this).html(buttonText);
    }, 3000);
  });
});
</script>

<?= $this->endSection(); ?>