<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Kategori</h3>
    <form action="/kategori/update/<?= $sub_kategori['id'] ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="kode_kategori">Kategori</label>
            <select class="form-control" id="kode_kategori" name="kode_kategori" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori as $row): ?>
                    <option value="<?= $row['kode_kategori'] ?>" <?= $row['kode_kategori'] == $sub_kategori['kode_kategori'] ? 'selected' : '' ?>>
                        <?= $row['kode_kategori'] ?> - <?= $row['nama_kategori'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nama_sub_kategori">Nama Sub Kategori</label>
            <input type="text" name="nama_sub_kategori" id="nama_sub_kategori" class="form-control" value="<?= $sub_kategori['nama_sub_kategori'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi_kategori">Deskripsi</label>
            <textarea name="deskripsi_sub_kategori" id="deskripsi_sub_kategori" class="form-control" required><?= $sub_kategori['deskripsi_sub_kategori'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/kategori" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>