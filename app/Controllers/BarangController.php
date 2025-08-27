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
        $pemakaianModel = new \App\Models\PemakaianModel();
        $assetModel = new \App\Models\AssetModel();
        $db = \Config\Database::connect();
    
        // Validasi input   
        $this->validate([
            'status' => 'required',
            'id_lokasi' => 'required',
            'id_pengguna' => 'required',
        ]);
    
        // Ambil data barang berdasarkan ID
        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori, asset.kode_kategori')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();
    
        if (!$barang) {
            return redirect()->to('/barangmodal')->with('error', 'Data barang tidak ditemukan.');
        }
    
        // Data yang akan diupdate
        $data = [
            'status' => $this->request->getPost('status'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
        ];
    
        // Update data barang
        $barangModel->update($id, $data);

        //  Sinkronisasi data pemakaian yang terkait dengan barang ini
        $this->syncPemakaianData($barang['id_asset'], $this->request->getPost('id_pengguna'), $this->request->getPost('id_lokasi'));

        // Update jumlah barang di tabel asset jika status berubah
        $statusBaru = $this->request->getPost('status');
        $statusLama = $barang['status'];
        
        if ($statusBaru !== $statusLama) {
            // Jika status berubah dari tersedia ke terpakai/rusak, kurangi jumlah
            if ($statusLama === 'tersedia' && in_array($statusBaru, ['terpakai', 'rusak', 'maintenance'])) {
                $assetModel->set('jumlah_barang', 'jumlah_barang - 1', false)
                          ->where('id', $barang['id_asset'])
                          ->update();
            }
            // Jika status berubah dari terpakai/rusak ke tersedia, tambah jumlah
            elseif (in_array($statusLama, ['terpakai', 'rusak', 'maintenance']) && $statusBaru === 'tersedia') {
                $assetModel->set('jumlah_barang', 'jumlah_barang + 1', false)
                          ->where('id', $barang['id_asset'])
                          ->update();
            }
        }
    
        // Redirect ke detailbarangmodal/{kode_sub_kategori}
        return redirect()->to('/detailbarangmodal/' . $barang['kode_sub_kategori'])->with('success', 'Data barang berhasil diperbarui dan data pemakaian tersinkronkan.');
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
        $pemakaianModel = new \App\Models\PemakaianModel();

        $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori, asset.nama_barang, asset.kode_barang')
        ->join('asset', 'asset.id = barang.id_asset')
        ->where('barang.id', $id)
        ->first();

        if (!$barang) {
            return redirect()->to('/barangmodal')->with('error', 'Data barang tidak ditemukan.');
        }

        // Ambil data riwayat dari tabel pemakaian untuk konsistensi
        $riwayat = $pemakaianModel
            ->select('pemakaian.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
            ->where('pemakaian.id_asset', $barang['id_asset'])
            ->orderBy('pemakaian.tanggal_mulai', 'DESC')
            ->findAll();

        $data = [
            'menu' => 'barangmodal',
            'barang' => $barang,
            'riwayat' => $riwayat,
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
        $pemakaianModel = new \App\Models\PemakaianModel();
        $assetModel = new \App\Models\AssetModel();
        $db = \Config\Database::connect();
    
        // Validasi input   
        $this->validate([
            'status' => 'required',
            'id_lokasi' => 'required',
            'id_pengguna' => 'required',
        ]);
    
        // Ambil data barang berdasarkan ID
        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori, asset.kode_kategori')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();
    
        if (!$barang) {
            return redirect()->to('/baranghabispakai')->with('error', 'Data barang tidak ditemukan.');
        }
    
        // Data yang akan diupdate
        $data = [
            'status' => $this->request->getPost('status'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
        ];
    
        // Update data barang
        $barangModel->update($id, $data);

        // ✅ Sinkronisasi data pemakaian yang terkait dengan barang ini
        $this->syncPemakaianData($barang['id_asset'], $this->request->getPost('id_pengguna'), $this->request->getPost('id_lokasi'));

        // Update jumlah barang di tabel asset jika status berubah
        $statusBaru = $this->request->getPost('status');
        $statusLama = $barang['status'];
        
        if ($statusBaru !== $statusLama) {
            // Jika status berubah dari tersedia ke terpakai/habis terpakai, kurangi jumlah
            if ($statusLama === 'tersedia' && in_array($statusBaru, ['terpakai', 'habis terpakai'])) {
                $assetModel->set('jumlah_barang', 'jumlah_barang - 1', false)
                          ->where('id', $barang['id_asset'])
                          ->update();
            }
            // Jika status berubah dari terpakai/habis terpakai ke tersedia, tambah jumlah
            elseif (in_array($statusLama, ['terpakai', 'habis terpakai']) && $statusBaru === 'tersedia') {
                $assetModel->set('jumlah_barang', 'jumlah_barang + 1', false)
                          ->where('id', $barang['id_asset'])
                          ->update();
            }
        }
    
        // Redirect ke detailbaranghabispakai/{kode_sub_kategori}
        return redirect()->to('/detailbaranghabispakai/' . $barang['kode_sub_kategori'])->with('success', 'Data barang berhasil diperbarui dan data pemakaian tersinkronkan.');
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
        $pemakaianModel = new \App\Models\PemakaianModel();

        $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori, asset.nama_barang, asset.kode_barang')
        ->join('asset', 'asset.id = barang.id_asset')
        ->where('barang.id', $id)
        ->first();

        if (!$barang) {
            return redirect()->to('/baranghabispakai')->with('error', 'Data barang tidak ditemukan.');
        }

        // Ambil data riwayat dari tabel pemakaian untuk konsistensi
        $riwayat = $pemakaianModel
            ->select('pemakaian.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
            ->where('pemakaian.id_asset', $barang['id_asset'])
            ->orderBy('pemakaian.tanggal_mulai', 'DESC')
            ->findAll();

        $data = [
            'menu' => 'baranghabispakai',
            'barang' => $barang,
            'riwayat' => $riwayat,
        ];

        return view('Dataasset/Baranghabispakai/riwayatbaranghabispakai', $data);
    }



    // pembelian 
   
    // ✅ Method untuk sinkronisasi data pemakaian (hanya update, tidak tambah data baru)
    private function syncPemakaianData($id_asset, $id_pengguna, $id_lokasi)
    {
        $db = \Config\Database::connect();

        // Update semua record pemakaian yang terkait dengan asset ini
        // yang masih aktif (tanggal_selesai null atau tanggal_selesai > hari ini)
        $updated = $db->table('pemakaian')
            ->where('id_asset', $id_asset)
            ->where('(tanggal_selesai IS NULL OR tanggal_selesai >= CURDATE())', null, false)
            ->update([
                'id_pengguna' => $id_pengguna,
                'id_lokasi' => $id_lokasi
            ]);

        // Log untuk debugging (opsional)
        if ($updated > 0) {
            log_message('info', "Sinkronisasi pemakaian: {$updated} record diupdate untuk asset ID {$id_asset}");
        }
    }
}