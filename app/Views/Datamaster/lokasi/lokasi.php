<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2 mb-3">
    <h3 class="pb-2 mb-0">Data Lokasi Aset</h3>
    <a href="<?= base_url('lokasi/create') ?>" class="btn btn-primary">Tambah Data</a>
  </div>

  <!-- Flash Message -->
  <?php if (session()->getFlashdata('message')) : ?>
    <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')) : ?>
    <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
  <?php endif; ?>

  <!-- Tabel Data -->
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="lokasiTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Kode Lokasi</th>
            <th>Nama Lokasi</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($lokasi as $lok) : ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= esc($lok['kode_lokasi']) ?></td>
              <td><?= esc($lok['nama_lokasi']) ?></td>
              <td><?= esc($lok['deskripsi_lokasi']) ?></td>
              <td>
                <a href="<?= base_url('/lokasi/edit/' . $lok['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                <form action="<?= base_url('/lokasi/delete/' . $lok['id']) ?>" method="post" class="d-inline form-delete">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-sm btn-danger btn-confirm-delete">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- SweetAlert2 & DataTable JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    // Inisialisasi DataTables
    $('#lokasiTable').DataTable();

    // Konfirmasi saat klik tombol hapus
    $('.btn-confirm-delete').on('click', function (e) {
      e.preventDefault();
      const form = $(this).closest('form');
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // SweetAlert flash message
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
      const message = flashMessage.getAttribute('data-message');
      const type = flashMessage.getAttribute('data-type');

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
  });
</script>

<?= $this->endSection(); ?>
