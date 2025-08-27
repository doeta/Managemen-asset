<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Riwayat Jumlah Barang</h3>
    <a href="<?= base_url('/dataassets/detail/' . $asset['kode_sub_kategori']) ?>" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Informasi Asset -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Informasi Asset</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Kode Barang:</strong></td>
              <td><?= esc($asset['kode_barang']) ?></td>
            </tr>
            <tr>
              <td><strong>Nama Barang:</strong></td>
              <td><?= esc($asset['nama_barang']) ?></td>
            </tr>
            <tr>
              <td><strong>Kategori:</strong></td>
              <td><?= esc($asset['kode_kategori']) ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <td width="150"><strong>Sub Kategori:</strong></td>
              <td><?= esc($asset['kode_sub_kategori']) ?></td>
            </tr>
            <tr>
              <td><strong>Jumlah Saat Ini:</strong></td>
              <td><span class="badge bg-primary fs-6"><?= $asset['jumlah_barang'] ?> unit</span></td>
            </tr>
            <tr>
              <td><strong>Jenis Barang:</strong></td>
              <td>
                <?php 
                $isHabisTerpakai = in_array($asset['kode_kategori'], ['barang_habis_pakai', 'kertas', 'tinta', 'alat_tulis', 'konsumsi']);
                $jenisBarang = $isHabisTerpakai ? 'Habis Terpakai' : 'Dapat Diganti Kepemilikan';
                $badgeClass = $isHabisTerpakai ? 'bg-danger' : 'bg-success';
                ?>
                <span class="badge <?= $badgeClass ?>"><?= $jenisBarang ?></span>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Status -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Statistik Status Barang</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <?php foreach ($statistikStatus as $stat) : ?>
              <div class="col-md-2 mb-2">
                <div class="text-center">
                  <?php 
                  $badgeClass = 'bg-secondary';
                  switch($stat['status']) {
                    case 'tersedia':
                      $badgeClass = 'bg-success';
                      break;
                    case 'terpakai':
                      $badgeClass = 'bg-primary';
                      break;
                    case 'rusak':
                      $badgeClass = 'bg-danger';
                      break;
                    case 'maintenance':
                      $badgeClass = 'bg-warning';
                      break;
                    case 'habis terpakai':
                      $badgeClass = 'bg-dark';
                      break;
                  }
                  ?>
                  <div class="badge <?= $badgeClass ?> fs-5 mb-1"><?= $stat['jumlah'] ?></div>
                  <div class="small text-muted"><?= ucfirst($stat['status']) ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Riwayat Perubahan Jumlah -->
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Riwayat Perubahan Jumlah</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="riwayatTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Jenis</th>
              <th>Jumlah Sebelum</th>
              <th>Jumlah Digunakan</th>
              <th>Jumlah Sesudah</th>
              <th>Pengguna</th>
              <th>Lokasi</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($riwayatJumlah)) : ?>
              <?php foreach ($riwayatJumlah as $i => $riwayat) : ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= date('d-m-Y', strtotime($riwayat['tanggal'])) ?></td>
                  <td>
                    <span class="badge bg-info"><?= $riwayat['jenis'] ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-secondary"><?= $riwayat['jumlah_sebelum'] ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-warning">-<?= $riwayat['jumlah_digunakan'] ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-primary"><?= $riwayat['jumlah_sesudah'] ?></span>
                  </td>
                  <td><?= esc($riwayat['pengguna'] ?? '-') ?></td>
                  <td><?= esc($riwayat['lokasi'] ?? '-') ?></td>
                  <td><?= esc($riwayat['keterangan'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="9" class="text-center">Belum ada riwayat perubahan jumlah.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Riwayat Pemakaian Detail -->
  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-0">Riwayat Pemakaian Detail</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="pemakaianTable">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Pengguna</th>
              <th>Lokasi</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($riwayatPemakaian)) : ?>
              <?php foreach ($riwayatPemakaian as $i => $pemakaian) : ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= date('d-m-Y', strtotime($pemakaian['tanggal_mulai'])) ?></td>
                  <td><?= $pemakaian['tanggal_selesai'] ? date('d-m-Y', strtotime($pemakaian['tanggal_selesai'])) : '-' ?></td>
                  <td class="text-center">
                    <span class="badge bg-info"><?= $pemakaian['jumlah_digunakan'] ?> <?= $pemakaian['satuan_penggunaan'] ?></span>
                  </td>
                  <td>
                    <?php 
                    $statusClass = 'bg-secondary';
                    switch($pemakaian['status']) {
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
                    <span class="badge <?= $statusClass ?>"><?= ucfirst($pemakaian['status']) ?></span>
                  </td>
                  <td><?= esc($pemakaian['nama_pengguna'] ?? '-') ?></td>
                  <td><?= esc($pemakaian['nama_lokasi'] ?? '-') ?></td>
                  <td><?= esc($pemakaian['keterangan'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="8" class="text-center">Belum ada riwayat pemakaian.</td>
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
  // Initialize DataTables
  $('#riwayatTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    },
    order: [[1, 'desc']] // Sort by date descending
  });

  $('#pemakaianTable').DataTable({
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
    },
    order: [[1, 'desc']] // Sort by date descending
  });
});
</script>

<?= $this->endSection(); ?> 