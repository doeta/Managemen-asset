<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>


<div class="my-3 p-3 bg-body rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Detail Barang</h3>
    <a href="<?= base_url('/dataassets/detailSubKategori/' . $barang['kode_sub_kategori']) ?>" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Informasi Barang -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Informasi Barang</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Kode Barang:</strong></td>
              <td><?= esc($barang['kode_barang']) ?></td>
            </tr>
            <tr>
              <td><strong>Nama Barang:</strong></td>
              <td><?= esc($barang['nama_barang']) ?></td>
            </tr>
            <tr>
              <td><strong>Kategori:</strong></td>
              <td><?= esc($barang['kode_kategori']) ?></td>
            </tr>
            <tr>
              <td><strong>Sub Kategori:</strong></td>
              <td><?= esc($barang['kode_sub_kategori']) ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Status:</strong></td>
              <td>
                <?php 
                $statusClass = 'bg-secondary';
                switch(strtolower($barang['status'])) {
                  case 'tersedia':
                    $statusClass = 'bg-success';
                    break;
                  case 'terpakai':
                    $statusClass = 'bg-primary';
                    break;
                  case 'rusak':
                    $statusClass = 'bg-danger';
                    break;
                  case 'maintenance':
                    $statusClass = 'bg-warning';
                    break;
                  case 'habis terpakai':
                    $statusClass = 'bg-dark';
                    break;
                }
                ?>
                <span class="badge <?= $statusClass ?>"><?= ucfirst($barang['status']) ?></span>
              </td>
            </tr>
            <tr>
              <td><strong>Pengguna:</strong></td>
              <td><?= esc($barang['nama_pengguna'] ?? '-') ?></td>
            </tr>
            <tr>
              <td><strong>Lokasi:</strong></td>
              <td><?= esc($barang['nama_lokasi'] ?? '-') ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>



  <!-- Riwayat Kepemilikan -->
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-user-friends"></i> Riwayat Kepemilikan</h5>
      <small class="text-muted">Barang: <?= esc($barang['nama_barang']) ?> - <?= esc($barang['kode_unik'] ?? $barang['id']) ?></small>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="riwayatKepemilikanTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Tanggal Mulai</th>
              <th>Pemilik</th>
              <th>Keterangan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($riwayatPengguna) && is_array($riwayatPengguna)) : ?>
              <?php $no_kepemilikan = 1; foreach ($riwayatPengguna as $riwayat) : ?>
                <tr>
                  <td><?= $no_kepemilikan ?></td>
                  <td><?= isset($riwayat['tanggal_mulai']) ? date('d-m-Y H:i', strtotime($riwayat['tanggal_mulai'])) : '-' ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <i class="fas fa-user text-primary me-2"></i>
                      <strong><?= esc($riwayat['nama_pengguna'] ?? 'Tidak diketahui') ?></strong>
                    </div>
                  </td>
                  <td><?= esc($riwayat['keterangan'] ?? 'Perubahan kepemilikan barang') ?></td>
                  <td>
                    <?php if ($no_kepemilikan == 1): ?>
                      <span class="badge bg-success"><i class="fas fa-check"></i> Terbaru</span>
                    <?php else: ?>
                      <span class="badge bg-secondary"><i class="fas fa-history"></i> Riwayat</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php $no_kepemilikan++; endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="5" class="text-center text-muted">
                  <i class="fas fa-info-circle"></i> Belum ada riwayat perubahan kepemilikan untuk barang ini
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


</div>

<script>
$(document).ready(function() {
  // Initialize DataTables untuk Riwayat Kepemilikan
  $('#riwayatKepemilikanTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    },
    order: [[1, 'desc']]
  });
});
</script>

<?= $this->endSection(); ?>