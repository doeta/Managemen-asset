<?php

namespace App\Controllers;

use App\Models\SubKategoriModel;
use App\Models\BarangModel;
use App\Models\RiwayatModel;
use App\Controllers\BaseController;


class BarangController extends BaseController
{
    public function barangModal()
    {
        $subKategoriModel = new SubKategoriModel();

        // Ambil data sub kategori untuk Barang Modal
        $data['sub_kategori'] = $subKategoriModel->getSubKategoriWithKategori('Barang Modal');
        $data['menu'] = 'barangmodal';

        return view('Dataasset/Barangmodal/barangmodal', $data);
    }

    public function detailBarangModal($kode_sub_kategori)
    {
        $barangModel = new BarangModel();
        
        // Ambil data barang dengan pagination
        $barangWithAssetLocationPengguna = $barangModel->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, pengguna.nama_pengguna, lokasi.nama_lokasi')
                                       ->join('asset', 'asset.id = barang.id_asset')
                                       ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
                                       ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
                                       ->where('asset.kode_sub_kategori', $kode_sub_kategori)
                                       ->orderBy('asset.nama_barang', 'ASC')
                                       ->findAll();
                                       
                                    
        

        $data = [
            'menu' => 'barangmodal',
            'barang' => $barangWithAssetLocationPengguna,
            'kode_sub_kategori' => $kode_sub_kategori,
            'pager' => $barangModel->pager,
           
        ];

        return view('Dataasset/Barangmodal/detailbarangmodal', $data);
    }

