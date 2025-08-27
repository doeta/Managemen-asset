<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\SubKategoriModel;
use App\Models\BarangModel;
use App\Models\PemakaianModel;
use App\Models\AssetModel;

class DataAssetsController extends BaseController
{
    public function index()
    {
        $subKategoriModel = new SubKategoriModel();
        $kategoriModel = new \App\Models\KategoriModel();
        
        // Ambil parameter filter dari GET request
        $selected_kategori = $this->request->getGet('kategori');
        
        // Ambil semua kategori untuk dropdown filter
        $data['kategori'] = $kategoriModel->findAll();
        
        // Ambil data sub kategori berdasarkan filter
        if (!empty($selected_kategori)) {
            // Jika ada filter kategori yang dipilih, gunakan filter tersebut
            $data['sub_kategori'] = $subKategoriModel->getSubKategoriWithKategori($selected_kategori);
        } else {
            // Jika tidak ada filter atau "Semua Kategori" dipilih, tampilkan SEMUA sub kategori
            $data['sub_kategori'] = $subKategoriModel->getSubKategoriWithKategori();
        }
        
        $data['menu'] = 'dataassets';
        $data['selected_kategori'] = $selected_kategori;
        
        return view('Dataassets/dataassets', $data);
    }

    public function detailSubKategori($kode_sub_kategori)
    {
        $barangModel = new \App\Models\BarangModel();
        $subKategoriModel = new \App\Models\SubKategoriModel();
        $assetModel = new \App\Models\AssetModel();
        $pemakaianModel = new \App\Models\PemakaianModel();

        // Ambil filter dari GET request
        $namaBarang = $this->request->getGet('nama_barang');

        // Query barang berdasarkan sub kategori + filter nama_barang jika ada
        $query = $barangModel
            ->select('barang.*, asset.kode_barang, asset.nama_barang, asset.kode_kategori, asset.kode_sub_kategori, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('asset', 'asset.id = barang.id_asset')
            ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
            ->where('asset.kode_sub_kategori', $kode_sub_kategori);

        if (!empty($namaBarang)) {
            $query->where('asset.nama_barang', $namaBarang);
        }

        $barangList = $query->orderBy('asset.nama_barang', 'ASC')->findAll();

        // Ambil daftar nama barang unik untuk dropdown filter (tetap hanya berdasarkan sub kategori)
        $nama_barang_list = $barangModel
            ->select('DISTINCT(asset.nama_barang)')
            ->join('asset', 'asset.id = barang.id_asset')
            ->where('asset.kode_sub_kategori', $kode_sub_kategori)
            ->orderBy('asset.nama_barang', 'ASC')
            ->findAll();

        // Ambil info sub kategori + kategori dari salah satu barang (mengikuti filter nama_barang)
        $sub_kategori = $subKategoriModel
            ->select('sub_kategori.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.kode_kategori = sub_kategori.kode_kategori')
            ->where('sub_kategori.kode_sub_kategori', $kode_sub_kategori)
            ->first();

        // Ambil statistik status berdasarkan nama_barang (jika difilter), bukan seluruh sub kategori
        $statistikStatusQuery = $barangModel
            ->select('barang.status, COUNT(*) as jumlah')
            ->join('asset', 'asset.id = barang.id_asset')
            ->where('asset.kode_sub_kategori', $kode_sub_kategori);

        if (!empty($namaBarang)) {
            $statistikStatusQuery->where('asset.nama_barang', $namaBarang);
        }

        $statistikStatus = $statistikStatusQuery->groupBy('barang.status')->findAll();

        if (!empty($namaBarang)) {
            $barangSample = !empty($barangList) ? $barangList[0] : null;
        } else {
            $barangSample = null; // Tidak menampilkan nama barang jika tidak difilter
        }

        $data = [
            'menu' => 'dataassets',
            'barang' => $barangList,
            'nama_barang_list' => $nama_barang_list,
            'sub_kategori' => $sub_kategori,
            'kode_sub_kategori' => $kode_sub_kategori,
            'statistikStatus' => $statistikStatus,
            'barangSample' => $barangSample,
        ];

        return view('Dataassets/detailassets', $data);
    }

    
    public function edit($id) 
    {
        $barangModel = new BarangModel();
        $lokasiModel = new \App\Models\LokasiModel();
        $penggunaModel = new \App\Models\PenggunaModel();

        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori, asset.kode_kategori, asset.nama_barang')
                          ->join('asset', 'asset.id = barang.id_asset')
                          ->where('barang.id', $id)
                          ->first();

        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data tidak ditemukan.');
        }

        //PROTEKSI: Cek apakah barang sudah habis terpakai
        if (isset($barang['status']) && strtolower(trim($barang['status'])) === 'habis terpakai') {
            return redirect()->back()
                ->with('error', 'Barang sudah habis terpakai dan tidak dapat diedit!')
                ->with('warning', 'Status: Habis Terpakai - Hanya bisa dilihat detail, riwayat, atau dihapus.');
        }

        //PROTEKSI TAMBAHAN: Cek berdasarkan kategori barang habis pakai
        if ($this->isHabisTerpakaiBarang($barang['kode_kategori']) && 
            isset($barang['status']) && 
            in_array(strtolower(trim($barang['status'])), ['habis terpakai', 'habis_terpakai'])) {
            
            return redirect()->back()
                ->with('error', 'Barang kategori habis pakai yang sudah terpakai tidak dapat diedit!')
                ->with('info', 'Untuk barang habis pakai yang sudah digunakan, silakan tambah barang baru atau lihat riwayat pemakaian.');
        }

        $data = [
            'menu' => 'dataassets',
            'barang' => $barang,
            'lokasi' => $lokasiModel->findAll(),
            'pengguna' => $penggunaModel->findAll(),
            'is_editable' => $this->isBarangEditable($barang),
            'status_info' => $this->getStatusInfo($barang['status'] ?? 'tersedia'),
        ];
        
        return view('Dataassets/edit', $data);
    }

