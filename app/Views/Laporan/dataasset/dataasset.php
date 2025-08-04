<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <h3 class="pb-2 mb-3">Laporan Data Asset</h3>

  <!-- Filter Section -->
  <div class="row mb-4">
    <div class="col-md-3 mb-2">
      <label for="filterKategori" class="form-label">Filter Kategori</label>
      <select id="filterKategori" class="form-control">
        <option value="">Semua Kategori</option>
        <?php if (!empty($kategori)) : ?>
          <?php foreach ($kategori as $kat) : ?>
            <option value="<?= esc($kat['nama_kategori']) ?>">
              <?= esc($kat['nama_kategori']) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterSubKategori" class="form-label">Filter Sub Kategori</label>
      <select id="filterSubKategori" class="form-control">
        <option value="">Semua Sub Kategori</option>
        <?php if (!empty($sub_kategori)) : ?>
          <?php foreach ($sub_kategori as $sub) : ?>
            <option value="<?= esc($sub['nama_sub_kategori']) ?>" data-kategori="<?= esc($sub['nama_kategori']) ?>">
              <?= esc($sub['nama_sub_kategori']) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterNamaBarang" class="form-label">Filter Nama Barang</label>
      <select id="filterNamaBarang" class="form-control">
        <option value="">Semua Barang</option>
        <?php if (!empty($nama_barang_list)) : ?>
          <?php foreach ($nama_barang_list as $item) : ?>
            <?php if (!empty($item['nama_barang'])) : ?>
              <option value="<?= esc($item['nama_barang']) ?>"><?= esc($item['nama_barang']) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterTahun" class="form-label">Filter Tahun</label>
      <select id="filterTahun" class="form-control">
        <option value="">Semua Tahun</option>
        <?php 
        // Ambil tahun dari data barang yang sudah ada
        $tahunList = [];
        if (!empty($barang)) {
          foreach ($barang as $item) {
            if (isset($item['tahun_perolehan']) && !empty($item['tahun_perolehan'])) {
              $tahunList[] = $item['tahun_perolehan'];
            }
          }
          $tahunList = array_unique($tahunList);
          rsort($tahunList);
        }
        ?>
        <?php foreach ($tahunList as $tahun) : ?>
          <option value="<?= esc($tahun) ?>"><?= esc($tahun) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Action Buttons -->
    <div class="col-md-6">
      <div class="d-flex flex-wrap gap-3"> <!-- tambahkan flex-wrap juga kalau ingin responsif -->
        <button type="button" id="exportPDF" class="btn btn-danger">
          <i class="fas fa-file-pdf"></i> Export PDF
        </button>
        <button type="button" id="exportExcel" class="btn btn-success">
          <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button type="button" id="resetFilters" class="btn btn-secondary">
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
        <strong>Total Data Tersedia: </strong><span id="totalData"><?= count($barang ?? []) ?></span> item
        <span id="filteredInfo" style="display: none;"> | <strong>Data Setelah Filter: </strong><span id="filteredCount">0</span> item</span>
      </div>
    </div>
  </div>

  <!-- Preview Data -->
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Preview Data Asset</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm" id="previewTable">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Kode Unik</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Sub Kategori</th>
                  <th>Harga</th>
                  <th>Status</th>
                  <th>Pengguna</th>
                  <th>Lokasi</th>
                </tr>
              </thead>
              <tbody id="previewTableBody">
                <?php if (!empty($barang)) : ?>
                  <?php foreach ($barang as $i => $item) : ?>
                    <tr>
                      <td><?= $i + 1 ?></td>
                      <td><?= esc($item['kode_unik'] ?? '-') ?></td>
                      <td><?= esc($item['nama_barang'] ?? '-') ?></td>
                      <td><?= esc($item['nama_kategori'] ?? '-') ?></td>
                      <td><?= esc($item['nama_sub_kategori'] ?? '-') ?></td>
                      <td><?= !empty($item['harga_barang']) ? 'Rp ' . number_format($item['harga_barang'], 0, ',', '.') : '-' ?></td>
                      <td><span class="badge bg-<?= ($item['status'] == 'aktif') ? 'success' : (($item['status'] == 'rusak') ? 'danger' : 'warning') ?>"><?= ucfirst($item['status'] ?? '-') ?></span></td>
                      <td><?= esc($item['nama_pengguna'] ?? '-') ?></td>
                      <td><?= esc($item['nama_lokasi'] ?? '-') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="9" class="text-center">Tidak ada data asset yang tersedia.</td>
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

