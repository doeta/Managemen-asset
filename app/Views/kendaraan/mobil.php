<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h3 class="pb-2 mb-3">Data Kendaraan - Mobil</h3>

    <table class="table table-bordered table-striped" id="kendaraanTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kendaraan</th>
                <th>No Polisi</th>
                <th>Pengguna</th>
                <th>Warna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($kendaraan)) : ?>
                <?php foreach ($kendaraan as $key => $row) : ?>
                    <tr>
                         <td><?= $key + 1 ?></td>
                        <td><?= esc($row['nama_kendaraan']) ?></td>
                        <td><?= esc($row['no_polisi']) ?></td>
                        <td><?= esc($row['nama_pengguna']) ?></td>
                        <td><?= esc($row['warna']) ?></td>
                        <td>
                            <a href="/kendaraan/editmobil/<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/kendaraan/riwayatmobil/<?= $row['id'] ?>" class="btn btn-info btn-sm">Riwayat</a>
                            <form action="/kendaraan/deletemobil/<?= $row['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kendaraan mobil.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
  // Script untuk menginisialisasi DataTables
  $(document).ready(function() {
    $('#kendaraanTable').DataTable();
  });
</script>

<?= $this->endSection(); ?>
