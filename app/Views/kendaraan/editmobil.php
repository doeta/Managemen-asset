<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h4 class="mb-4">Tambah Riwayat Penggunaan</h4>

    <form action="<?= base_url('/kendaraan/mobil/riwayat/tambah/' . $kendaraan['id']) ?>" method="post">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="nomor_polisi" class="form-label">Nomor Polisi</label>
                <input type="text" name="nomor_polisi" id="nomor_polisi" class="form-control" value="<?= esc($kendaraan['no_polisi'] ?? '') ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label for="id_pengguna" class="form-label">Pengguna</label>
                <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                    <option value="">Pilih Pengguna</option>
                    <?php foreach ($pengguna as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= esc($user['nama_pengguna']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="id_lokasi" class="form-label">Lokasi</label>
                <select name="id_lokasi" id="id_lokasi" class="form-control" required>
                    <option value="">Pilih Lokasi</option>
                    <?php foreach ($lokasi as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['nama_lokasi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control">
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-success">Simpan Riwayat</button>
            <a href="<?= base_url('/kendaraan/mobil') ?>" class="btn btn-secondary">Kembali</a>
        </div>
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

<?= $this->endSection(); ?>
