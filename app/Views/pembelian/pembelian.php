<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?>
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-3">Tambah Asset</h3>

    <!-- Flash Message SweetAlert -->
    <div id="flash-message"
         data-message="<?= session()->getFlashdata('message') ?>"
         data-type="<?= session()->getFlashdata('status_barang') == 'lama' ? 'warning' : 'success' ?>">
    </div>

    <form action="/pembelian/simpan" method="post">
        <?= csrf_field() ?>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nama_barang" class="form-label">Nama Asset *</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
            </div>
            <div class="col-md-6">
                <label for="tanggal_masuk" class="form-label">Tanggal Masuk *</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="kode_kategori" class="form-label">Kategori *</label>
                <select class="form-control" id="kode_kategori" name="kode_kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?php if (!empty($kategori)) : ?>
                        <?php foreach ($kategori as $row): ?>
                            <option value="<?= $row['kode_kategori'] ?>"><?= $row['kode_kategori'] ?> - <?= $row['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="kode_sub_kategori" class="form-label">Sub Kategori *</label>
                <div class="input-group">
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
            </div>
        </div>

        <div class="mb-3">
            <label for="deskripsi_barang" class="form-label">Deskripsi Asset</label>
            <textarea class="form-control" id="deskripsi_barang" name="deskripsi_barang" rows="2" required></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="jumlah_barang" class="form-label">Jumlah *</label>
                <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" required>
            </div>
            <div class="col-md-4">
                <label for="harga_barang" class="form-label">Harga Barang Rp. *</label>
                <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
                <small class="text-danger">* Harga satuan</small>
            </div>
            <div class="col-md-4">
                <label for="total_harga_barang" class="form-label">Jumlah Nilai Barang Rp. *</label>
                <input type="number" class="form-control" id="total_harga_barang" name="total_harga_barang" readonly>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <a href="/riwayatpembelian" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Inisialisasi Select2 untuk Sub Kategori
        $('#kode_sub_kategori').select2({
            placeholder: 'Pilih Sub Kategori',
            allowClear: true,
            width: '100%'
        });

        const $subKategori = $('#kode_sub_kategori');
        const $kategori = $('#kode_kategori');

        // Simpan semua opsi sub kategori
        const allSubOptions = $subKategori.find('option').clone();

        // Filter sub kategori sesuai kategori
        $kategori.on('change', function () {
            const selectedKategori = $(this).val();
            $subKategori.empty().append(new Option('Pilih Sub Kategori', '', true, false));

            allSubOptions.each(function () {
                const kodeKategori = $(this).data('kode-kategori');
                if (kodeKategori === selectedKategori) {
                    $subKategori.append($(this).clone());
                }
            });

            $subKategori.val(null).trigger('change');
        });

        // Hitung total harga
        const jumlahBarangInput = $('#jumlah_barang');
        const hargaBarangInput = $('#harga_barang');
        const totalHargaInput = $('#total_harga_barang');

        function hitungTotalHarga() {
            const jumlah = parseFloat(jumlahBarangInput.val()) || 0;
            const harga = parseFloat(hargaBarangInput.val()) || 0;
            totalHargaInput.val(jumlah * harga);
        }

        jumlahBarangInput.on('input', hitungTotalHarga);
        hargaBarangInput.on('input', hitungTotalHarga);

        // SweetAlert flash message
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            const message = flashMessage.getAttribute('data-message');
            const type = flashMessage.getAttribute('data-type'); // success / warning

            if (message) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        }
    });
    
</script>

<?= $this->endSection() ?>
