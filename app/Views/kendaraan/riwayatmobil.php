<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-3">Detail Mobil</h3>
    <table class="table table-bordered mb-4">
        <tr>
            <th>Nama Kendaraan</th>
            <td><?= esc($kendaraan['nama_kendaraan'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Pajak</th>
            <td><?= esc($kendaraan['pembayaran_pajak'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Merk</th>
            <td><?= esc($kendaraan['merk_kendaraan'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td><?= esc($kendaraan['tahun_kendaraan'] ?? '-') ?></td>
        </tr>
        <tr>
            <th>Harga</th>
            <td><?= esc($kendaraan['harga'] ?? '-') ?></td>
        </tr>
    </table>

    <h4 class="mb-3 mt-4">Riwayat Penggunaan Mobil</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nomor Polisi</th>
                <th>Tanggal Mulai</th>
                <th>Pengguna</th>
                <th>Lokasi</th>
                <th>Tanggal Selesai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($riwayat)) : ?>
                <?php foreach ($riwayat as $row) : ?>
                    <tr>
                        <td><?= esc($row['nomor_polisi'] ?? '-') ?></td>
                        <td><?= esc($row['tanggal_mulai'] ?? '-') ?></td>
                        <td><?= esc($row['nama_pengguna'] ?? '-') ?></td>
                        <td><?= esc($row['nama_lokasi'] ?? '-') ?></td>
                        <td><?= esc($row['tanggal_selesai'] ?? '-') ?></td>
                        <td><?= esc($row['keterangan'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada riwayat penggunaan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="<?= base_url('/kendaraan/mobil') ?>" class="btn btn-primary">Kembali</a>
</div>

<?= $this->endSection(); ?>