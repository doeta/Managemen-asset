<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>
<?php $request = \Config\Services::request(); ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2 mb-3">
    <h3 class="pb-2 mb-0">
      <?php if (isset($sub_kategori)) : ?>
        Detail Asset - <?= esc($sub_kategori['nama_sub_kategori']) ?>
      <?php else : ?>
        Detail Semua Barang Asset
      <?php endif; ?>
    </h3>
    <a href="<?= base_url('/dataassets') ?>" class="btn btn-primary">Kembali</a>
  </div>


<!-- Form Filter -->
  <form method="get" action="">
    <div class="row">
      <div class="col-md-3 mb-2">
        <label for="filterNamaBarang" class="form-label">Filter Nama Barang</label>
        <select id="filterNamaBarang" name="nama_barang" class="form-control" onchange="this.form.submit()">
          <option value="">Semua Barang</option>
          <?php if (!empty($nama_barang_list)) : ?>
            <?php foreach ($nama_barang_list as $item) : ?>
              <?php if (!empty($item['nama_barang'])) : ?>
                <option value="<?= esc($item['nama_barang']) ?>" <?= ($request->getGet('nama_barang') == $item['nama_barang']) ? 'selected' : '' ?>>
                  <?= esc($item['nama_barang']) ?>
                </option>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
    </div>
  </form>
  <?php if (isset($sub_kategori)) : ?>
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="alert alert-info bg-light border rounded-3 shadow-sm">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi</h5>
        <div class="row align-items-start">
          
          <!-- Kolom Kiri -->
          <div class="col-md-6">
            <p class="mb-1"><strong>Nama Barang:</strong>
              <?= isset($barangSample) ? esc($barangSample['nama_barang']) : 'Semua Barang' ?>
            </p>
            <p class="mb-1"><strong>Kategori:</strong> <?= esc($sub_kategori['nama_kategori'] ?? '-') ?></p>
            <p class="mb-0"><strong>Sub Kategori:</strong> <?= esc($sub_kategori['nama_sub_kategori'] ?? '-') ?></p>
          </div>

          <!-- Kolom Kanan -->
          <div class="col-md-6">
            <?php if (!empty($statistikStatus)) : ?>
              <p class="mb-1"><strong>Statistik Status:</strong></p>
              <ul class="mb-0 ps-3">
                <?php foreach ($statistikStatus as $row) : ?>
                  <li><?= ucfirst($row['status']) ?>: <?= $row['jumlah'] ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else : ?>
              <p class="text-muted mb-0">Tidak ada statistik status tersedia.</p>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>






  <!-- Tabel Data -->
  <!-- Riwayat Pengguna -->
  <?php if (!empty($riwayat_pengguna)) : ?>
  <div class="card mb-4">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0">Riwayat Pengguna Barang</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Pengguna</th>
              <th>Jumlah Digunakan</th>
              <th>Satuan</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($riwayat_pengguna as $i => $r) : ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($r['nama_pengguna'] ?? '-') ?></td>
                <td><?= esc($r['jumlah_digunakan'] ?? '-') ?></td>
                <td><?= esc($r['satuan_penggunaan'] ?? '-') ?></td>
                <td><?= esc($r['tanggal_mulai'] ?? '-') ?></td>
                <td><?= esc($r['tanggal_selesai'] ?? '-') ?></td>
                <td><?= esc($r['keterangan'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="barangTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Kode Unik</th>
            <th>Harga</th>
            <th>Tanggal Masuk</th>
            <th>Status</th>
            <th>Nama Pengguna</th>
            <th>Lokasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($barang)) : ?>
            <?php $no = 1; foreach ($barang as $asset) : ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($asset['nama_barang']) ?></td>
                <td><?= esc($asset['kode_barang'] ?? '-') ?></td>
                <td><?= esc($asset['kode_unik'] ?? '-') ?></td>
                <td><?= number_format($asset['harga_barang'] ?? 0, 2, ',', '.') ?></td>
                <td><?= esc($asset['tanggal_masuk'] ?? '-') ?></td>
                <td>
                  <?php
                  $statusClass = '';
                  $statusText = $asset['status'] ?? '-';
                  
                  switch (strtolower($statusText)) {
                    case 'tersedia':
                      $statusClass = 'bg-success';
                      break;
                    case 'dipakai':
                      $statusClass = 'bg-warning';
                      break;
                    case 'habis_terpakai':
                      $statusClass = 'bg-danger';
                      $statusText = 'Habis Terpakai';
                      break;
                    case 'rusak':
                      $statusClass = 'bg-secondary';
                      break;
                    default:
                      $statusClass = 'bg-secondary';
                  }
                  ?>
                  <span class="badge <?= $statusClass ?>">
                    <?= esc($statusText) ?>
                  </span>
                </td>
                <td><?= esc($asset['nama_pengguna'] ?? '-') ?></td>
                <td><?= esc($asset['nama_lokasi'] ?? '-') ?></td>
                <td>
                  <?php 
                  // Cek apakah barang habis terpakai atau tidak bisa diedit
                  $isHabisTerpakai = (isset($asset['status']) && 
                                     in_array(strtolower(trim($asset['status'])), ['habis terpakai', 'habis_terpakai']));
                  ?>
                  
                  <?php if ($isHabisTerpakai): ?>
                    <!-- Barang habis terpakai - hanya bisa lihat detail dan hapus -->
                    <div class="btn-group-sm d-flex flex-wrap gap-1">
                      <a href="<?= base_url('/dataassets/detail/' . $asset['id']) ?>" 
                         class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                      
                      <form action="<?= base_url('/dataassets/delete/' . $asset['id']) ?>" 
                            method="post" class="d-inline form-delete">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete" 
                                title="Hapus Data">
                          <i class="fas fa-trash"></i> Hapus
                        </button>
                      </form>
                    </div>
                    
                    <small class="text-danger d-block mt-1">
                      <i class="fas fa-lock"></i> Tidak dapat diedit
                    </small>
                    
                  <?php else: ?>
                    <!-- Barang normal - bisa edit -->
                    <div class="btn-group-sm d-flex flex-wrap gap-1">
                      <a href="<?= base_url('/dataassets/edit/' . $asset['id']) ?>" 
                         class="btn btn-sm btn-warning" title="Edit Data">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a href="<?= base_url('/dataassets/detail/' . $asset['id']) ?>" 
                         class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                      <form action="<?= base_url('/dataassets/delete/' . $asset['id']) ?>" 
                            method="post" class="d-inline form-delete">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete" 
                                title="Hapus Data">
                          <i class="fas fa-trash"></i> Hapus
                        </button>
                      </form>
                    </div>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="10" class="text-center">Tidak ada data barang asset.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables + Export Buttons -->
<script>
$(document).ready(function() {
    // Konfirmasi Hapus
    $('.btn-confirm-delete').on('click', function (e) {
      if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        e.preventDefault();
      }
    });
});

  function updateFilterInfo() {
    var activeFilters = [];
    var namaBarang = $('#filterNamaBarang').val();
    if (namaBarang) activeFilters.push('Nama Barang: ' + namaBarang);
    if (activeFilters.length > 0) {
      $('#activeFilters').text(activeFilters.join(', '));
      $('#filterInfo').show();
    } else {
      $('#filterInfo').hide();
    }
  }
</script>

<!-- Custom CSS untuk styling tambahan -->
<style>
.badge.bg-danger {
    background-color: #dc3545 !important;
}
.text-muted {
    font-size: 0.75rem;
}
</style>

<?= $this->endSection(); ?>