<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2 mb-3">
    <h3 class="pb-2 mb-0">
      <?php if (isset($sub_kategori)) : ?>
        Detail Asset - <?= esc($sub_kategori['nama_sub_kategori']) ?>
      <?php else : ?>
        Detail Semua Barang Asset
      <?php endif; ?>
    </h3>
    <a href="<?= base_url('/dataassets') ?>" class="btn btn-primary">Kembali</a>
  </div>

  <?php if (isset($sub_kategori)) : ?>
  <!-- Info Sub Kategori -->
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="alert alert-info">
        <h5><i class="fas fa-info-circle"></i> Informasi</h5>
        <p class="mb-1"><strong>Kategori:</strong> <?= esc($sub_kategori['nama_kategori']) ?></p>
        <p class="mb-1"><strong>Sub Kategori:</strong> <?= esc($sub_kategori['nama_sub_kategori']) ?></p>
        <p class="mb-0"><strong>Kode:</strong> <code><?= esc($sub_kategori['kode_sub_kategori']) ?></code></p>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Tabel Data -->
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="barangTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
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
                <td><?= esc($asset['kode_barang'] ?? '-') ?></td>
                <td><?= esc($asset['kode_unik'] ?? '-') ?></td>
                <td><?= number_format($asset['harga_barang'] ?? 0, 2, ',', '.') ?></td>
                <td><?= esc($asset['tanggal_masuk'] ?? '-') ?></td>
                <td>
                  <span class="badge <?= strtolower($asset['status']) === 'tersedia' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= esc($asset['status'] ?? '-') ?>
                  </span>
                </td>
                <td><?= esc($asset['nama_pengguna'] ?? '-') ?></td>
                <td><?= esc($asset['nama_lokasi'] ?? '-') ?></td>
                <td>
                  <a href="<?= base_url('/dataassets/edit/' . $asset['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="<?= base_url('/dataassets/riwayat/' . $asset['id']) ?>" class="btn btn-sm btn-info">Riwayat</a>
                  <form action="<?= base_url('/dataassets/delete/' . $asset['id']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="10" class="text-center">Tidak ada data barang asset.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- DataTables + Export Buttons -->
<script>

    // Konfirmasi Hapus
    $('.btn-confirm-delete').on('click', function (e) {
      if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        e.preventDefault();
      }
    });

</script>

<?= $this->endSection(); ?>
