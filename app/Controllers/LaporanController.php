<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemakaianModel;
use App\Models\RiwayatPembelianModel;
use App\Models\KendaraanModel;
use App\Models\KategoriModel;
use App\Models\SubKategoriModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;


class LaporanController extends BaseController
{
//////////////////////////////////////////////////////
////////////////laporan Data Asset/////////////////////
//////////////////////////////////////////////////////
    public function dataasset()
    {
        $db = \Config\Database::connect();

        // Query untuk mengambil data barang dari struktur database yang baru (general)
        $query = $db->table('barang')
            ->select('barang.*, asset.kode_sub_kategori, sub_kategori.nama_sub_kategori, kategori.nama_kategori, 
                    pengguna.nama_pengguna, lokasi.nama_lokasi, YEAR(barang.tanggal_masuk) as tahun_perolehan')
            ->join('asset', 'asset.id = barang.id_asset', 'left')
            ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
            ->orderBy('kategori.nama_kategori, sub_kategori.nama_sub_kategori, barang.nama_barang')
            ->get()
            ->getResultArray();

        // Ambil data kategori untuk filter
        $kategoriQuery = $db->table('kategori')
            ->select('nama_kategori')
            ->orderBy('nama_kategori')
            ->get()
            ->getResultArray();

        // Ambil data sub kategori untuk filter
        $subKategoriQuery = $db->table('sub_kategori')
            ->select('sub_kategori.nama_sub_kategori, kategori.nama_kategori')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->orderBy('kategori.nama_kategori, sub_kategori.nama_sub_kategori')
            ->get()
            ->getResultArray();

        // Ambil data barang untuk filter nama barang
        $barangQuery = $db->table('barang')
            ->select('nama_barang')
            ->where('nama_barang IS NOT NULL')
            ->where('nama_barang !=', '')
            ->groupBy('nama_barang')
            ->orderBy('nama_barang')
            ->get()
            ->getResultArray();

        $data = [
            'menu' => 'laporan_dataasset',
            'barang' => $query,
            'kategori' => $kategoriQuery,
            'sub_kategori' => $subKategoriQuery,
            'nama_barang_list' => $barangQuery
        ];

        return view('laporan/dataasset/dataasset', $data);
    }

    // Method untuk AJAX request - get sub kategori berdasarkan kategori
    public function getSubKategoriByKategori()
    {
        $kategori = $this->request->getGet('kategori');
        
        if (empty($kategori)) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();
        $subKategori = $db->table('sub_kategori')
            ->select('sub_kategori.nama_sub_kategori')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->where('kategori.nama_kategori', $kategori)
            ->orderBy('sub_kategori.nama_sub_kategori')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($subKategori);
    }

    // Method untuk AJAX request - get nama barang berdasarkan sub kategori
    public function getNamaBarangBySubKategori()
    {
        $sub_kategori = $this->request->getGet('sub_kategori');
        
        if (empty($sub_kategori)) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();
        $namaBarang = $db->table('barang')
            ->select('barang.nama_barang')
            ->join('asset', 'asset.id = barang.id_asset', 'left')
            ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
            ->where('sub_kategori.nama_sub_kategori', $sub_kategori)
            ->where('barang.nama_barang IS NOT NULL')
            ->where('barang.nama_barang !=', '')
            ->groupBy('barang.nama_barang')
            ->orderBy('barang.nama_barang')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($namaBarang);
    }

    // Method untuk AJAX request - get data barang berdasarkan filter
    public function getDataBarangByFilter()
    {
        $kategori = $this->request->getGet('kategori');
        $sub_kategori = $this->request->getGet('sub_kategori');
        $nama_barang = $this->request->getGet('nama_barang');
        $tahun = $this->request->getGet('tahun');

        $db = \Config\Database::connect();
        
        // Query dengan filter
        $query = $db->table('barang')
            ->select('barang.*, asset.kode_sub_kategori, sub_kategori.nama_sub_kategori, kategori.nama_kategori, 
                    pengguna.nama_pengguna, lokasi.nama_lokasi, YEAR(barang.tanggal_masuk) as tahun_perolehan')
            ->join('asset', 'asset.id = barang.id_asset', 'left')
            ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left');

        // Apply filters
        if (!empty($kategori)) {
            $query->where('kategori.nama_kategori', $kategori);
        }
        if (!empty($sub_kategori)) {
            $query->where('sub_kategori.nama_sub_kategori', $sub_kategori);
        }
        if (!empty($nama_barang)) {
            $query->like('barang.nama_barang', $nama_barang);
        }
        if (!empty($tahun)) {
            $query->where('YEAR(barang.tanggal_masuk)', $tahun);
        }

        $barang = $query->orderBy('kategori.nama_kategori, sub_kategori.nama_sub_kategori, barang.nama_barang')
                       ->get()
                       ->getResultArray();

        return $this->response->setJSON([
            'data' => $barang,
            'total' => count($barang)
        ]);
    }

    public function exportDataAssetPdf()
    {
        $kategori = $this->request->getGet('kategori');
        $sub_kategori = $this->request->getGet('sub_kategori');
        $nama_barang = $this->request->getGet('nama_barang');
        $tahun = $this->request->getGet('tahun');

        $db = \Config\Database::connect();
        
        // Query dengan filter
        $query = $db->table('barang')
            ->select('barang.*, asset.kode_sub_kategori, sub_kategori.nama_sub_kategori, kategori.nama_kategori, 
                    pengguna.nama_pengguna, lokasi.nama_lokasi, YEAR(barang.tanggal_masuk) as tahun_perolehan')
            ->join('asset', 'asset.id = barang.id_asset', 'left')
            ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left');

        // Apply filters
        if (!empty($kategori)) {
            $query->where('kategori.nama_kategori', $kategori);
        }
        if (!empty($sub_kategori)) {
            $query->where('sub_kategori.nama_sub_kategori', $sub_kategori);
        }
        if (!empty($nama_barang)) {
            $query->like('barang.nama_barang', $nama_barang);
        }
        if (!empty($tahun)) {
            $query->where('YEAR(barang.tanggal_masuk)', $tahun);
        }

        $barang = $query->orderBy('kategori.nama_kategori, sub_kategori.nama_sub_kategori, barang.nama_barang')
                       ->get()
                       ->getResultArray();

        $data = [
            'barang' => $barang,
            'kategori' => $kategori ?? 'Semua',
            'sub_kategori' => $sub_kategori ?? 'Semua',
            'nama_barang' => $nama_barang ?? 'Semua',
            'tahun' => $tahun ?? 'Semua'
        ];

        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan/dataasset/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan_Data_Asset.pdf', ['Attachment' => false]);
    }

    public function exportDataAssetExcel()
    {
        $kategori = $this->request->getGet('kategori');
        $sub_kategori = $this->request->getGet('sub_kategori');
        $nama_barang = $this->request->getGet('nama_barang');
        $tahun = $this->request->getGet('tahun');

        $db = \Config\Database::connect();
        
        // Query dengan filter
        $query = $db->table('barang')
            ->select('barang.*, asset.kode_sub_kategori, sub_kategori.nama_sub_kategori, kategori.nama_kategori, 
                    pengguna.nama_pengguna, lokasi.nama_lokasi, YEAR(barang.tanggal_masuk) as tahun_perolehan')
            ->join('asset', 'asset.id = barang.id_asset', 'left')
            ->join('sub_kategori', 'sub_kategori.kode_sub_kategori = asset.kode_sub_kategori', 'left')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori', 'left')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left');

        // Apply filters
        if (!empty($kategori)) {
            $query->where('kategori.nama_kategori', $kategori);
        }
        if (!empty($sub_kategori)) {
            $query->where('sub_kategori.nama_sub_kategori', $sub_kategori);
        }
        if (!empty($nama_barang)) {
            $query->like('barang.nama_barang', $nama_barang);
        }
        if (!empty($tahun)) {
            $query->where('YEAR(barang.tanggal_masuk)', $tahun);
        }

        $barang = $query->orderBy('kategori.nama_kategori, sub_kategori.nama_sub_kategori, barang.nama_barang')
                       ->get()
                       ->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Format Judul - Centered
        $sheet->mergeCells('A1:K1')->setCellValue('A1', 'LAPORAN DATA ASSET');
        $sheet->mergeCells('A2:K2')->setCellValue('A2', 'Dinas Komunikasi dan Informatika');
        $sheet->mergeCells('A3:K3')->setCellValue('A3', 'Kategori: ' . ($kategori ?: 'Semua') . ' | Sub Kategori: ' . ($sub_kategori ?: 'Semua') . ' | Nama Barang: ' . ($nama_barang ?: 'Semua') . ' | Tahun: ' . ($tahun ?: 'Semua'));

        // Style untuk judul - center alignment
        $sheet->getStyle('A1:A3')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(Font::UNDERLINE_SINGLE);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setItalic(true);

        // Header Tabel (di baris ke-5)
        $startRow = 5;
        $sheet->setCellValue("A{$startRow}", 'No');
        $sheet->setCellValue("B{$startRow}", 'Kode Unik');
        $sheet->setCellValue("C{$startRow}", 'Nama Barang');
        $sheet->setCellValue("D{$startRow}", 'Kategori');
        $sheet->setCellValue("E{$startRow}", 'Sub Kategori');
        $sheet->setCellValue("F{$startRow}", 'Harga Barang');
        $sheet->setCellValue("G{$startRow}", 'Tanggal Masuk');
        $sheet->setCellValue("H{$startRow}", 'Status');
        $sheet->setCellValue("I{$startRow}", 'Pengguna');
        $sheet->setCellValue("J{$startRow}", 'Lokasi');
        $sheet->setCellValue("K{$startRow}", 'Tahun Perolehan');

        // Gaya Header
        $sheet->getStyle("A{$startRow}:K{$startRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Isi Tabel
        $rowIndex = $startRow + 1;
        foreach ($barang as $index => $row) {
            $sheet->setCellValue("A{$rowIndex}", $index + 1);
            $sheet->setCellValue("B{$rowIndex}", $row['kode_unik'] ?? '-');
            $sheet->setCellValue("C{$rowIndex}", $row['nama_barang'] ?? '-');
            $sheet->setCellValue("D{$rowIndex}", $row['nama_kategori'] ?? '-');
            $sheet->setCellValue("E{$rowIndex}", $row['nama_sub_kategori'] ?? '-');
            $sheet->setCellValue("F{$rowIndex}", !empty($row['harga_barang']) ? 'Rp ' . number_format($row['harga_barang'], 0, ',', '.') : '-');
            $sheet->setCellValue("G{$rowIndex}", !empty($row['tanggal_masuk']) ? date('d-m-Y', strtotime($row['tanggal_masuk'])) : '-');
            $sheet->setCellValue("H{$rowIndex}", ucfirst($row['status'] ?? '-'));
            $sheet->setCellValue("I{$rowIndex}", $row['nama_pengguna'] ?? '-');
            $sheet->setCellValue("J{$rowIndex}", $row['nama_lokasi'] ?? '-');
            $sheet->setCellValue("K{$rowIndex}", $row['tahun_perolehan'] ?? '-');
            $rowIndex++;
        }

        // Auto-size kolom
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh tabel
        $sheet->getStyle("A{$startRow}:K" . ($rowIndex - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Footer tanda tangan - Posisi kanan bawah
        $rowIndex += 2;
        
        // Tanggal dan tempat - kanan
        $sheet->mergeCells("I{$rowIndex}:K{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Kebumen, ' . date('d') . ' ' . 
                ['','Januari','Februari','Maret','April','Mei','Juni','Juli',
                'Agustus','September','Oktober','November','Desember'][date('n')] . ' ' . date('Y'));
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex++;
        
        // Jabatan - kanan
        $sheet->mergeCells("I{$rowIndex}:K{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Mahasiswa Magang Dinas Komunikasi dan Informatika');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex += 4; // Space untuk tanda tangan
        
        // Nama - kanan dan bold
        $sheet->mergeCells("I{$rowIndex}:K{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Duta Adi Pamungkas');
        $sheet->getStyle("I{$rowIndex}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['bold' => true]
        ]);
        
        $rowIndex++;
        
        // Jabatan detail - kanan
        $sheet->mergeCells("I{$rowIndex}:K{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Mahasiswa Informatika');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex++;
        
        // NIP - kanan
        $sheet->mergeCells("I{$rowIndex}:K{$rowIndex}")
            ->setCellValue("I{$rowIndex}", '24060123140174');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Output file
        $filename = 'Laporan_Data_Asset_' . ($kategori ?: 'Semua') . '_' . ($sub_kategori ?: 'Semua') . '_' . ($nama_barang ?: 'Semua') . '_' . ($tahun ?: 'Semua') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

//////////////////////////////////////////////////////
////////////////laporan Kendaraan/////////////////////
//////////////////////////////////////////////////////
    public function kendaraan()
    {
        $model = model('App\Models\KendaraanModel');
        // Join pengguna dan lokasi
        $data['kendaraan'] = $model
            ->select('kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = kendaraan.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left')
            ->findAll();



        $data['menu'] = 'laporan_kendaraan';
        return view('laporan/kendaraan/kendaraan', $data);
    }

    public function cetakKendaraanPdf($tahun = null)
    {
        $model = model('App\Models\KendaraanModel');

        $jenis = $this->request->getGet('jenis'); // Ambil jenis kendaraan dari query string

        // Query join lokasi & pengguna
        $query = $model
            ->select('kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = kendaraan.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left');

        // Filter tahun
        if (!empty($tahun) && $tahun !== 'Semua') {
            $query->where('tahun_kendaraan', $tahun);
        }

        // Filter jenis kendaraan (motor/mobil/dll)
        if (!empty($jenis) && $jenis !== 'Semua') {
            $query->where('kendaraan.model_kendaraan', $jenis);
        }

        $kendaraan = $query->findAll();

        $data = [
            'kendaraan' => $kendaraan,
            'tahun' => $tahun ?? 'Semua',
            'jenis' => $jenis ?? 'Semua', // Kirim ke view jika perlu ditampilkan
        ];

        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan/kendaraan/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render();
        $dompdf->stream('Laporan_Kendaraan_Dinas.pdf', ['Attachment' => false]);
    }


    public function exportKendaraanExcel($tahun = null)
    {
        $model = new KendaraanModel();
        $builder = $model->select('kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
                        ->join('pengguna', 'pengguna.id = kendaraan.id_pengguna', 'left')
                        ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left');

        if ($tahun && $tahun !== 'Semua') {
            $builder->where('tahun_kendaraan', $tahun);
        }

        // Gunakan asObject agar bisa pakai ->field
        $kendaraan = $builder->asObject()->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul kolom sesuai yang di view
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Jenis');
        $sheet->setCellValue('C1', 'Nama Kendaraan');
        $sheet->setCellValue('D1', 'Merk/Type');
        $sheet->setCellValue('E1', 'Warna');
        $sheet->setCellValue('F1', 'Nomor Polisi');
        $sheet->setCellValue('G1', 'Tahun');
        $sheet->setCellValue('H1', 'Lokasi');
        $sheet->setCellValue('I1', 'Pemilik');

        // Gaya header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Isi data
        $rowNum = 2;
        foreach ($kendaraan as $i => $row) {
            $sheet->setCellValue('A' . $rowNum, $i + 1);
            $sheet->setCellValue('B' . $rowNum, ucwords($row->model_kendaraan ?? ''));
            $sheet->setCellValue('C' . $rowNum, $row->nama_kendaraan ?? '');
            $sheet->setCellValue('D' . $rowNum, $row->merk_kendaraan ?? '');
            $sheet->setCellValue('E' . $rowNum, $row->warna ?? '');
            $sheet->setCellValue('F' . $rowNum, $row->no_polisi ?? '');
            $sheet->setCellValue('G' . $rowNum, $row->tahun_kendaraan ?? '');
            $sheet->setCellValue('H' . $rowNum, $row->nama_lokasi ?? 'Tidak Ada');
            $sheet->setCellValue('I' . $rowNum, $row->nama_pengguna ?? 'Tidak Ada');
            $rowNum++;
        }

        // Auto lebar kolom
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh data
        $lastRow = $rowNum - 1;
        $sheet->getStyle("A1:I{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Nama file
        $filename = 'Laporan_Kendaraan_' . ($tahun ?? 'Semua') . '.xlsx';

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

//////////////////////////////////////////////////////
////////////////laporan pembelian/////////////////////
//////////////////////////////////////////////////////
    public function pembelian()
    {
        $riwayatPembelianModel = new RiwayatPembelianModel();
        $data['menu'] = 'laporan_pembelian';
        $data['pembelian'] = $riwayatPembelianModel->getRiwayatWithAsset();
        return view('laporan/pembelian/pembelian', $data);
    }

    public function cetakPembelianPdf($tahun = null)
    {
        // Ambil parameter dari URL dan query string
        $tahun = $tahun ?: $this->request->getGet('tahun');
        $kategori = $this->request->getGet('kategori');
        $subkategori = $this->request->getGet('subkategori');

        $riwayatPembelianModel = new RiwayatPembelianModel();
        $data['pembelian'] = $riwayatPembelianModel->getRiwayatWithAsset($tahun, $kategori, $subkategori);
        $data['tahun'] = $tahun ?? 'Semua';
        $data['kategori'] = $kategori ?? 'Semua';
        $data['subkategori'] = $subkategori ?? 'Semua';

        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan/pembelian/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();
        $dompdf->stream('Laporan_Pembelian_Asset.pdf', ['Attachment' => false]);
    }

    public function exportPembelianExcel($tahun = null)
    {
        // Ambil parameter dari URL dan query string
        $tahun = $tahun ?: $this->request->getGet('tahun');
        $kategori = $this->request->getGet('kategori');
        $subkategori = $this->request->getGet('subkategori');

        $riwayatPembelianModel = new \App\Models\RiwayatPembelianModel();
        // Pastikan semua parameter dikirim ke model
        $pembelian = $riwayatPembelianModel->getRiwayatWithAsset($tahun, $kategori, $subkategori);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->mergeCells('A1:H1')->setCellValue('A1', 'LAPORAN PEMBELIAN ASET');
        $sheet->mergeCells('A2:H2')->setCellValue('A2', 'Dinas Komunikasi dan Informatika');
        $sheet->mergeCells('A3:H3')->setCellValue('A3', 'Tahun: ' . ($tahun ?: 'Semua') . ' | Kategori: ' . ($kategori ?: 'Semua') . ' | Sub Kategori: ' . ($subkategori ?: 'Semua'));
        $sheet->getStyle('A1:A3')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setItalic(true);

        // Header Tabel
        $startRow = 5;
        $sheet->setCellValue("A{$startRow}", 'No');
        $sheet->setCellValue("B{$startRow}", 'Nama Barang');
        $sheet->setCellValue("C{$startRow}", 'Kategori');
        $sheet->setCellValue("D{$startRow}", 'Sub Kategori');
        $sheet->setCellValue("E{$startRow}", 'Tanggal Pembelian');
        $sheet->setCellValue("F{$startRow}", 'Jumlah');
        $sheet->setCellValue("G{$startRow}", 'Harga Satuan');
        $sheet->setCellValue("H{$startRow}", 'Total Harga');

        $sheet->getStyle("A{$startRow}:H{$startRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

        // Isi Tabel
        $rowIndex = $startRow + 1;
        foreach ($pembelian as $index => $row) {
            $sheet->setCellValue("A{$rowIndex}", $index + 1);
            $sheet->setCellValue("B{$rowIndex}", $row['nama_barang'] ?? '-');
            $sheet->setCellValue("C{$rowIndex}", $row['nama_kategori'] ?? '-');
            $sheet->setCellValue("D{$rowIndex}", $row['nama_sub_kategori'] ?? '-');
            $sheet->setCellValue("E{$rowIndex}", !empty($row['tanggal_pembelian']) ? date('d-m-Y', strtotime($row['tanggal_pembelian'])) : '-');
            $sheet->setCellValue("F{$rowIndex}", $row['jumlah'] ?? $row['jumlah_dibeli'] ?? '-');
            $sheet->setCellValue("G{$rowIndex}", $row['harga_satuan'] ?? '-');
            $sheet->setCellValue("H{$rowIndex}", $row['total_harga'] ?? '-');
            $rowIndex++;
        }

        // Auto-size kolom
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh tabel
        $sheet->getStyle("A{$startRow}:H" . ($rowIndex - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

        // Output file
        $filename = 'Laporan_Pembelian_Asset_' . ($tahun ?: 'Semua') . '_' . ($kategori ?: 'Semua') . '_' . ($subkategori ?: 'Semua') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

//////////////////////////////////////////////////////
////////////////laporan pemakaian/////////////////////
//////////////////////////////////////////////////////

    public function pemakaian()
    {
        $pemakaianModel = new PemakaianModel();
        $data['menu'] = 'laporan_pemakaian'; // Ubah dari 'pemakaian' ke 'laporan_pemakaian'
        $data['pemakaian'] = $pemakaianModel->getPemakaianWithAsset();
        return view('laporan/pemakaian/pemakaian', $data);
    }
    public function cetakPemakaianPdf()
    {
        $tahun = $this->request->getGet('tahun');
        $kategori = $this->request->getGet('kategori');
        $subKategori = $this->request->getGet('sub_kategori');

        $pemakaianModel = new PemakaianModel();
        $data['pemakaian'] = $pemakaianModel->getPemakaianWithAsset($tahun, $kategori, $subKategori);
        $data['tahun'] = $tahun ?? 'Semua';
        
        // Ambil nama kategori dan sub kategori jika kode disediakan
        if (!empty($kategori)) {
            $kategoriModel = new KategoriModel();
            $kategoriData = $kategoriModel->where('kode_kategori', $kategori)->first();
            $data['kategori'] = $kategoriData['nama_kategori'] ?? 'Semua';
        } else {
            $data['kategori'] = 'Semua';
        }
        
        if (!empty($subKategori)) {
            $subKategoriModel = new SubKategoriModel();
            $subKategoriData = $subKategoriModel->where('kode_sub_kategori', $subKategori)->first();
            $data['sub_kategori'] = $subKategoriData['nama_sub_kategori'] ?? 'Semua';
        } else {
            $data['sub_kategori'] = 'Semua';
        }

        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan/pemakaian/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();
        $dompdf->stream('Laporan_Pemakaian_Asset.pdf', ['Attachment' => false]);
    }

    public function exportPemakaianExcel()
    {
        $tahun = $this->request->getGet('tahun');
        $kategori = $this->request->getGet('kategori');
        $subKategori = $this->request->getGet('sub_kategori');

        $pemakaianModel = new \App\Models\PemakaianModel();
        $pemakaian = $pemakaianModel->getPemakaianWithAsset($tahun, $kategori, $subKategori);
        
        // Ambil nama kategori dan sub kategori
        $kategoriNama = 'Semua';
        $subKategoriNama = 'Semua';
        
        if (!empty($kategori)) {
            $kategoriModel = new \App\Models\KategoriModel();
            $kategoriData = $kategoriModel->where('kode_kategori', $kategori)->first();
            $kategoriNama = $kategoriData['nama_kategori'] ?? 'Semua';
        }
        
        if (!empty($subKategori)) {
            $subKategoriModel = new \App\Models\SubKategoriModel();
            $subKategoriData = $subKategoriModel->where('kode_sub_kategori', $subKategori)->first();
            $subKategoriNama = $subKategoriData['nama_sub_kategori'] ?? 'Semua';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Format Judul - Centered
        $sheet->mergeCells('A1:L1')->setCellValue('A1', 'LAPORAN PEMAKAIAN ASET');
        $sheet->mergeCells('A2:L2')->setCellValue('A2', 'Dinas Komunikasi dan Informatika');
        $sheet->mergeCells('A3:L3')->setCellValue('A3', 'Tahun: ' . ($tahun ?: 'Semua') . ' | Kategori: ' . $kategoriNama . ' | Sub Kategori: ' . $subKategoriNama);

        // Style untuk judul - center alignment
        $sheet->getStyle('A1:A3')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setUnderline(Font::UNDERLINE_SINGLE);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setItalic(true);

        // Header Tabel (di baris ke-5)
        $startRow = 5;
        $sheet->setCellValue("A{$startRow}", 'No');
        $sheet->setCellValue("B{$startRow}", 'Nama Barang');
        $sheet->setCellValue("C{$startRow}", 'Kategori');
        $sheet->setCellValue("D{$startRow}", 'Sub Kategori');
        $sheet->setCellValue("E{$startRow}", 'Lokasi');
        $sheet->setCellValue("F{$startRow}", 'Pengguna');
        $sheet->setCellValue("G{$startRow}", 'Jumlah');
        $sheet->setCellValue("H{$startRow}", 'Satuan');
        $sheet->setCellValue("I{$startRow}", 'Tanggal Mulai');
        $sheet->setCellValue("J{$startRow}", 'Tanggal Selesai');
        $sheet->setCellValue("K{$startRow}", 'Status');
        $sheet->setCellValue("L{$startRow}", 'Keterangan');

        // Gaya Header
        $sheet->getStyle("A{$startRow}:L{$startRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Isi Tabel
        $rowIndex = $startRow + 1;
        foreach ($pemakaian as $index => $row) {
            $sheet->setCellValue("A{$rowIndex}", $index + 1);
            $sheet->setCellValue("B{$rowIndex}", $row['nama_barang'] ?? '-');
            $sheet->setCellValue("C{$rowIndex}", $row['nama_kategori'] ?? '-');
            $sheet->setCellValue("D{$rowIndex}", $row['nama_sub_kategori'] ?? '-');
            $sheet->setCellValue("E{$rowIndex}", $row['nama_lokasi'] ?? '-');
            $sheet->setCellValue("F{$rowIndex}", $row['nama_pengguna'] ?? '-');
            $sheet->setCellValue("G{$rowIndex}", $row['jumlah_digunakan'] ?? '-');
            $sheet->setCellValue("H{$rowIndex}", $row['satuan_penggunaan'] ?? '-');
            $sheet->setCellValue("I{$rowIndex}", !empty($row['tanggal_mulai']) ? date('d-m-Y', strtotime($row['tanggal_mulai'])) : '-');
            $sheet->setCellValue("J{$rowIndex}", !empty($row['tanggal_selesai']) ? date('d-m-Y', strtotime($row['tanggal_selesai'])) : '-');
            $sheet->setCellValue("K{$rowIndex}", ucfirst($row['status'] ?? '-'));
            $sheet->setCellValue("L{$rowIndex}", $row['keterangan'] ?? '-');
            $rowIndex++;
        }

        // Auto-size kolom
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh tabel
        $sheet->getStyle("A{$startRow}:L" . ($rowIndex - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Footer tanda tangan - Posisi kanan bawah
        $rowIndex += 2;
        
        // Tanggal dan tempat - kanan
        $sheet->mergeCells("I{$rowIndex}:L{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Kebumen, ' . date('d') . ' ' . 
                ['','Januari','Februari','Maret','April','Mei','Juni','Juli',
                'Agustus','September','Oktober','November','Desember'][date('n')] . ' ' . date('Y'));
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex++;
        
        // Jabatan - kanan
        $sheet->mergeCells("I{$rowIndex}:L{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Mahasiswa Magang Dinas Komunikasi dan Informatika');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex += 4; // Space untuk tanda tangan
        
        // Nama - kanan dan bold
        $sheet->mergeCells("I{$rowIndex}:L{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Duta Adi Pamungkas');
        $sheet->getStyle("I{$rowIndex}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['bold' => true]
        ]);
        
        $rowIndex++;
        
        // Jabatan detail - kanan
        $sheet->mergeCells("I{$rowIndex}:L{$rowIndex}")
            ->setCellValue("I{$rowIndex}", 'Mahasiswa Informatika');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowIndex++;
        
        // NIP - kanan
        $sheet->mergeCells("I{$rowIndex}:L{$rowIndex}")
            ->setCellValue("I{$rowIndex}", '24060123140174');
        $sheet->getStyle("I{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Output file
        $filename = 'Laporan_Pemakaian_Asset_' . ($tahun ?: 'Semua') . '_' . ($kategori ?: 'Semua') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }



}
