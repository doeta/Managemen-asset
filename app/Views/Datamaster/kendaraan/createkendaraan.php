<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Kendaraan</h3>
    <form action="/kendaraan/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_kendaraan" class="form-label">Nama Kendaraan</label>
            <input type="text" class="form-control" id="nama_kendaraan" name="nama_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="no_polisi" class="form-label">No Polisi</label>
            <input type="text" class="form-control" id="no_polisi" name="no_polisi" required>
        </div>
        <div class="mb-3">
            <label for="nomor_polisi_sebelumnya" class="form-label">No Polisi Sebelumnya</label>
            <input type="text" class="form-control" id="nomor_polisi_sebelumnya" name="nomor_polisi_sebelumnya" required>
        </div>
        <div class="mb-3">
            <label for="id_pengguna" class="form-label">Pengguna</label>
            <select class="form-control" id="id_pengguna" name="id_pengguna" required>
                <option value="" disabled selected>Pilih Pengguna</option>
                <?php foreach ($pengguna as $pengguna) : ?>
                    <option value="<?= $pengguna['id'] ?>"><?= $pengguna['nama_pengguna'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_lokasi" class="form-label">Lokasi</label>
            <select class="form-control" id="id_lokasi" name="id_lokasi" required>
                <option value="" disabled selected>Pilih Lokasi</option>
                <?php foreach ($lokasi as $lokasi) : ?>
                    <option value="<?= $lokasi['id'] ?>"><?= $lokasi['nama_lokasi'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="warna" class="form-label">Warna</label>
            <input type="text" class="form-control" id="warna" name="warna" required>
        </div>
        <div class="mb-3">
            <label for="model_kendaraan" class="form-label">Model Kendaraan</label>
            <select class="form-control" id="model_kendaraan" name="model_kendaraan" required>
                <option value="" disabled selected>Pilih Model Kendaraan</option>
                <option value="motor">Motor</option>
                <option value="mobil">Mobil</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="merk_kendaraan" class="form-label">Merk</label>
            <input type="text" class="form-control" id="merk_kendaraan" name="merk_kendaraan" required>
        </div>      
        <div class="mb-3">
            <label for="tipe_kendaraan" class="form-label">Tipe Kendaraan</label>
            <input type="text" class="form-control" id="tipe_kendaraan" name="tipe_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="mb-3">
            <label for="tahun_kendaraan" class="form-label">Tahun Pembuatan</label>
            <input type="number" class="form-control" id="tahun_kendaraan" name="tahun_kendaraan" required>
        </div>

        <div class="mb-3">
            <label for="no_rangka" class="form-label">No Rangka</label>
            <input type="text" class="form-control" id="no_rangka" name="no_rangka" required>
        </div>
        <div class="mb-3">
            <label for="no_stnk" class="form-label">No STNK</label>
            <input type="text" class="form-control" id="no_stnk" name="no_stnk" required>
        </div>
        <div class="mb-3">
            <label for="no_mesin" class="form-label">No Mesin</label>
            <input type="text" class="form-control" id="no_mesin" name="no_mesin" required>
        </div>
        <div class="mb-3">
            <label for="no_bpkb" class="form-label">No BPKB</label>
            <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" required>
        </div>
        <div class="mb-3">
            <label for="pembayaran_pajak" class="form-label">Tanggal Pembayaran Pajak</label>
            <input type="date" class="form-control" id="pembayaran_pajak" name="pembayaran_pajak" required>
        </div>
        <div class="mb-3">
            <label for="masa_berlaku" class="form-label">Masa Berlaku </label>
            <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" required>
        </div>
       
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/kendaraan" class="btn btn-secondary">Kembali</a>
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
