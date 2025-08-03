<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="border-bottom pb-2 mb-3">Form Tambah Pemakaian Barang</h3>
    <form action="<?= base_url('pemakaian/simpan') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Kategori dan Subkategori -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kode_kategori" id="kode_kategori" class="form-control select2" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['kode_kategori'] ?>"><?= esc($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sub Kategori</label>
                <select name="kode_sub_kategori" id="kode_sub_kategori" class="form-control select2" required>
                    <option value="">Pilih Sub Kategori</option>
                    <?php foreach ($sub_kategori as $sub): ?>
                        <option value="<?= $sub['kode_sub_kategori'] ?>" data-kode-kategori="<?= $sub['kode_kategori'] ?>">
                            <?= esc($sub['nama_sub_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Barang dan Lokasi -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <select name="id_asset" id="id_asset" class="form-control select2" required>
                    <option value="">Pilih Barang</option>
                    <?php foreach ($barang as $b): ?>
                        <option value="<?= $b['id'] ?>"
                                data-kategori="<?= $b['kode_kategori'] ?>"
                                data-sub="<?= $b['kode_sub_kategori'] ?>"
                                data-stok="<?= $b['jumlah_barang'] ?>">
                            <?= esc($b['nama_barang']) ?> (Stok: <?= $b['jumlah_barang'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="stok-tertampil" class="text-muted mt-2"></div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Lokasi</label>
                <select name="id_lokasi" class="form-control select2" required>
                    <option value="">Pilih Lokasi</option>
                    <?php foreach ($lokasi as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['nama_lokasi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Pengguna, Jumlah, Satuan -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Pengguna</label>
                <select name="id_pengguna" class="form-control select2" required>
                    <option value="">Pilih Pengguna</option>
                    <?php foreach ($pengguna as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= esc($p['nama_pengguna']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jumlah Digunakan</label>
                <input type="number" name="jumlah_digunakan" id="jumlah_digunakan" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan_penggunaan" class="form-control" required>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal Pemakaian</label>
                <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control">
            </div>
        </div>
        <!-- Status -->
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>    
                <option value="tersedia">Tersedia</option>
                <option value="habis terpakai">Habis Terpakai</option>
                <option value="terpakai">Terpakai</option>
            </select>
        </div>                  
        <!-- Keterangan -->
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('pemakaian') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Select2 + jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('.select2').select2();

    // Simpan semua opsi subkategori
    const allSubOptions = $('#kode_sub_kategori option').clone();
    const allBarangOptions = $('#id_asset option').clone();

    $('#kode_kategori').on('change', function () {
        const selectedKategori = $(this).val();

        // Filter subkategori berdasarkan kategori
        $('#kode_sub_kategori').html('<option value="">Pilih Sub Kategori</option>');
        allSubOptions.each(function () {
            const kat = $(this).data('kode-kategori');
            if (kat == selectedKategori) {
                $('#kode_sub_kategori').append($(this));
            }
        });

        // Reset barang dan stok
        $('#id_asset').val('').trigger('change');
        $('#id_asset option:not(:first)').hide();
        $('#stok-tertampil').text('');
    });

    $('#kode_sub_kategori').on('change', function () {
        const selectedSub = $(this).val();
        const selectedKategori = $('#kode_kategori').val();

        $('#id_asset').html('<option value="">Pilih Barang</option>');
        allBarangOptions.each(function () {
            const kat = $(this).data('kategori');
            const sub = $(this).data('sub');
            if (kat == selectedKategori && sub == selectedSub) {
                $('#id_asset').append($(this));
            }
        });

        $('#stok-tertampil').text('');
    });

    $('#id_asset').on('change', function () {
        const stok = $('option:selected', this).data('stok') || 0;
        $('#stok-tertampil').text('Stok tersedia: ' + stok);
        $('#jumlah_digunakan').attr('max', stok);
    });

    $('#jumlah_digunakan').on('input', function () {
        const max = parseInt($(this).attr('max'));
        if (parseInt(this.value) > max) {
            alert('Jumlah melebihi stok tersedia!');
            this.value = max;
        }
    });
});
</script>

<?= $this->endSection() ?>
