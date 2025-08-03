<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Pengguna </h3>
    <form action="/pengguna/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" required>
        </div>
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" required>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No Telepon</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
        </div>
        <div class="mb-3">
            <label for="id_lokasi" class="form-label">Unit Kerja</label>
                <select class="form-control" id="id_lokasi" name="id_lokasi" required>
                    <option value="" disabled selected>Pilih Unit Kerja</option>
                    <?php foreach ($lokasi as $row): ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['nama_lokasi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>  
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/pengguna" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<?= $this->endSection() ?>
