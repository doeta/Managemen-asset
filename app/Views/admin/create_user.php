
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah User</h3>
    <?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.session()->getFlashdata('error').'</div>' : '' ?>
    <form action="<?= base_url('auth/users/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user">User</option>
                <option value="operator">Operator</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah User</button>
        <a href="<?= base_url('auth/users') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>