    public function update($id)
    {
        $barangModel = new BarangModel();
        $pemakaianModel = new PemakaianModel();
        $assetModel = new AssetModel();
    
        // Ambil data barang berdasarkan ID untuk validasi
        $barang = $barangModel->select('barang.*, asset.kode_sub_kategori, asset.kode_kategori, asset.nama_barang')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();
    
        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data barang tidak ditemukan.');
        }

        //PROTEKSI UPDATE: Double check sebelum update
        if (isset($barang['status']) && strtolower(trim($barang['status'])) === 'habis terpakai') {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengupdate! Barang sudah habis terpakai.')
                ->with('warning', 'Status barang yang sudah habis terpakai tidak dapat diubah.');
        }

        // Validasi input   
        $this->validate([
            'status' => 'required',
            'id_lokasi' => 'required',
            'id_pengguna' => 'required',
        ]);

        $statusBaru = $this->request->getPost('status');
        $statusLama = $barang['status'];
        
        // VALIDASI STATUS BARU: Jika mengubah ke habis terpakai, pastikan ini barang habis pakai
        if (strtolower(trim($statusBaru)) === 'habis terpakai' && 
            !$this->isHabisTerpakaiBarang($barang['kode_kategori'])) {
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Status "Habis Terpakai" hanya dapat diterapkan pada kategori barang habis pakai!')
                ->with('info', 'Kategori: ' . $barang['kode_kategori'] . ' bukan kategori habis pakai.');
        }
        
        // Data yang akan diupdate
        $data = [
            'status' => $statusBaru,
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
        ];

        // Tambah timestamp jika status berubah ke habis terpakai
        if (strtolower(trim($statusBaru)) === 'habis terpakai' && 
            strtolower(trim($statusLama)) !== 'habis terpakai') {
            $data['tanggal_habis'] = date('Y-m-d H:i:s');
        }
    
