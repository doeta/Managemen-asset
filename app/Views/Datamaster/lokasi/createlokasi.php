<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Lokasi Asset</h3>
    <form action="/lokasi/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
            <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi_lokasi" class="form-label">Deskripsi Lokasi</label>
            <textarea class="form-control" id="deskripsi_lokasi" name="deskripsi_lokasi" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/lokasi" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>
