<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Sub Kategori</h3>
    <form action="/subkategori/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="kode_kategori" class="form-label">Kode Kategori</label>
            <select class="form-control" id="kode_kategori" name="kode_kategori" required>
                <option value="" disabled selected>Pilih Kategori</option>
                    <?php foreach ($kategori as $row) : ?>
                        <option value="<?= $row['kode_kategori'] ?>"><?= $row['kode_kategori'] ?> - <?= $row['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nama_sub_kategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_sub_kategori" name="nama_sub_kategori" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi_sub_kategori" class="form-label">Deskripsi Kategori</label>
            <textarea class="form-control" id="deskripsi_sub_kategori" name="deskripsi_sub_kategori" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/subkategori" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>