<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemakaianModel;
use App\Models\RiwayatPembelianModel;
use App\Models\KendaraanModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;


class LaporanController extends BaseController
{
    public function dataasset()
    {
        $db = \Config\Database::connect();

        // Barang Modal
        $modalQuery = $db->table('sub_kategori_barang_modal sk')
            ->select('sk.kode_sub_kategori, sk.nama_sub_kategori, k.nama_kategori, COUNT(bm.id) as jumlah_barang')
            ->join('kategori_barang_modal k', 'k.id = sk.kategori_id', 'left')
            ->join('barang_modal bm', 'bm.sub_kategori_id = sk.id', 'left')
            ->groupBy('sk.id')
            ->get()
            ->getResultArray();

        foreach ($modalQuery as &$item) {
            $item['jenis_barang'] = 'Modal';
        }

        // Barang Habis Pakai
        $habisQuery = $db->table('sub_kategori_barang_habis sk')
            ->select('sk.kode_sub_kategori, sk.nama_sub_kategori, k.nama_kategori, COUNT(bh.id) as jumlah_barang')
            ->join('kategori_barang_habis k', 'k.id = sk.kategori_id', 'left')
            ->join('barang_habis_pakai bh', 'bh.sub_kategori_id = sk.id', 'left')
            ->groupBy('sk.id')
            ->get()
            ->getResultArray();

        foreach ($habisQuery as &$item) {
            $item['jenis_barang'] = 'Habis Pakai';
        }

        $data['menu'] = 'laporan_dataasset';
        $data['barang'] = array_merge($modalQuery, $habisQuery);

        return view('laporan/dataasset', $data);
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

        $pemakaianModel = new PemakaianModel();
        $data['pemakaian'] = $pemakaianModel->getPemakaianWithAsset($tahun, $kategori);
        $data['tahun'] = $tahun ?? 'Semua';
        $data['kategori'] = $kategori ?? 'Semua';

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

        $pemakaianModel = new \App\Models\PemakaianModel();
        $pemakaian = $pemakaianModel->getPemakaianWithAsset($tahun, $kategori);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Format Judul - Centered
        $sheet->mergeCells('A1:L1')->setCellValue('A1', 'LAPORAN PEMAKAIAN ASET');
        $sheet->mergeCells('A2:L2')->setCellValue('A2', 'Dinas Komunikasi dan Informatika');
        $sheet->mergeCells('A3:L3')->setCellValue('A3', 'Tahun: ' . ($tahun ?: 'Semua') . ' | Kategori: ' . ($kategori ?: 'Semua'));

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
