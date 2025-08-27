<?=  $this->extend('layouts/main'); ?>\

<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Edit Kendaraan</h3>
    <form action="/kendaraan/update/<?= $kendaraan['id'] ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_kendaraan" class="form-label">Nama Kendaraan</label>
            <input type="text" class="form-control" id="nama_kendaraan" value="<?= $kendaraan['nama_kendaraan'] ?>" name="nama_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="no_polisi" class="form-label">No Polisi</label>
            <input type="text" class="form-control" id="no_polisi" value="<?= $kendaraan['no_polisi'] ?>" name="no_polisi" required>
        </div>
        <div class="mb-3">
            <label for="nomor_polisi_sebelumnya" class="form-label">No Polisi Sebelumnya</label>
            <input type="text" class="form-control" id="nomor_polisi_sebelumnya" value="<?= $kendaraan['nomor_polisi_sebelumnya'] ?>" name="nomor_polisi_sebelumnya" required>
        </div>
        <div class="mb-3">
            <label for="id_pengguna" class="form-label">Pengguna</label>
            <select class="form-control" id="id_pengguna" name="id_pengguna" required>
                <option value="">-- Pilih Pengguna --</option>
                <?php foreach ($pengguna as $peng) : ?>
                    <option value="<?= $peng['id'] ?>" <?= ($kendaraan['id_pengguna'] == $peng['id']) ? 'selected' : '' ?>>
                        <?= $peng['nama_pengguna'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="warna" class="form-label">Warna</label>
            <input type="text" class="form-control" id="warna" value="<?= $kendaraan['warna'] ?>" name="warna" required>
        </div>
        <div class="mb-3">
            <label for="model_kendaraan" class="form-label">Model Kendaraan</label>
            <select class="form-control" id="model_kendaraan" name="model_kendaraan" required>
                <option value="">-- Pilih Model Kendaraan --</option>
                <option value="motor" <?= ($kendaraan['model_kendaraan'] === 'motor') ? 'selected' : '' ?>>Motor</option>
                <option value="mobil" <?= ($kendaraan['model_kendaraan'] === 'mobil') ? 'selected' : '' ?>>Mobil</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="merk_kendaraan" class="form-label">Merk</label>
            <input type="text" class="form-control" id="merk_kendaraan" value="<?= $kendaraan['merk_kendaraan'] ?>" name="merk_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="tipe_kendaraan" class="form-label">Tipe Kendaraan</label>
            <input type="text" class="form-control" id="tipe_kendaraan" value="<?= $kendaraan['tipe_kendaraan'] ?>" name="tipe_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" value="<?= $kendaraan['harga'] ?>" name="harga" required>
        </div>

        <div class="mb-3">
            <label for="tahun_kendaraan" class="form-label">Tahun Pembuatan</label>
            <input type="number" class="form-control" id="tahun_kendaraan" value="<?= $kendaraan['tahun_kendaraan'] ?>" name="tahun_kendaraan" required>
        </div>
        <div class="mb-3">
            <label for="no_rangka" class="form-label">No Rangka</label>
            <input type="text" class="form-control" id="no_rangka" value="<?= $kendaraan['no_rangka'] ?>" name="no_rangka" required>
        </div>
        <div class="mb-3">
            <label for="no_stnk" class="form-label">No STNK</label>
            <input type="text" class="form-control" id="no_stnk" value="<?= $kendaraan['no_stnk'] ?>" name="no_stnk" required>
        </div>
        <div class="mb-3">
            <label for="no_mesin" class="form-label">No Mesin</label>
            <input type="text" class="form-control" id="no_mesin" value="<?= $kendaraan['no_mesin'] ?>" name="no_mesin" required>
        </div>
        <div class="mb-3">
            <label for="no_bpkb" class="form-label">No BPKB</label>
            <input type="text" class="form-control" id="no_bpkb" value="<?= $kendaraan['no_bpkb'] ?>" name="no_bpkb" required>
        </div>
        <div class="mb-3">
            <label for="harga_pajak" class="form-label">Harga Pajak</label>
            <input type="number" class="form-control" id="harga_pajak" value="<?= $kendaraan['harga_pajak'] ?>" name="harga_pajak" required>
        <div class="mb-3">
            <label for="pembayaran_pajak" class="form-label">Tanggal Pembayaran Pajak</label>
            <input type="date" class="form-control" id="pembayaran_pajak" value="<?= $kendaraan['pembayaran_pajak'] ?>" name="pembayaran_pajak" required>
        </div>
        <div class="mb-3">
            <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
            <input type="date" class="form-control" name="masa_berlaku" id="masa_berlaku" readonly>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/kendaraan" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#id_pengguna').select2({
        placeholder: "Cari atau pilih pengguna",
        allowClear: true
    });
});

//pajak otomatis 1 tahun
  document.addEventListener('DOMContentLoaded', function () {
    const bayarInput = document.getElementById('pembayaran_pajak');
    const masaInput = document.getElementById('masa_berlaku');

    bayarInput.addEventListener('change', function () {
      const bayarDate = new Date(this.value);
      if (!isNaN(bayarDate.getTime())) {
        const tahunDepan = new Date(bayarDate);
        tahunDepan.setFullYear(tahunDepan.getFullYear() + 1);
        masaInput.value = tahunDepan.toISOString().split('T')[0];
      }
    });
  });

</script>
<?= $this->endSection() ?>

