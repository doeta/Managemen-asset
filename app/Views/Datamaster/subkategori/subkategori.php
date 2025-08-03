<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <div class="d-flex justify-content-between border-bottom py-2">
        <h3 class="pb-2 mb-0">Data Sub Kategori Asset</h3>
        <a href="<?= base_url('subkategori/create') ?>" class="btn btn-primary">Tambah Data</a>
    </div>
  
    <div class="pt-3">

        <!-- Tempat untuk notifikasi flash -->
        <?php if (session()->getFlashdata('message')) : ?>
            <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped" id="SubkategoriTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Kategori</th>
                    <th>Kode Sub Kategori</th>
                    <th>Nama Sub Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sub_kategori)) : ?>
                    <?php foreach ($sub_kategori as $key => $row) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= esc($row['kode_kategori']) ?> - <?= esc($row['nama_kategori']) ?></td>
                            <td><?= esc($row['kode_sub_kategori']) ?></td>
                            <td><?= esc($row['nama_sub_kategori']) ?></td>
                            <td><?= esc($row['deskripsi_sub_kategori']) ?></td>
                            <td>
                                <a href="<?= base_url('subkategori/edit/' . $row['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <form action="<?= base_url('subkategori/delete/' . $row['id']) ?>" method="post" class="d-inline form-delete">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert untuk konfirmasi hapus
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
            })
        });
    });

    // SweetAlert untuk flash message
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

    // Inisialisasi DataTables
    $(document).ready(function() {
        $('#SubkategoriTable').DataTable();
    });
</script>

<?= $this->endSection() ?>
