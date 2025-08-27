<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\{AssetModel, SubKategoriModel, KategoriModel, RiwayatModel, RiwayatPembelianModel};
use Config\Database;

class AssetController extends BaseController
{
    protected $modelAsset;

    public function __construct()
    {
        $this->modelAsset = new AssetModel();
    }

    public function index()
    {
        $data = [
            'menu' => 'asset',
            'assets' => $this->modelAsset->getAssetWithKategoriPaginated(10),
            'pager'  => $this->modelAsset->pager,
        ];
        return view('Datamaster/asset/asset', $data);
    }

    public function show($id)
    {
        $data['menu'] = 'asset';
        $data['asset'] = $this->modelAsset->getAssetWithKategoriWhere($id);
        $riwayatModel = new RiwayatModel();
        $data['riwayat_pengguna'] = $riwayatModel->select('riwayat.*, pengguna.nama_pengguna')
            ->join('pengguna', 'pengguna.id = riwayat.id_pengguna', 'left')
            ->where('riwayat.id_asset', $id)
            ->orderBy('riwayat.tanggal_mulai', 'DESC')
            ->findAll();
        return view('Dataassets/detailassets', $data);
    }

    public function create()
    {
        $modelSubKategori = new SubKategoriModel();
        $modelKategori = new KategoriModel();
        $data = [
            'menu' => 'asset',
            'sub_kategori' => $modelSubKategori->findAll(),
            'kategori' => $modelKategori->findAll(),
        ];

        return view('Datamaster/asset/createasset', $data);
    }

    private function generateKodeAsset()
    {
        $lastAsset = $this->modelAsset->orderBy('kode_barang', 'DESC')->first();
        $newNumber = $lastAsset ? ((int) str_replace('KB', '', $lastAsset['kode_barang']) + 1) : 1;
        return 'KB' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $jumlah         = (int) $this->request->getPost('jumlah_barang');
        $harga          = (int) $this->request->getPost('harga_barang');
        $totalHarga     = $jumlah * $harga;
        $namaBarang     = $this->request->getPost('nama_barang');
        $kodeKategori   = $this->request->getPost('kode_kategori');
        $kodeSubKategori = $this->request->getPost('kode_sub_kategori');
        $tanggalMasuk   = $this->request->getPost('tanggal_masuk');
        $deskripsiBarang = $this->request->getPost('deskripsi_barang');

        $db = Database::connect();
        $riwayatModel = new RiwayatModel();
        $riwayatPembelianModel = new RiwayatPembelianModel();

        $existingAsset = $this->modelAsset
            ->where('nama_barang', $namaBarang)
            ->where('kode_kategori', $kodeKategori)
            ->where('kode_sub_kategori', $kodeSubKategori)
            ->first();

        if ($existingAsset) {
            $newJumlah = $existingAsset['jumlah_barang'] + $jumlah;
            $newTotalHarga = $existingAsset['total_harga_barang'] + $totalHarga;

            $this->modelAsset->update($existingAsset['id'], [
                'jumlah_barang' => $newJumlah,
                'total_harga_barang' => $newTotalHarga,
                'harga_barang' => $harga,
                'tanggal_masuk' => $tanggalMasuk,
                'deskripsi_barang' => $deskripsiBarang
            ]);

            $idAsset = $existingAsset['id'];
            $kodeBarang = $existingAsset['kode_barang'];
            $startIndex = $existingAsset['jumlah_barang'];

            session()->setFlashdata('message', 'Barang sudah ada. Jumlah berhasil ditambahkan ke stok lama.');
            session()->setFlashdata('status_barang', 'lama');
        } else {
            $kodeBarang = $this->generateKodeAsset();

            $this->modelAsset->save([
                'kode_barang' => $kodeBarang,
                'nama_barang' => $namaBarang,
                'kode_kategori' => $kodeKategori,
                'kode_sub_kategori' => $kodeSubKategori,
                'deskripsi_barang' => $deskripsiBarang,
                'jumlah_barang' => $jumlah,
                'harga_barang' => $harga,
                'total_harga_barang' => $totalHarga,
                'tanggal_masuk' => $tanggalMasuk,
            ]);

            $idAsset = $this->modelAsset->getInsertID();
            $startIndex = 0;

            session()->setFlashdata('message', 'Barang baru berhasil ditambahkan.');
            session()->setFlashdata('status_barang', 'baru');
        }

        for ($i = 0; $i < $jumlah; $i++) {
            $nomor = $startIndex + $i + 1;
            $kodeUnik = $kodeBarang . '-' . $kodeKategori . '-' . $kodeSubKategori . '-' . $tanggalMasuk . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);

            $db->table('barang')->insert([
                'id_asset' => $idAsset,
                'nama_barang' => $namaBarang,
                'kode_unik' => $kodeUnik,
                'harga_barang' => $harga,
                'tanggal_masuk' => $tanggalMasuk,
                'status' => 'tersedia',
            ]);

            $idBarang = $db->insertID();

            $riwayatModel->insert([
                'menu' => 'asset',
                'id_asset' => $idAsset,
                'id_barang' => $idBarang,
                'tanggal_masuk' => $tanggalMasuk,
                'tanggal_selesai' => null,
                'keterangan' => 'Barang masuk',
            ]);
        }

