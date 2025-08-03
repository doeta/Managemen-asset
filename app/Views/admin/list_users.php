<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <div class="d-flex justify-content-between border-bottom py-2">
        <h3 class="pb-2 mb-0">Daftar Pengguna</h3>
        <a href="<?= base_url('auth/users/create') ?>" class="btn btn-primary">Tambah User</a>
    </div>

    <div class="pt-3">
        <!-- Notifikasi -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div id="flash-message" data-message="<?= session()->getFlashdata('success') ?>" data-type="success"></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped" id="userTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $key => $u) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= esc($u['username']) ?></td>
                            <td><?= esc($u['nama']) ?></td>
                            <td><?= esc($u['email']) ?></td>
                            <td><?= esc($u['role']) ?></td>
                            <td>
                                <form action="<?= base_url('auth/users/delete/' . $u['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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

    // DataTable
    $(document).ready(function () {
        $('#userTable').DataTable();
    });
</script>
<?= $this->endSection() ?>