    public function editBarangModal($id) 
{
    $barangModel = new BarangModel();
    $lokasiModel = new \App\Models\LokasiModel();
    $penggunaModel = new \App\Models\PenggunaModel();

    $barang = $barangModel->select('barang.*, asset.kode_sub_kategori')
                          ->join('asset', 'asset.id = barang.id_asset')
                          ->where('barang.id', $id)
                          ->first();

    if (!$barang) {
        return redirect()->to('/barangmodal')->with('error', 'Data tidak ditemukan.');
    }

    $data = [
        'menu' => 'barangmodal',
        'barang' => $barang,
        'lokasi' => $lokasiModel->findAll(),
        'pengguna' => $penggunaModel->findAll(),
    ];
    
    return view('Dataasset/Barangmodal/editbarangmodal', $data);
}
    public function updateBarangModal($id)
    {
        $barangModel = new BarangModel();
    
        // Validasi input   
        $this->validate([
            'status' => 'required',
            'id_lokasi' => 'required',
            'id_pengguna' => 'required',

        ]);
    
        // Ambil data barang berdasarkan ID
        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();
    
        if (!$barang) {
            return redirect()->to('/barangmodal')->with('error', 'Data barang tidak ditemukan.');
        }
    
        // Data yang akan diupdate
        $data = [
            'menu' => 'barangmodal',
            'status' => $this->request->getPost('status'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
        ];
    
        $barangModel->update($id, $data);

        // Inisialisasi RiwayatModel sebelum digunakan
        $riwayatModel = new RiwayatModel();

        // Simpan data ke tabel riwayat
        $riwayat =  [
            'id_barang' => $id,
            'id_asset' => $barang['id_asset'], // Ambil dari data barang
            'jumlah_digunakan' => $this->request->getPost('jumlah_digunakan'),
            'satuan_penggunaan' => $this->request->getPost('satuan_penggunaan'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $validationRules = [
            'jumlah_digunakan' => 'required|numeric',
            'satuan_penggunaan' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            // 'tanggal_selesai' => 'permit_empty|valid_date', // Uncomment if needed
            // 'keterangan' => 'permit_empty', // Optional
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon lengkapi semua data.');
        }

        $riwayatModel->insert($riwayat);
    
        // Redirect ke detailbarangmodal/{kode_sub_kategori}
        return redirect()->to('/detailbarangmodal/' . $barang['kode_sub_kategori'])->with('success', 'Data barang berhasil diperbarui.');
    }

    public function deleteBarangModal($id)
    {
        $barangModel = new BarangModel();

        // Hapus data barang berdasarkan ID
        if ($barangModel->delete($id)) {
            return redirect()->to('/barangmodal')->with('success', 'Data barang berhasil dihapus.');
        } else {
            return redirect()->to('/barangmodal')->with('error', 'Gagal menghapus data barang.');
        }
    }   

    public function riwayatBarangModal($id)
    {
        $barangModel = new BarangModel();
        $riwayatModel = new RiwayatModel();

        $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori')
        ->join('asset', 'asset.id = barang.id_asset')
        ->where('barang.id', $id)
        ->first();

        // Ambil data barang berdasarkan ID
        // $barang = $barangModel->find($id);

        if (!$barang) {
            return redirect()->to('/barangmodal')->with('error', 'Data barang tidak ditemukan.');
        }

        // Ambil data riwayat dari RiwayatModel
        $riwayat = $riwayatModel->getRiwayatWithBarang($id);

        $data = [
            'menu' => 'barangmodal',
            'riwayat' => $riwayat,
            'barang'  => $barang,
        ];

        return view('Dataasset/Barangmodal/riwayatbarangmodal', $data);
    }

     

    // Barang Habis Pakai
    public function barangHabisPakai()
    {
        $subKategoriModel = new SubKategoriModel();
        $data['menu'] = 'baranghabispakai';

        // Ambil data sub kategori untuk Barang Habis Pakai
        $data['sub_kategori'] = $subKategoriModel->getSubKategoriWithKategori('Habis Pakai');

        return view('Dataasset/Baranghabispakai/baranghabispakai', $data);
    }

    public function detailBarangHabisPakai($kode_sub_kategori)
    {
        $barangModel = new BarangModel();

        // Ambil data barang dengan pagination
        $barangWithAssetLocationPengguna = $barangModel->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, pengguna.nama_pengguna, lokasi.nama_lokasi')
                                       ->join('asset', 'asset.id = barang.id_asset')
                                       ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
                                       ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
                                       ->where('asset.kode_sub_kategori', $kode_sub_kategori)
                                       ->orderBy('asset.nama_barang', 'ASC')
                                       ->findAll();

        $data = [
            'menu' => 'baranghabispakai',
            'barang' => $barangWithAssetLocationPengguna,
            'kode_sub_kategori' => $kode_sub_kategori,
            'pager' => $barangModel->pager

        ];

        return view('Dataasset/Baranghabispakai/detailbaranghabispakai', $data);
    }

    public function editBarangHabisPakai($id) 
    {
        $barangModel = new BarangModel();
        $lokasiModel = new \App\Models\LokasiModel();
        $penggunaModel = new \App\Models\PenggunaModel();

        // Ambil data barang berdasarkan ID
        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();

        // Ambil data barang , lokasi dan pengguna
        $data = [
            'menu' => 'baranghabispakai',
            'barang' => $barang,
            'lokasi' => $lokasiModel->findAll(),
            'pengguna' => $penggunaModel->findAll(),
        ];
        return view('Dataasset/Baranghabispakai/editbaranghabispakai', $data);
    }

    public function updateBarangHabisPakai($id)
{
    $barangModel = new BarangModel();
    $riwayatModel = new \App\Models\RiwayatModel(); // pastikan model ini sudah dibuat

    // Validasi input
    $this->validate([
        'status' => 'required',
        'id_lokasi' => 'required',
        'id_pengguna' => 'required',
    ]);

    // Ambil data barang dan sub kategori
    $barang = $barangModel->select('barang.*, asset.kode_sub_kategori')
                          ->join('asset', 'asset.id = barang.id_asset')
                          ->where('barang.id', $id)
                          ->first();

    if (!$barang) {
        return redirect()->to('/baranghabispakai')->with('error', 'Data barang tidak ditemukan.');
    }

    // Update data barang
    $data = [
        'menu' => 'baranghabispakai',
        'status' => $this->request->getPost('status'),
        'id_lokasi' => $this->request->getPost('id_lokasi'),
        'id_pengguna' => $this->request->getPost('id_pengguna'),
    ];

    $barangModel->update($id, $data);

// Simpan data ke tabel riwayat
$riwayat = [
    'id_barang' => $id,
    'id_asset' => $barang['id_asset'], // Ambil dari data barang
    'jumlah_digunakan' => $this->request->getPost('jumlah_digunakan'),
    'satuan_penggunaan' => $this->request->getPost('satuan_penggunaan'),
    'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
    'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
    'keterangan' => $this->request->getPost('keterangan'),
];

$validationRules = [
    'jumlah_digunakan' => 'required|numeric',
    'satuan_penggunaan' => 'required',
    'tanggal_mulai' => 'required|valid_date',
    // 'tanggal_selesai' => 'permit_empty|valid_date', // Uncomment if needed
    // 'keterangan' => 'permit_empty', // Optional
];

if (!$this->validate($validationRules)) {
    return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon lengkapi semua data.');
}

    $riwayatModel->insert($riwayat);

    // Redirect kembali ke detail
    return redirect()->to('/detailbaranghabispakai/' . $barang['kode_sub_kategori'])->with('success', 'Data berhasil diperbarui dan riwayat disimpan.');
}


    public function deleteBarangHabisPakai($id)
    {
        $barangModel = new BarangModel();

        // Hapus data barang berdasarkan ID
        if ($barangModel->delete($id)) {
            return redirect()->to('/baranghabispakai')->with('success', 'Data barang berhasil dihapus.');
        } else {
            return redirect()->to('/baranghabispakai')->with('error', 'Gagal menghapus data barang.');
        }
    }

    public function riwayatBarangHabisPakai($id)
    {
    $barangModel = new BarangModel();
    $riwayatModel = new RiwayatModel();



    // Ambil data barang berdasarkan ID
    $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori')
        ->join('asset', 'asset.id = barang.id_asset')
        ->where('barang.id', $id)
        ->first();

    if (!$barang) {
        return redirect()->to('/baranghabispakai')->with('error', 'Data barang tidak ditemukan.');
    }

    $riwayat = $riwayatModel->getRiwayatWithBarang($id);
    

    $data = [
        'menu' => 'baranghabispakai',
        'riwayat' => $riwayat,
        'barang'  => $barang, // Sekarang sudah ada kode_sub_kategori
    ];

    return view('Dataasset/Baranghabispakai/riwayatbaranghabispakai', $data);
    }



    // pembelian 
   
}