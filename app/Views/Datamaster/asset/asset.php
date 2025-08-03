<?= $this->extend('layouts/main'); ?>
<?= $this->section('content') ?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2">
    <h3 class="pb-2 mb-0">Data Asset</h3>
    <a href="<?= base_url('asset/create') ?>" class="btn btn-primary">Tambah Data</a>
  </div>

  <div class="pt-3">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('message')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
    <?php endif; ?>
    <!-- Tabel Asset -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="assetTable">
        <thead class="bg-light">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Kategori</th>
            <th>Sub Kategori</th>
            <th>Deskripsi</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Harga Total</th>
            <th>Tanggal Masuk</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($assets)) : ?>
            <?php $no = 1; foreach ($assets as $asset) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($asset['nama_barang']) ?></td>
                <td><?= esc($asset['kode_barang']) ?></td>
                <td><?= esc($asset['kode_kategori']) ?> - <?= esc($asset['nama_kategori']) ?></td>
                <td><?= esc($asset['kode_sub_kategori']) ?> - <?= esc($asset['nama_sub_kategori']) ?></td>
                <td><?= esc($asset['deskripsi_barang']) ?></td>
                <td><?= esc($asset['jumlah_barang']) ?></td>
                <td><?= number_format($asset['harga_barang'], 2, ',', '.') ?></td>
                <td><?= number_format($asset['total_harga_barang'], 2, ',', '.') ?></td>
                <td><?= esc($asset['tanggal_masuk']) ?></td>
                <td>
                  <a href="<?= base_url('asset/edit/' . $asset['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <form action="<?= base_url('asset/delete/' . $asset['id']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="11" class="text-center">Tidak ada data asset.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Konfirmasi hapus dengan SweetAlert2
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // SweetAlert flash message
  const flashMessage = document.getElementById('flash-message');
  if (flashMessage) {
    const message = flashMessage.getAttribute('data-message');
    const type = flashMessage.getAttribute('data-type');

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

  // DataTables init
  $(document).ready(function() {
    $('#assetTable').DataTable();
  });

  let namaAsset = "Data Asset"; // bisa juga diambil dari PHP

<?php /*$('#assetTable').DataTable({
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
      title: '' + namaAsset
    }
  ]
});*/ ?>

</script>

<?= $this->endSection(); ?>
