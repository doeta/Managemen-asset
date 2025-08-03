<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="border-bottom pb-2 mb-3">Edit Asset</h3>
    <form action="/asset/update/<?= $asset['id'] ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="nama_barang">Nama Asset</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= $asset['nama_barang'] ?>" required>
        </div>
        <div class="form-group">
            <label for="kode_kategori">Kategori</label>
            <select class="form-control" id="kode_kategori" name="kode_kategori" required>
                <option value="">Pilih Kategori</option>
                <?php if (!empty($kategori)) : ?>
                    <?php foreach ($kategori as $row): ?>
                        <option value="<?= $row['kode_kategori'] ?>" <?= $row['kode_kategori'] == $asset['kode_kategori'] ? 'selected' : '' ?>>
                            <?= $row['kode_kategori'] ?> - <?= $row['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="kode_sub_kategori" class="form-label">Sub Kategori</label>
            <select class="form-control" id="kode_sub_kategori" name="kode_sub_kategori" required>
                <option value="">Pilih Sub Kategori</option>
                <?php if (!empty($sub_kategori)) : ?>
                    <?php foreach ($sub_kategori as $row): ?>
                        <option value="<?= $row['kode_sub_kategori'] ?>" data-kode-kategori="<?= $row['kode_kategori'] ?>">
                            <?= $row['kode_sub_kategori'] ?> - <?= $row['nama_sub_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="deskripsi_barang">Deskripsi</label>
            <textarea name="deskripsi_barang" id="deskripsi_barang" class="form-control" required><?= $asset['deskripsi_barang'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="jumlah_barang">Jumlah</label>
            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" value="<?= $asset['jumlah_barang'] ?>" required>
        </div>
        <div class="form-group">
            <label for="harga_barang">Harga</label>
            <input type="number" name="harga_barang" id="harga_barang" class="form-control" value="<?= $asset['harga_barang'] ?>" required>
        </div>
        <div class="form-group">
            <label for="total_harga_barang">Total Harga</label>
            <input type="number" name="total_harga_barang" id="total_harga_barang" class="form-control" value="<?= $asset['total_harga_barang'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="tanggal_masuk">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="<?= $asset['tanggal_masuk'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/asset" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- jQuery (wajib) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Ambil elemen input
    const jumlahBarangInput = document.getElementById('jumlah_barang');
    const hargaBarangInput = document.getElementById('harga_barang');
    const totalHargaInput = document.getElementById('total_harga_barang');

    // Tambahkan event listener untuk menghitung total harga
    function hitungTotalHarga() {
        const jumlah = parseFloat(jumlahBarangInput.value) || 0;
        const harga = parseFloat(hargaBarangInput.value) || 0;
        const total = jumlah * harga;
        totalHargaInput.value = total; // Set nilai total harga
    }

    // Event listener untuk input jumlah dan harga
    jumlahBarangInput.addEventListener('input', hitungTotalHarga);
    hargaBarangInput.addEventListener('input', hitungTotalHarga);
    
    // Event listener untuk kategori dan subkategori
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kode_kategori');
        const subKategoriSelect = document.getElementById('kode_sub_kategori');
        const allSubOptions = Array.from(subKategoriSelect.options);

        kategoriSelect.addEventListener('change', function () {
            const selectedKategori = this.value;

            // Reset subkategori select
            subKategoriSelect.innerHTML = '';

            // Tambah opsi default
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Pilih Sub Kategori';
            subKategoriSelect.appendChild(defaultOption);

            // Filter dan tampilkan opsi subkategori yang sesuai
            allSubOptions.forEach(option => {
                const kodeKategori = option.getAttribute('data-kode-kategori');
                if (kodeKategori === selectedKategori) {
                    subKategoriSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });

        
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kode_kategori');
        const subKategoriSelect = document.getElementById('kode_sub_kategori');
        const allSubOptions = Array.from(subKategoriSelect.options);

        kategoriSelect.addEventListener('change', function () {
            const selectedKategori = this.value;

            // Reset subkategori select
            subKategoriSelect.innerHTML = '';

            // Tambah opsi default
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Pilih Sub Kategori';
            subKategoriSelect.appendChild(defaultOption);

            // Filter dan tampilkan opsi subkategori yang sesuai
            allSubOptions.forEach(option => {
                const kodeKategori = option.getAttribute('data-kode-kategori');
                if (kodeKategori === selectedKategori) {
                    subKategoriSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });

    // Inisialisasi Select2
    $(document).ready(function() {
        $('#kode_sub_kategori').select2({
            placeholder: 'Pilih Sub Kategori',
            allowClear: true
        });
    });

</script>
<?= $this->endSection() ?>