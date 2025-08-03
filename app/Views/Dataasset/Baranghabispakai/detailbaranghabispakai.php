<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<?php $nama_asset = 'Barang Habis Pakai'; // Atau dari controller ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2 mb-3">
    <h3 class="pb-2 mb-0">Detail <?= esc($nama_asset) ?></h3>
    <a href="<?= base_url('/baranghabispakai') ?>" class="btn btn-primary">Kembali</a>
  </div>

  <!-- Tabel Data -->
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="barangTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Unik</th>
            <th>Harga</th>
            <th>Tanggal Masuk</th>
            <th>Status</th>
            <th>Nama Pengguna</th>
            <th>Lokasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($barang)) : ?>
            <?php $no = 1; foreach ($barang as $asset) : ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($asset['nama_barang']) ?></td>
                <td><?= esc($asset['kode_unik']) ?></td>
                <td><?= number_format($asset['harga_barang'], 2, ',', '.') ?></td>
                <td><?= esc($asset['tanggal_masuk']) ?></td>
                <td>
                  <span class="badge <?= $asset['status'] === 'tersedia' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= esc($asset['status']) ?>
                  </span>
                </td>
                <td><?= esc($asset['nama_pengguna'] ?? '-') ?></td>
                <td><?= esc($asset['nama_lokasi'] ?? '-') ?></td>
                <td>
                  <form action="<?= base_url('/baranghabispakai/delete/' . $asset['id']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="9" class="text-center">Tidak ada data untuk sub kategori ini.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables + Export Buttons -->
<script>
  $(document).ready(function () {
    let namaAsset = "<?= esc($nama_asset) ?>";

    $('#barangTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copy',
          title: 'Data Asset - ' + namaAsset
        },
        {
          extend: 'csv',
          title: 'Data Asset - ' + namaAsset,
          filename: 'Data_Asset_' + namaAsset.replace(/\s+/g, '_')
        },
        {
          extend: 'excel',
          title: 'Data Asset - ' + namaAsset,
          filename: 'Data_Asset_' + namaAsset.replace(/\s+/g, '_')
        },
        {
          extend: 'pdf',
          title: 'Data Asset - ' + namaAsset,
          filename: 'Data_Asset_' + namaAsset.replace(/\s+/g, '_'),
          customize: function (doc) {
            doc.content.splice(0, 0, {
              text: 'Data Asset - ' + namaAsset,
              fontSize: 14,
              margin: [0, 0, 0, 12]
            });
          }
        },
        {
          extend: 'print',
          title: 'Data Asset - ' + namaAsset
        }
      ]
    });

    // Konfirmasi Hapus
    $('.btn-confirm-delete').on('click', function (e) {
      if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        e.preventDefault();
      }
    });
  });
</script>

<?= $this->endSection(); ?>
