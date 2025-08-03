<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <h3 class="pb-2 mb-3">Laporan Barang Aset</h3>

  <!-- Filter Section -->
  <div class="row mb-4">
    <div class="col-md-3 mb-2">
      <label for="filterJenis" class="form-label">Filter Jenis Barang</label>
      <select id="filterJenis" class="form-select">
        <option value="">Semua Jenis</option>
        <option value="Modal">Barang Modal</option>
        <option value="Habis Pakai">Barang Habis Pakai</option>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterKategori" class="form-label">Filter Kategori</label>
      <select id="filterKategori" class="form-select">
        <option value="">Semua Kategori</option>
        <?php if (!empty($barang)) : ?>
          <?php 
          $kategori_list = array_unique(array_column($barang, 'nama_kategori'));
          sort($kategori_list);
          foreach ($kategori_list as $kategori) : ?>
            <option value="<?= esc($kategori) ?>"><?= esc($kategori) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterSubKategori" class="form-label">Filter Sub Kategori</label>
      <select id="filterSubKategori" class="form-select">
        <option value="">Semua Sub Kategori</option>
        <?php if (!empty($barang)) : ?>
          <?php 
          $subkategori_list = array_unique(array_column($barang, 'nama_sub_kategori'));
          sort($subkategori_list);
          foreach ($subkategori_list as $subkategori) : ?>
            <option value="<?= esc($subkategori) ?>"><?= esc($subkategori) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    
    <div class="col-md-3 mb-2">
      <label for="filterTahun" class="form-label">Filter Tahun</label>
      <select id="filterTahun" class="form-select">
        <option value="">Semua Tahun</option>
        <?php if (!empty($barang)) : ?>
          <?php 
          // Asumsi ada field tahun dalam data, jika tidak ada bisa disesuaikan
          $tahun_list = [];
          foreach ($barang as $item) {
            if (isset($item['tahun_perolehan'])) {
              $tahun_list[] = $item['tahun_perolehan'];
            }
          }
          $tahun_list = array_unique($tahun_list);
          rsort($tahun_list); // urutkan descending
          foreach ($tahun_list as $tahun) : ?>
            <option value="<?= esc($tahun) ?>"><?= esc($tahun) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
  </div>

  <!-- Search Box untuk Nama Barang -->
  <div class="row mb-3">
    <div class="col-md-6">
      <label for="searchNamaBarang" class="form-label">Cari Nama Barang</label>
      <input type="text" id="searchNamaBarang" class="form-control" placeholder="Ketik nama barang untuk mencari...">
    </div>
    <div class="col-md-6 d-flex align-items-end">
      <button type="button" id="resetFilters" class="btn btn-secondary">
        <i class="fas fa-refresh"></i> Reset Filter
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="gabunganTable">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Sub Kategori</th>
          <th>Nama Sub Kategori</th>
          <th>Kategori</th>
          <th>Nama Barang</th>
          <th>Jumlah Barang</th>
          <th>Jenis</th>
          <th>Tahun Perolehan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($barang)) : ?>
          <?php foreach ($barang as $i => $item) : ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($item['kode_sub_kategori']) ?></td>
              <td><?= esc($item['nama_sub_kategori']) ?></td>
              <td><?= esc($item['nama_kategori']) ?></td>
              <td><?= esc($item['nama_barang'] ?? '-') ?></td>
              <td><?= esc($item['jumlah_barang']) ?></td>
              <td><?= esc($item['jenis_barang']) ?></td>
              <td><?= esc($item['tahun_perolehan'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="8" class="text-center">Tidak ada data barang.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Info Total -->
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="alert alert-info">
        <strong>Total Data Ditampilkan: </strong><span id="totalData"><?= count($barang) ?></span> item
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('#gabunganTable').DataTable({
      "pageLength": 25,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "language": {
        "search": "Pencarian:",
        "lengthMenu": "Tampilkan _MENU_ data per halaman",
        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
        "infoFiltered": "(difilter dari _MAX_ total data)",
        "paginate": {
          "first": "Pertama",
          "last": "Terakhir",
          "next": "Selanjutnya",
          "previous": "Sebelumnya"
        },
        "emptyTable": "Tidak ada data yang tersedia",
        "zeroRecords": "Tidak ditemukan data yang sesuai"
      },
      "drawCallback": function() {
        updateTotalData();
      }
    });

    // Filter Jenis Barang
    $('#filterJenis').on('change', function () {
      var val = $(this).val();
      table.column(6).search(val).draw(); // kolom ke-6 adalah 'Jenis'
    });

    // Filter Kategori
    $('#filterKategori').on('change', function () {
      var val = $(this).val();
      table.column(3).search(val).draw(); // kolom ke-3 adalah 'Kategori'
      
      // Update sub kategori options berdasarkan kategori yang dipilih
      updateSubKategoriOptions(val);
    });

    // Filter Sub Kategori
    $('#filterSubKategori').on('change', function () {
      var val = $(this).val();
      table.column(2).search(val).draw(); // kolom ke-2 adalah 'Nama Sub Kategori'
    });

    // Filter Tahun
    $('#filterTahun').on('change', function () {
      var val = $(this).val();
      table.column(7).search(val).draw(); // kolom ke-7 adalah 'Tahun Perolehan'
    });

    // Search Nama Barang
    $('#searchNamaBarang').on('keyup', function () {
      var val = $(this).val();
      table.column(4).search(val).draw(); // kolom ke-4 adalah 'Nama Barang'
    });

    // Reset Filters
    $('#resetFilters').on('click', function() {
      // Reset semua select dan input
      $('#filterJenis').val('');
      $('#filterKategori').val('');
      $('#filterSubKategori').val('');
      $('#filterTahun').val('');
      $('#searchNamaBarang').val('');
      
      // Reset semua filter DataTable
      table.columns().search('').draw();
      
      // Reset sub kategori options
      updateSubKategoriOptions('');
    });

    // Function untuk update sub kategori berdasarkan kategori
    function updateSubKategoriOptions(selectedKategori) {
      var subKategoriSelect = $('#filterSubKategori');
      var originalOptions = subKategoriSelect.data('original-options');
      
      // Simpan original options jika belum ada
      if (!originalOptions) {
        originalOptions = subKategoriSelect.html();
        subKategoriSelect.data('original-options', originalOptions);
      }
      
      if (selectedKategori === '') {
        // Tampilkan semua sub kategori
        subKategoriSelect.html(originalOptions);
      } else {
        // Filter sub kategori berdasarkan kategori
        subKategoriSelect.html('<option value="">Semua Sub Kategori</option>');
        
        // Loop through table data untuk mendapatkan sub kategori yang sesuai
        table.rows().every(function() {
          var data = this.data();
          if (data[3] === selectedKategori) { // kolom kategori
            var subKategori = data[2]; // kolom sub kategori
            var exists = false;
            
            // Cek apakah option sudah ada
            subKategoriSelect.find('option').each(function() {
              if ($(this).val() === subKategori) {
                exists = true;
                return false;
              }
            });
            
            if (!exists && subKategori !== '') {
              subKategoriSelect.append('<option value="' + subKategori + '">' + subKategori + '</option>');
            }
          }
        });
      }
      
      // Reset sub kategori filter
      subKategoriSelect.val('');
      table.column(2).search('').draw();
    }

    // Function untuk update total data
    function updateTotalData() {
      var info = table.page.info();
      $('#totalData').text(info.recordsDisplay);
    }
  });
</script>

<?= $this->endSection(); ?>