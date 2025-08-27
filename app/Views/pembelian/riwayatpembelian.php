<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="my-3 p-3 bg-body rounded shadow-sm">
  <!-- Header -->
  <div class="d-flex justify-content-between border-bottom py-2">
    <h3 class="pb-2 mb-0">Data Pembelian Barang</h3>
    <a href="<?= base_url('/pembelian') ?>" class="btn btn-primary"> + Tambah</a>
  </div>

  <div class="pt-3">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('message')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('message') ?>" data-type="success"></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
      <div id="flash-message" data-message="<?= session()->getFlashdata('error') ?>" data-type="error"></div>
    <?php endif; ?>

    <!-- Tabel Riwayat -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="riwayatpembelianTable">
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
            <th>Tanggal Pembelian</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($riwayat)) : ?>
            <?php $no = 1; foreach ($riwayat as $row) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($row['nama_barang'] ?? '-') ?></td>
                <td><?= esc($row['kode_barang'] ?? '-') ?></td>
                <td><?= esc($row['nama_kategori'] ?? '-') ?></td>
                <td><?= esc($row['kode_sub_kategori'] ?? '-') ?> - <?= esc($row['nama_sub_kategori'] ?? '-') ?></td>
                <td><?= esc($row['deskripsi_barang'] ?? '-') ?></td>
                <td><?= esc($row['jumlah_dibeli'] ?? '-') ?></td>
                <td><?= number_format($row['harga_satuan'] ?? 0, 2, ',', '.') ?></td>
                <td><?= number_format($row['total_harga'] ?? 0, 2, ',', '.') ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_pembelian'])) ?></td>
                <td>
                  <form action="<?= base_url('/pembelian/delete/' . $row['id_riwayat']) ?>" method="post" class="d-inline form-delete">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger btn-delete">
                      <i class="bi bi-trash"></i> 
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="11" class="text-center">Tidak ada data riwayat pembelian.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <a href="<?= base_url('/asset') ?>" class="btn btn-secondary mt-3">Kembali</a>
  </div>
</div>

<!-- Tambahan CSS DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables + Buttons JS -->


<script>
  // SweetAlert flash message
  const flashMessage = document.getElementById('flash-message');
  if (flashMessage) {
    const message = flashMessage.getAttribute('data-message');
    const type = flashMessage.getAttribute('data-type');
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

  // Konfirmasi Hapus
  $(document).on('submit', '.form-delete', function(e) {
    e.preventDefault();
    const form = this;

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data yang dihapus tidak dapat dikembalikan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });

  // Inisialisasi DataTable
  <?php /*$(document).ready(function () {
    $('#riwayatpembelianTable').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      pageLength: 10
    });
  });*/ ?>
</script>

<?= $this->endSection(); ?>
