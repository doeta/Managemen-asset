<?= $this->extend('layouts/main'); ?>

<?= $this->section('content') ?> 

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2 mb-3">
    <h3 class="pb-2 mb-0">Detail Barang Modal</h3>
    <a href="<?= base_url('/barangmodal') ?>" class="btn btn-primary">Kembali</a>
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
                  <!-- Tombol Aksi -->
                  <form action="<?= base_url('barangmodal/delete/' . $asset['id']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete">Hapus</button>  
                  </form>
                  <a href="<?= base_url('/barangmodal/edit/' . $asset['id']) ?>" class="btn btn-sm btn-primary">Update</a>
                  <a href="<?= base_url('/barangmodal/riwayat/' . $asset['id']) ?>" class="btn btn-sm btn-info">Riwayat</a>
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

<!-- DataTable & Konfirmasi Hapus -->
<script>
  $(document).ready(function () {
    $('#barangTable').DataTable();

    // Konfirmasi saat klik tombol hapus
    $('.btn-confirm-delete').on('click', function (e) {
      if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        e.preventDefault();
      }
    });
  });
  
  let namaBarangModal = " Data Barang Modal";

  $('#barangTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'copy',
        title: 'Data Barang Modal - ' + namaBarangModal,
      },
      {
        extend: 'csv',
        title: 'Data Barang Modal - ' + namaBarangModal,
        filename: 'Data_Barang_Modal_' + namaBarangModal + '.csv'
      },
      {
        extend: 'excel',
        title: 'Data Barang Modal - ' + namaBarangModal,
        filename: 'Data_Barang_Modal_' + namaBarangModal
      },
      {
        extend: 'pdf',
        title: 'Data Barang Modal - ' + namaBarangModal,
        filename: 'Data_Barang_Modal_' + namaBarangModal.replace(/\s+/g, '_'),
        customize: function (doc) {
          doc.content.splice(0, 0, {
            text: 'Data Barang Modal - ' + namaBarangModal,
            fontsize: 18,
            margin: [0, 0, 0, 12],

          });
        }
      },
      {
        extend: 'print',
        title: '' + namaBarangModal,
      }
    ]
  });
</script>

<?= $this->endSection(); ?>
