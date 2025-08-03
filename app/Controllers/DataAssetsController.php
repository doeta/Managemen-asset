<?php

namespace App\Controllers;

use App\Models\SubKategoriModel;
use App\Models\BarangModel;
use App\Models\RiwayatModel;
use App\Controllers\BaseController;
use App\Models\LokasiModel;
use App\Models\PenggunaModel;
use App\Models\KategoriModel;

class DataAssetsController extends BaseController
{
    public function index()
    {
        $subKategoriModel = new SubKategoriModel();
        $kategoriModel = new KategoriModel();

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

    public function detail($kode_sub_kategori)
    {
        $barangModel = new BarangModel();
        
        // Menggunakan method yang sudah ada di model
        $barang = $barangModel->getBarangWithAssetLocationPenggunaBySubKategori($kode_sub_kategori);

        $data = [
            'menu' => 'dataassets',
            'barang' => $barang,
            'kode_sub_kategori' => $kode_sub_kategori,
            'pager' => $barangModel->pager,
        ];

        return view('Dataassets/detailassets', $data);
    }


    public function edit($id)
    {
        $barangModel = new BarangModel();
        $lokasiModel = new LokasiModel();
        $penggunaModel = new PenggunaModel();

        $barang = $barangModel->getBarangById($id);

        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'menu' => 'dataassets',
            'barang' => $barang,
            'lokasi' => $lokasiModel->findAll(),
            'pengguna' => $penggunaModel->findAll(),
        ];

        return view('Dataassets/edit', $data);
    }

    public function update($id)
    {
        $barangModel = new BarangModel();

        // Validasi input utama
        $validationRules = [
            'status' => 'required',
            'id_lokasi' => 'required',
            'id_pengguna' => 'required',
        ];

        // Validasi input riwayat jika ada
        if ($this->request->getPost('jumlah_digunakan')) {
            $validationRules = array_merge($validationRules, [
                'jumlah_digunakan' => 'required|numeric',
                'satuan_penggunaan' => 'required',
                'tanggal_mulai' => 'required|valid_date',
            ]);
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Mohon lengkapi semua data.');
        }

        $barang = $barangModel->getBarangById($id);

        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data barang tidak ditemukan.');
        }

        // Update data barang
        $updateData = [
            'status' => $this->request->getPost('status'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
        ];

        $barangModel->update($id, $updateData);

        // Insert riwayat jika ada data penggunaan
        if ($this->request->getPost('jumlah_digunakan')) {
            $riwayatModel = new RiwayatModel();

            $riwayat = [
                'id_barang' => $id,
                'id_asset' => $barang['id_asset'],
                'jumlah_digunakan' => $this->request->getPost('jumlah_digunakan'),
                'satuan_penggunaan' => $this->request->getPost('satuan_penggunaan'),
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $this->request->getPost('tanggal_selesai') ?: null,
                'keterangan' => $this->request->getPost('keterangan'),
            ];

            $riwayatModel->insert($riwayat);
        }

        return redirect()->to('/dataassets/detail/' . $barang['kode_sub_kategori'])->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $barangModel = new BarangModel();

        if ($barangModel->delete($id)) {
            return redirect()->to('/dataassets')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to('/dataassets')->with('error', 'Gagal menghapus data.');
        }
    }

    public function riwayat($id)
    {
        $barangModel = new BarangModel();
        $riwayatModel = new RiwayatModel();

        $barang = $barangModel
        ->select('barang.*, asset.kode_sub_kategori')
        ->join('asset', 'asset.id = barang.id_asset')
        ->where('barang.id', $id)
        ->first();
        
        if (!$barang) {
            return redirect()->to('/dataassets')->with('error', 'Data tidak ditemukan.');
        }

        $riwayat = $riwayatModel->getRiwayatWithBarang($id);

        $data = [
            'menu' => 'dataassets',
            'riwayat' => $riwayat,
            'barang' => $barang,
        ];

        return view('Dataassets/riwayatassets', $data);
    }
}

