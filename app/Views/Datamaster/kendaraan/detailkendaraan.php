<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-4 bg-white rounded shadow-sm">
    <h3 class="pb-3 border-bottom">Detail Kendaraan</h3>
    <table class="table table-bordered">
        <tr>
            <th width="30%">Nama Kendaraan</th>
            <td><?= $kendaraan['nama_kendaraan'] ?></td>
        </tr>
        <tr>
            <th>Nama Pengguna</th>
            <td><?= $kendaraan['nama_pengguna'] ?></td>
        </tr>
        <tr>
            <th>No Polisi</th>
            <td><?= $kendaraan['no_polisi'] ?></td>
        </tr>
        <tr>
            <th>No Polisi Sebelumnya</th>
            <td><?= $kendaraan['nomor_polisi_sebelumnya'] ?></td>
        </tr>
        <tr>
            <th>Warna</th>
            <td><?= $kendaraan['warna'] ?></td>
        </tr>
        <tr>
            <th>Model Kendaraan</th>
            <td><?= $kendaraan['model_kendaraan'] ?></td>
        </tr>
        <tr>
            <th>Merk</th>
            <td><?= $kendaraan['merk_kendaraan'] ?></td>
        </tr>
        <tr>
            <th>Tipe Kendaraan</th>
            <td><?= $kendaraan['tipe_kendaraan'] ?></td>
        </tr>
        <tr>
            <th>Harga</th>
            <td><?= number_format($kendaraan['harga'], 0, ',', '.') ?></td>
        </tr>

        <tr>
            <th>Tahun Kendaraan</th>
            <td><?= $kendaraan['tahun_kendaraan'] ?></td>
        </tr>
        <tr>
            <th>No STNK</th>
            <td><?= $kendaraan['no_stnk'] ?></td>
        <tr>
            <th>No Rangka</th>
            <td><?= $kendaraan['no_rangka'] ?></td>
        </tr>
        <tr>
            <th>No Mesin</th>
            <td><?= $kendaraan['no_mesin'] ?></td>
        </tr>
        <tr>
            <th>No BPKB</th>
            <td><?= $kendaraan['no_bpkb'] ?></td>
        </tr>
        <tr>
            <th>Pembayaran Pajak</th>
            <td><?= date('d-m-Y', strtotime($kendaraan['pembayaran_pajak'])) ?></td>
        </tr>
        <tr>
            <th>Masa Berlaku</th>
            <td><?= date('d-m-Y', strtotime($kendaraan['masa_berlaku'])) ?></td>
        </tr>    
        
    </table>
    <a href="/kendaraan" class="btn btn-primary mt-3">Kembali</a>
</div>


<?= $this->endSection() ?>
