<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Asset</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 12px;
            font-style: italic;
        }
        .filter-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .filter-info strong {
            color: #495057;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 40px;
            margin-left: auto;
        }
        .page-break {
            page-break-before: always;
        }
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .status-rusak {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .status-maintenance {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA ASSET</h1>
        <h2>Dinas Komunikasi dan Informatika</h2>
        <h3>Kebumen</h3>
    </div>

    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong><br>
        Kategori: <?= $kategori ?><br>
        Sub Kategori: <?= $sub_kategori ?><br>
        Nama Barang: <?= $nama_barang ?><br>
        Tahun: <?= $tahun ?><br>
        <strong>Total Data: <?= count($barang) ?> item</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Kode Unik</th>
                <th width="20%">Nama Barang</th>
                <th width="12%">Kategori</th>
                <th width="15%">Sub Kategori</th>
                <th width="10%">Harga Barang</th>
                <th width="8%">Tanggal Masuk</th>
                <th width="6%">Status</th>
                <th width="8%">Pengguna</th>
                <th width="6%">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($barang)) : ?>
                <?php foreach ($barang as $i => $item) : ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><?= esc($item['kode_unik'] ?? '-') ?></td>
                        <td><?= esc($item['nama_barang'] ?? '-') ?></td>
                        <td><?= esc($item['nama_kategori'] ?? '-') ?></td>
                        <td><?= esc($item['nama_sub_kategori'] ?? '-') ?></td>
                        <td class="text-right"><?= !empty($item['harga_barang']) ? 'Rp ' . number_format($item['harga_barang'], 0, ',', '.') : '-' ?></td>
                        <td class="text-center"><?= !empty($item['tanggal_masuk']) ? date('d-m-Y', strtotime($item['tanggal_masuk'])) : '-' ?></td>
                        <td class="text-center">
                            <?php 
                            $status = strtolower($item['status'] ?? '');
                            $statusClass = '';
                            switch($status) {
                                case 'aktif':
                                    $statusClass = 'status-aktif';
                                    break;
                                case 'rusak':
                                    $statusClass = 'status-rusak';
                                    break;
                                default:
                                    $statusClass = 'status-maintenance';
                            }
                            ?>
                            <span class="<?= $statusClass ?>"><?= ucfirst($item['status'] ?? '-') ?></span>
                        </td>
                        <td><?= esc($item['nama_pengguna'] ?? '-') ?></td>
                        <td><?= esc($item['nama_lokasi'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data asset yang tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Kebumen, <?= date('d') ?> <?= ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][date('n')] ?> <?= date('Y') ?></p>
            <p>Mahasiswa Magang Dinas Komunikasi dan Informatika</p>
            <p><strong>Duta Adi Pamungkas</strong></p>
            <p>Mahasiswa Informatika</p>
            <p>24060123140174</p>
        </div>
    </div>
</body>
</html>
