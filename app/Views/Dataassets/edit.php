<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="border-bottom pb-2 mb-3">Edit Barang Assets</h3>

    <form action="<?= base_url('/dataassets/update/' . $barang['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="tersedia" <?= $barang['status'] === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="habis terpakai" <?= $barang['status'] === 'habis terpakai' ? 'selected' : '' ?>>Habis Terpakai</option>
                <option value="terpakai" <?= $barang['status'] === 'terpakai' ? 'selected' : '' ?>>Di Pakai</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="id_lokasi" class="form-label">Lokasi</label>
            <select name="id_lokasi" id="id_lokasi" class="form-control select2" required>
                <option value="">Pilih Lokasi</option>
                <?php foreach ($lokasi as $loc) : ?>
                    <option value="<?= $loc['id'] ?>" <?= $loc['id'] == $barang['id_lokasi'] ? 'selected' : '' ?>>
                        <?= esc($loc['nama_lokasi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="id_pengguna" class="form-label">Pengguna</label>
            <select name="id_pengguna" id="id_pengguna" class="form-control select2" required>
                <option value="">Pilih Pengguna</option>
                <?php foreach ($pengguna as $user) : ?>
                    <option value="<?= $user['id'] ?>" <?= $user['id'] == $barang['id_pengguna'] ? 'selected' : '' ?>>
                        <?= esc($user['nama_pengguna']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="tanggal_perubahan" class="form-label">Tanggal Perubahan Pemilik</label>
            <input type="date" name="tanggal_perubahan" id="tanggal_perubahan" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" >
        </div>

        <div class="form-group mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
        </div>


        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('/dataassets/detail/' . $barang['kode_sub_kategori']) ?>" class="btn btn-primary">Kembali</a>
        </form>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#id_lokasi').select2({
        placeholder: "Cari atau pilih lokasi",
        allowClear: true
    });
    $('#id_pengguna').select2({
        placeholder: "Cari atau pilih pengguna",
        allowClear: true
    });
});
</script>
<?= $this->endSection() ?>
