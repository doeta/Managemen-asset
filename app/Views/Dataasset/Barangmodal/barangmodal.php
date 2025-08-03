<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <h3 class="pb-2 mb-3">Daftar Sub Kategori Barang Modal</h3>

  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="barangModalTable">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Sub Kategori</th>
          <th>Nama Sub Kategori</th>
          <th>Kategori</th>
          <th>Jumlah Barang</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sub_kategori)) : ?>
          <?php foreach ($sub_kategori as $i => $sub) : ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($sub['kode_sub_kategori']) ?></td>
              <td><?= esc($sub['nama_sub_kategori']) ?></td>
              <td><?= esc($sub['nama_kategori']) ?></td>
              <td><?= esc($sub['jumlah_barang']) ?></td>
              <td>
                <a href="<?= base_url('detailbarangmodal/' . $sub['kode_sub_kategori']) ?>" class="btn btn-info btn-sm">
                  Lihat Barang
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="5" class="text-center">Tidak ada data sub kategori yang tersedia.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#barangModalTable').DataTable();
  });
</script>

<?= $this->endSection(); ?>