        // Tambahkan riwayat pembelian
        $riwayatPembelianModel->insert([
            'id_asset' => $idAsset,
            'kode_kategori' => $kodeKategori,
            'kode_sub_kategori' => $kodeSubKategori,
            'nama_barang' => $namaBarang,
            'kode_barang' => $kodeBarang,
            'deskripsi_barang' => $deskripsiBarang,
            'jumlah_dibeli' => $jumlah,
            'harga_satuan' => $harga,
            'total_harga' => $totalHarga,
            'tanggal_pembelian' => $tanggalMasuk
        ]);

        return redirect()->to('/asset/create');
    }

    public function edit($id)
    {
        $modelSubKategori = new SubKategoriModel();
        $modelKategori = new KategoriModel();

        $data = [
            'menu' => 'asset',
            'sub_kategori' => $modelSubKategori->findAll(),
            'kategori' => $modelKategori->findAll(),
            'asset' => $this->modelAsset->getAssetWithKategoriWhere($id),
        ];

        return view('Datamaster/asset/editasset', $data);
    }

    public function update($id)
    {
        $jumlah = (int) $this->request->getPost('jumlah_barang');
        $harga = (int) $this->request->getPost('harga_barang');
        $totalHarga = $jumlah * $harga;

        $assetLama = $this->modelAsset->find($id);
        $jumlahLama = $assetLama['jumlah_barang'];

        $data = [
            'menu'               => 'asset',
            'id'                 => $id,
            'kode_kategori'      => $this->request->getPost('kode_kategori'),
            'kode_sub_kategori'  => $this->request->getPost('kode_sub_kategori'),
            'nama_barang'        => $this->request->getPost('nama_barang'),
            'kode_barang'        => $this->request->getPost('kode_barang'),
            'deskripsi_barang'   => $this->request->getPost('deskripsi_barang'),
            'jumlah_barang'      => $jumlah,
            'harga_barang'       => $harga,
            'total_harga_barang' => $totalHarga,
            'tanggal_masuk'      => $this->request->getPost('tanggal_masuk'),
        ];

        $this->modelAsset->update($id, $data);

        $delta = $jumlah - $jumlahLama;
        $barangModel = new \App\Models\BarangModel();

        if ($delta > 0) {
            for ($i = 0; $i < $delta; $i++) {
                $barangModel->save([
                    'id_asset' => $id,
                    'status' => 'Tersedia',
                    'harga_barang' => $harga,
                    'tanggal_masuk' => $data['tanggal_masuk'],
                ]);
            }
        } elseif ($delta < 0) {
            $barangUntukDihapus = $barangModel->where('id_asset', $id)
                ->where('status', 'Tersedia')
                ->limit(abs($delta))
                ->findAll();

            foreach ($barangUntukDihapus as $barang) {
                $barangModel->delete($barang['id']);
            }
        }

        return redirect()->to('/asset')->with('message', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $db = Database::connect();
        $builder = $db->table('barang');

        $related = $builder->where('id_asset', $id)->countAllResults();

        if ($related > 0) {
            return redirect()->to('/asset')->with('error', 'Tidak bisa menghapus data, masih ada barang terkait. Hapus dulu data barang.');
        }

        $this->modelAsset->delete($id);
        return redirect()->to('/asset')->with('message', 'Data asset berhasil dihapus.');
    }

public function riwayat($id)
{
    $asset = $this->modelAsset->find($id);
    if (!$asset) {
        return redirect()->to('/asset')->with('error', 'Data asset tidak ditemukan.');
    }

    $riwayatModel = new \App\Models\RiwayatPembelianModel();
    $riwayat = $riwayatModel
                ->where('id_asset', $id)
                ->orderBy('tanggal_pembelian', 'DESC')
                ->findAll();

    $data = [
        'menu' => 'asset',
        'asset' => $asset, // <-- Pastikan ini berisi data asset termasuk 'id'
        'riwayat' => $riwayat,
    ];

    return view('pembelian/riwayatpembelian', $data);
}


}