        // Cek apakah pengguna berubah
        $id_pengguna_baru = $this->request->getPost('id_pengguna');
        $tanggal_perubahan = $this->request->getPost('tanggal_perubahan');
        if ($id_pengguna_baru != $barang['id_pengguna']) {
            // Catat riwayat pemilik
            $riwayatModel = new \App\Models\RiwayatModel();
            $riwayatModel->insert([
                'id_asset' => $barang['id_asset'],
                'id_barang' => $barang['id'],
                'id_pengguna' => $id_pengguna_baru,
                'tanggal_mulai' => $tanggal_perubahan ? date('Y-m-d', strtotime($tanggal_perubahan)) : date('Y-m-d'),
                'keterangan' => 'Perubahan pemilik barang',
            ]);
        }

        // Update data barang
        $barangModel->update($id, $data);

        // Update langsung data pemakaian yang terkait dengan asset ini
        $db = \Config\Database::connect();
        $db->table('pemakaian')
            ->where('id_asset', $barang['id_asset'])
            ->update([
                'id_pengguna' => $id_pengguna_baru,
                'id_lokasi' => $this->request->getPost('id_lokasi')
            ]);

    // Ambil jumlah yang dipakai dari input (pastikan field form bernama jumlah_digunakan)
    $jumlah_digunakan = (int) $this->request->getPost('jumlah_digunakan');
    // Update jumlah barang di tabel asset berdasarkan jenis barang dan jumlah pemakaian
    $this->updateAssetQuantity($barang, $statusLama, $statusBaru, $jumlah_digunakan);

