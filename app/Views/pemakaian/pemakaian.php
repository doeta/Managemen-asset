<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2">
    <h3 class="pb-2 mb-0">Data Pemakaian Barang</h3>
    <a href="<?= base_url('pemakaian/create') ?>" class="btn btn-primary"> + Tambah</a>
  </div>

  <div class="pt-3">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('message')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
    <?php endif; ?>

    <!-- Tabel -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="pemakaianTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Kategori</th>
            <th>Sub Kategori</th>
            <th>Lokasi</th>
            <th>Pengguna</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Tanggal pemakaian</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($pemakaian)) : ?>
            <?php $no = 1; foreach ($pemakaian as $p) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($p['nama_barang'] ?? '-') ?></td>
                <td><?= esc($p['kode_barang'] ?? '-') ?></td>
                <td><?= esc($p['kode_kategori'] ?? '-') ?> - <?= esc($p['nama_kategori'] ?? '-') ?></td>
                <td><?= esc($p['kode_sub_kategori'] ?? '-') ?> - <?= esc($p['nama_sub_kategori'] ?? '-') ?></td>
                <td><?= esc($p['nama_lokasi'] ?? '-') ?></td>
                <td><?= esc($p['nama_pengguna'] ?? '-') ?></td>
                <td><?= esc($p['jumlah_digunakan'] ?? '-') ?></td>
                <td><?= esc($p['satuan_penggunaan'] ?? '-') ?></td>
                <td><?= esc($p['tanggal_mulai'] ?? '-') ?></td>
                <td><?= esc($p['tanggal_selesai'] ?: '-') ?></td>
                <td>
                  <?php
                    $status = $p['status'] ?? '';
                    if ($status == 'tersedia') {
                      echo '<span class="badge bg-success">Tersedia</span>';
                    } elseif ($status == 'habis terpakai') {
                      echo '<span class="badge bg-danger">Habis Terpakai</span>';
                    } elseif ($status == 'terpakai') {
                      echo '<span class="badge bg-warning text-dark">Terpakai</span>';
                    } else {
                      echo '<span class="badge bg-secondary">Tidak Diketahui</span>';
                    }
                  ?>
                </td>
                <td><?= esc($p['keterangan'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="13" class="text-center">Tidak ada data pemakaian.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables CSS & JS (CDN) -->


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Flash Message & DataTables -->
<script>
  
  $(document).ready(function () {
    $('#pemakaianTable').DataTable();

    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
      const message = flashMessage.getAttribute('data-message');
      const type = flashMessage.getAttribute('data-type');

      if (message) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: type,
          title: message,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  });
  
    <?php /*$('#pemakaianTable').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});*/ ?>
</script>

<?= $this->endSection(); ?>
