<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-0">Tambah Asset</h3>
    <form action="/asset/store" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Asset</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
        </div>
        <div class="mb-3">
            <label for="kode_kategori" class="form-label">Kode Kategori</label>
            <select class="form-control" id="kode_kategori" name="kode_kategori" required>
                <option value="">Pilih Kategori</option>
                <?php if (!empty($kategori)) : ?>
                    <?php foreach ($kategori as $row): ?>
                        <option value="<?= $row['kode_kategori'] ?>"><?= $row['kode_kategori'] ?> - <?= $row['nama_kategori'] ?></option>
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
        <div class="mb-3">
            <label for="deskripsi_barang" class="form-label">Deskripsi Asset</label>
            <textarea class="form-control" id="deskripsi_barang" name="deskripsi_barang" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="jumlah_barang" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" required>
        </div>
        <div class="mb-3">
            <label for="harga_barang" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
        </div>
        <div class="mb-3">
            <label for="total_harga_barang" class="form-label">Total Harga</label>
            <input type="number" class="form-control" id="total_harga_barang" name="total_harga_barang" required readonly>
        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
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

    // pembagian sub kategori 
    
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