        // Redirect ke detail
        return redirect()->to('/dataassets/detail/' . $barang['kode_sub_kategori'])
            ->with('success', 'Data barang berhasil diperbarui, riwayat pemilik tercatat, dan data pemakaian tersinkronkan.');
    }

    public function delete($id)
    {
        $barangModel = new BarangModel();

        // Ambil data barang untuk logging
        $barang = $barangModel->select('barang.*, asset.nama_barang')
                              ->join('asset', 'asset.id = barang.id_asset')
                              ->where('barang.id', $id)
                              ->first();

        // Hapus data barang berdasarkan ID
        if ($barangModel->delete($id)) {
            $message = 'Data barang berhasil dihapus.';
            
            // Tambahan info jika barang habis terpakai
            if ($barang && isset($barang['status']) && strtolower(trim($barang['status'])) === 'habis terpakai') {
                $message .= ' (Barang habis terpakai: ' . $barang['nama_barang'] . ')';
            }
            
            return redirect()->to('/dataassets')->with('success', $message);
        } else {
            return redirect()->to('/dataassets')->with('error', 'Gagal menghapus data barang.');
        }
    }   

    public function detail($id)
    {
        $barangModel = new BarangModel();
        $pemakaianModel = new PemakaianModel();
        $assetModel = new AssetModel();

        $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori, asset.nama_barang, asset.kode_barang, asset.kode_kategori, asset.jumlah_barang, pengguna.nama_pengguna, lokasi.nama_lokasi')
        ->join('asset', 'asset.id = barang.id_asset')
        ->join('pengguna', 'pengguna.id = barang.id_pengguna', 'left')
        ->join('lokasi', 'lokasi.id = barang.id_lokasi', 'left')
        ->where('barang.id', $id)
        ->first();

        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data barang tidak ditemukan.');
        }

        // Ambil data riwayat dari tabel pemakaian untuk asset ini
        $riwayat = $pemakaianModel
            ->select('pemakaian.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = pemakaian.id_lokasi', 'left')
            ->where('pemakaian.id_asset', $barang['id_asset'])
            ->orderBy('pemakaian.tanggal_mulai', 'DESC')
            ->findAll();

        // Ambil riwayat pengguna dari RiwayatModel berdasarkan id_barang
        $riwayatPengguna = [];
        if (isset($barang['id'])) {
            $riwayatModel = new \App\Models\RiwayatModel();
            $riwayatPengguna = $riwayatModel->getRiwayatWithBarang($barang['id']);
        }

        // Ambil statistik status untuk barang-barang dalam asset yang sama
        $statistikStatus = $barangModel
            ->select('status, COUNT(*) as jumlah')
            ->where('id_asset', $barang['id_asset'])
            ->groupBy('status')
            ->findAll();

        // Ambil riwayat perubahan jumlah (simulasi dari pemakaian)
        $riwayatJumlah = [];
        $jumlahAwal = $barang['jumlah_barang'] ?? 0;
        
        foreach ($riwayat as $pemakaian) {
            $jumlahAwal += $pemakaian['jumlah_digunakan']; // Kembalikan ke jumlah sebelum pemakaian
            $riwayatJumlah[] = [
                'tanggal' => $pemakaian['tanggal_mulai'],
                'jenis' => 'Pemakaian',
                'jumlah_sebelum' => $jumlahAwal,
                'jumlah_digunakan' => $pemakaian['jumlah_digunakan'],
                'jumlah_sesudah' => $jumlahAwal - $pemakaian['jumlah_digunakan'],
                'keterangan' => $pemakaian['keterangan'],
                'pengguna' => $pemakaian['nama_pengguna'],
                'lokasi' => $pemakaian['nama_lokasi']
            ];
            $jumlahAwal -= $pemakaian['jumlah_digunakan'];
        }

        $data = [
            'menu' => 'dataassets',
            'barang' => $barang,
            'riwayat' => $riwayat,
            'statistikStatus' => $statistikStatus,
            'riwayatJumlah' => array_reverse($riwayatJumlah), // Urutkan dari lama ke baru
            'is_editable' => $this->isBarangEditable($barang),
            'status_info' => $this->getStatusInfo($barang['status'] ?? 'tersedia'),
            'is_habis_terpakai_kategori' => $this->isHabisTerpakaiBarang($barang['kode_kategori']),
            'riwayatPengguna' => $riwayatPengguna,
        ];

        return view('Dataassets/detailbarang', $data);
    }

    //  METHOD BARU: Cek apakah barang bisa diedit
    private function isBarangEditable($barang)
    {
        // Tidak bisa edit jika status habis terpakai
        if (isset($barang['status']) && 
            in_array(strtolower(trim($barang['status'])), ['habis terpakai', 'habis_terpakai'])) {
            return false;
        }

        // Untuk barang habis pakai, jika sudah digunakan tidak bisa diedit
        if ($this->isHabisTerpakaiBarang($barang['kode_kategori']) && 
            isset($barang['status']) && 
            strtolower(trim($barang['status'])) !== 'tersedia') {
            return false;
        }

        return true;
    }

    // METHOD BARU: Get status info untuk tampilan
    private function getStatusInfo($status)
    {
        $statusInfo = [
            'tersedia' => [
                'class' => 'success',
                'icon' => 'check-circle',
                'description' => 'Barang tersedia dan siap digunakan',
                'editable' => true
            ],
            'terpakai' => [
                'class' => 'warning',
                'icon' => 'clock',
                'description' => 'Barang sedang digunakan',
                'editable' => true
            ],
            'dipakai' => [
                'class' => 'warning',
                'icon' => 'clock',
                'description' => 'Barang sedang digunakan',
                'editable' => true
            ],
            'habis terpakai' => [
                'class' => 'danger',
                'icon' => 'times-circle',
                'description' => 'Barang sudah habis terpakai dan tidak dapat diedit',
                'editable' => false
            ],
            'habis_terpakai' => [
                'class' => 'danger',
                'icon' => 'times-circle',
                'description' => 'Barang sudah habis terpakai dan tidak dapat diedit',
                'editable' => false
            ],
            'rusak' => [
                'class' => 'secondary',
                'icon' => 'tools',
                'description' => 'Barang rusak dan perlu perbaikan',
                'editable' => true
            ],
            'maintenance' => [
                'class' => 'info',
                'icon' => 'wrench',
                'description' => 'Barang dalam maintenance',
                'editable' => true
            ]
        ];

        return $statusInfo[strtolower($status)] ?? $statusInfo['tersedia'];
    }

    //  METHOD DIPERBAIKI: Update untuk otomatis set habis terpakai
    private function updateAssetQuantity($barang, $statusLama, $statusBaru, $jumlah_digunakan = 1)
    {
        $assetModel = new AssetModel();
        
        // Cek apakah barang ini habis terpakai atau dapat diganti kepemilikan
        $isHabisTerpakai = $this->isHabisTerpakaiBarang($barang['kode_kategori']);
        
        if ($statusBaru !== $statusLama) {
            if ($isHabisTerpakai) {
                // Untuk barang habis terpakai
                if ($statusLama === 'tersedia' && 
                    in_array(strtolower(trim($statusBaru)), ['habis terpakai', 'habis_terpakai'])) {
                    // Kurangi stok sesuai jumlah yang dipakai
                    $assetModel->set('jumlah_barang', 'jumlah_barang - ' . (int)$jumlah_digunakan, false)
                              ->where('id', $barang['id_asset'])
                              ->where('jumlah_barang >=', (int)$jumlah_digunakan) // Pastikan tidak minus
                              ->update();
                }
                // Jika dari habis terpakai ke tersedia (pengembalian), tidak perlu update stok
            } else {
                // Untuk barang yang dapat diganti kepemilikan
                if ($statusLama === 'tersedia' && in_array($statusBaru, ['terpakai', 'dipakai', 'rusak', 'maintenance'])) {
                    // Kurangi stok tersedia
                    $assetModel->set('jumlah_barang', 'jumlah_barang - 1', false)
                              ->where('id', $barang['id_asset'])
                              ->where('jumlah_barang > 0') // Pastikan tidak minus
                              ->update();
                } elseif (in_array($statusLama, ['terpakai', 'dipakai', 'rusak', 'maintenance']) && $statusBaru === 'tersedia') {
                    // Tambah stok tersedia
                    $assetModel->set('jumlah_barang', 'jumlah_barang + 1', false)
                              ->where('id', $barang['id_asset'])
                              ->update();
                }
            }
        }
    }

    // Method untuk menentukan apakah barang habis terpakai
    private function isHabisTerpakaiBarang($kodeKategori)
    {
        // Daftar kategori barang yang habis terpakai
        $habisTerpakaiKategori = [
            'barang_habis_pakai',
            'kertas',
            'tinta',
            'alat_tulis',
            'konsumsi',
            'atk',
            'makanan',
            'minuman',
            'bahan_habis_pakai'
        ];
        
        return in_array(strtolower($kodeKategori), array_map('strtolower', $habisTerpakaiKategori));
    }

    //  Method untuk sinkronisasi data pemakaian (hanya update, tidak tambah data baru)
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

    //  METHOD BARU: Auto update status ke habis terpakai berdasarkan stok
    public function autoUpdateStatusHabisTerpakai()
    {
        $barangModel = new BarangModel();
        $assetModel = new AssetModel();

        // Ambil semua barang habis pakai yang stoknya 0 tapi statusnya masih tersedia/terpakai
        $barangHabis = $barangModel
            ->select('barang.*, asset.kode_kategori, asset.jumlah_barang')
            ->join('asset', 'asset.id = barang.id_asset')
            ->where('asset.jumlah_barang', 0)
            ->whereIn('barang.status', ['tersedia', 'terpakai', 'dipakai'])
            ->findAll();

        $updatedCount = 0;
        foreach ($barangHabis as $barang) {
            if ($this->isHabisTerpakaiBarang($barang['kode_kategori'])) {
                $barangModel->update($barang['id'], [
                    'status' => 'habis terpakai',
                    'tanggal_habis' => date('Y-m-d H:i:s')
                ]);
                $updatedCount++;
            }
        }

        return [
            'success' => true,
            'message' => "Berhasil update {$updatedCount} barang menjadi status habis terpakai",
            'updated_count' => $updatedCount
        ];
    }
}