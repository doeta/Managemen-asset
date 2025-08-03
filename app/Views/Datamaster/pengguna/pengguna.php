<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2">
    <h3 class="pb-2 mb-0">Data Pengguna Aset</h3>
    <a href="<?= base_url('pengguna/create') ?>" class="btn btn-primary">Tambah Data</a>
  </div>

  <div class="pt-3">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('message')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
    <?php endif; ?>

    <!-- Tabel Pengguna -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="penggunaTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Pengguna</th>
            <th>NIP</th>
            <th>No Telepon</th>
            <th>Unit Kerja</th>
            <th>Alamat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($pengguna)) : ?>
            <?php $no = 1; foreach ($pengguna as $peng) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($peng['nama_pengguna']) ?></td>
                <td><?= esc($peng['nip']) ?></td>
                <td><?= esc($peng['no_hp']) ?></td>
                <td><?= esc($peng['nama_lokasi']) ?></td>
                <td><?= esc($peng['alamat']) ?></td>
                <td>
                  <a href="<?= base_url('/pengguna/edit/' . $peng['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <form action="<?= base_url('/pengguna/delete/' . $peng['id']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="7" class="text-center">Tidak ada data pengguna.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Konfirmasi hapus dengan SweetAlert2
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
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
  });

  // Flash message
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

  // DataTables inisialisasi
  $(document).ready(function() {
    $('#penggunaTable').DataTable();
  });
</script>

<?= $this->endSection(); ?>
