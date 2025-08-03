<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Edit Lokasi Asset</h3>
    <form action="/lokasi/update/<?= $lokasi['id'] ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="POST">
        <div class="mb-3">
            <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
            <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" value="<?= $lokasi['nama_lokasi'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi_lokasi" class="form-label">Deskripsi Lokasi</label>
            <textarea class="form-control" id="deskripsi_lokasi" name="deskripsi_lokasi" rows="3" required><?= $lokasi['deskripsi_lokasi'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/lokasi" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>
