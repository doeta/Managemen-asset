<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Kategori</h3>
    <form action="/kategori/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="kode_kategori" class="form-label">Kode Kategori</label>
            <input type="text" class="form-control" id="kode_kategori" name="kode_kategori" required>
        </div>
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/kategori" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>