<script>
$(document).ready(function() {
  var dataTable = $('#previewTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    }
  });

  // Function untuk update sub kategori berdasarkan kategori
  function updateSubKategori() {
    var selectedKategori = $('#filterKategori').val();
    var subKategoriSelect = $('#filterSubKategori');
    
    if (selectedKategori === '') {
      // Reset ke semua sub kategori
      subKategoriSelect.html('<option value="">Semua Sub Kategori</option>');
      <?php if (!empty($sub_kategori)) : ?>
        <?php foreach ($sub_kategori as $sub) : ?>
          subKategoriSelect.append('<option value="<?= esc($sub['nama_sub_kategori']) ?>" data-kategori="<?= esc($sub['nama_kategori']) ?>"><?= esc($sub['nama_sub_kategori']) ?></option>');
        <?php endforeach; ?>
      <?php endif; ?>
    } else {
      // AJAX request untuk mendapatkan sub kategori berdasarkan kategori
      $.get('<?= base_url('laporan/asset/sub-kategori') ?>', { kategori: selectedKategori })
        .done(function(data) {
          subKategoriSelect.html('<option value="">Semua Sub Kategori</option>');
          $.each(data, function(index, item) {
            subKategoriSelect.append('<option value="' + item.nama_sub_kategori + '">' + item.nama_sub_kategori + '</option>');
          });
        })
        .fail(function() {
          console.log('Error loading sub kategori');
        });
    }
    subKategoriSelect.val('');
    updateNamaBarang();
    updatePreviewTable();
  }

  // Function untuk update nama barang berdasarkan sub kategori
  function updateNamaBarang() {
    var selectedSubKategori = $('#filterSubKategori').val();
    var namaBarangSelect = $('#filterNamaBarang');
    
    if (selectedSubKategori === '') {
      // Reset ke semua nama barang
      namaBarangSelect.html('<option value="">Semua Barang</option>');
      <?php if (!empty($nama_barang_list)) : ?>
        <?php foreach ($nama_barang_list as $item) : ?>
          <?php if (!empty($item['nama_barang'])) : ?>
            namaBarangSelect.append('<option value="<?= esc($item['nama_barang']) ?>"><?= esc($item['nama_barang']) ?></option>');
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    } else {
      // AJAX request untuk mendapatkan nama barang berdasarkan sub kategori
      $.get('<?= base_url('laporan/asset/nama-barang') ?>', { sub_kategori: selectedSubKategori })
        .done(function(data) {
          namaBarangSelect.html('<option value="">Semua Barang</option>');
          $.each(data, function(index, item) {
            namaBarangSelect.append('<option value="' + item.nama_barang + '">' + item.nama_barang + '</option>');
          });
        })
        .fail(function() {
          console.log('Error loading nama barang');
        });
    }
    namaBarangSelect.val('');
    updatePreviewTable();
  }

  // Function untuk update preview table berdasarkan filter
  function updatePreviewTable() {
    var kategori = $('#filterKategori').val() || '';
    var subKategori = $('#filterSubKategori').val() || '';
    var namaBarang = $('#filterNamaBarang').val() || '';
    var tahun = $('#filterTahun').val() || '';

    // AJAX request untuk mendapatkan data berdasarkan filter
    $.get('<?= base_url('laporan/asset/data-filter') ?>', {
      kategori: kategori,
      sub_kategori: subKategori,
      nama_barang: namaBarang,
      tahun: tahun
    })
    .done(function(response) {
      // Clear existing data
      dataTable.clear();
      
      // Add new data
      $.each(response.data, function(index, item) {
        var statusClass = '';
        var status = (item.status || '').toLowerCase();
        if (status === 'aktif') {
          statusClass = 'success';
        } else if (status === 'rusak') {
          statusClass = 'danger';
        } else {
          statusClass = 'warning';
        }

        var harga = item.harga_barang ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga_barang) : '-';
        var tanggal = item.tanggal_masuk ? new Date(item.tanggal_masuk).toLocaleDateString('id-ID') : '-';

        dataTable.row.add([
          index + 1,
          item.kode_unik || '-',
          item.nama_barang || '-',
          item.nama_kategori || '-',
          item.nama_sub_kategori || '-',
          harga,
          '<span class="badge bg-' + statusClass + '">' + (item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : '-') + '</span>',
          item.nama_pengguna || '-',
          item.nama_lokasi || '-'
        ]);
      });
      
      // Redraw table
      dataTable.draw();
      
      // Update counters
      $('#filteredCount').text(response.total);
      $('#totalData').text(response.total);
      
      // Show/hide filtered info
      if (kategori || subKategori || namaBarang || tahun) {
        $('#filteredInfo').show();
      } else {
        $('#filteredInfo').hide();
      }
    })
    .fail(function() {
      console.log('Error loading filtered data');
    });
  }

  // Function untuk update info filter aktif
  function updateFilterInfo() {
    var activeFilters = [];
    var kategori = $('#filterKategori').val();
    var subKategori = $('#filterSubKategori').val();
    var namaBarang = $('#filterNamaBarang').val();
    var tahun = $('#filterTahun').val();

    if (kategori) activeFilters.push('Kategori: ' + kategori);
    if (subKategori) activeFilters.push('Sub Kategori: ' + subKategori);
    if (namaBarang) activeFilters.push('Nama Barang: ' + namaBarang);
    if (tahun) activeFilters.push('Tahun: ' + tahun);

    if (activeFilters.length > 0) {
      $('#activeFilters').text(activeFilters.join(', '));
      $('#filterInfo').show();
    } else {
      $('#filterInfo').hide();
    }
  }

  // Function untuk generate export URL
  function generateExportUrl(format) {
    var kategori = $('#filterKategori').val() || '';
    var subKategori = $('#filterSubKategori').val() || '';
    var namaBarang = $('#filterNamaBarang').val() || '';
    var tahun = $('#filterTahun').val() || '';

    var params = new URLSearchParams();
    if (kategori) params.append('kategori', kategori);
    if (subKategori) params.append('sub_kategori', subKategori);
    if (namaBarang) params.append('nama_barang', namaBarang);
    if (tahun) params.append('tahun', tahun);

    return `<?= base_url('laporan/asset/') ?>${format}?${params.toString()}`;
  }

  // Event handlers untuk filter
  $('#filterKategori').on('change', function() {
    updateSubKategori();
    updateFilterInfo();
  });

  $('#filterSubKategori').on('change', function() {
    updateNamaBarang();
    updateFilterInfo();
  });

  $('#filterNamaBarang, #filterTahun').on('change', function() {
    updatePreviewTable();
    updateFilterInfo();
  });

  // Reset filters
  $('#resetFilters').on('click', function() {
    $('#filterKategori').val('');
    $('#filterSubKategori').html('<option value="">Semua Sub Kategori</option>');
    <?php if (!empty($sub_kategori)) : ?>
      <?php foreach ($sub_kategori as $sub) : ?>
        $('#filterSubKategori').append('<option value="<?= esc($sub['nama_sub_kategori']) ?>" data-kategori="<?= esc($sub['nama_kategori']) ?>"><?= esc($sub['nama_sub_kategori']) ?></option>');
      <?php endforeach; ?>
    <?php endif; ?>
    $('#filterNamaBarang').html('<option value="">Semua Barang</option>');
    <?php if (!empty($nama_barang_list)) : ?>
      <?php foreach ($nama_barang_list as $item) : ?>
        <?php if (!empty($item['nama_barang'])) : ?>
          $('#filterNamaBarang').append('<option value="<?= esc($item['nama_barang']) ?>"><?= esc($item['nama_barang']) ?></option>');
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
    $('#filterTahun').val('');
    updatePreviewTable();
    updateFilterInfo();
  });

  // Export handlers
  $('#exportPDF').on('click', function() {
    var url = generateExportUrl('pdf');
    window.open(url, '_blank');
  });

  $('#exportExcel').on('click', function() {
    var url = generateExportUrl('excel');
    window.open(url, '_blank');
  });

  // Initialize
  updateFilterInfo();
});
</script>

<?= $this->endSection(); ?>