<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RiwayatPembelianModel;
use App\Models\AssetModel;

class PembelianController extends BaseController
{
 /*   public function create($id_asset)
    {
        $assetModel = new AssetModel();
        $asset = $assetModel->getAssetWithKategoriWhere($id_asset);

        if (!$asset) {
            return redirect()->to('/asset')->with('error', 'Asset tidak ditemukan');
        }

        $data = [
            'menu' => 'pembelian',
            'asset' => $asset,
        ];

        return view('pembelian/create', $data);
    }
*/
  public function store()
{
    $assetModel = new \App\Models\AssetModel();
    $riwayatModel = new \App\Models\RiwayatPembelianModel();
    $db = \Config\Database::connect();

    $post = $this->request->getPost();

    // Cek apakah asset sudah ada
    $asset = $assetModel->where('nama_barang', $post['nama_barang'])->first();

    if ($asset) {
        // Jika ada, update jumlah dan total harga
        $newJumlah = $asset['jumlah_barang'] + $post['jumlah_barang'];
        $newTotalHarga = $asset['total_harga_barang'] + ($post['jumlah_barang'] * $post['harga_barang']);

        $assetModel->update($asset['id'], [
            'jumlah_barang' => $newJumlah,
            'harga_barang' => $post['harga_barang'],
            'total_harga_barang' => $newTotalHarga,
            'tanggal_masuk' => $post['tanggal_masuk'],
            'deskripsi_barang' => $post['deskripsi_barang'],
            'kode_kategori' => $post['kode_kategori'],
            'kode_sub_kategori' => $post['kode_sub_kategori'],
        ]);

        $idAsset = $asset['id'];
        $kodeBarang = $asset['kode_barang'];
        $startIndex = $asset['jumlah_barang']; // jumlah awal sebelum update

    } else {
        // Jika belum ada, generate kode barang baru dan insert asset
        $lastAsset = $assetModel->orderBy('id', 'DESC')->first();
        $lastNumber = $lastAsset ? intval(substr($lastAsset['kode_barang'], 2)) : 0;
        $kodeBarang = 'KB' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        $dataAsset = [
            'nama_barang' => $post['nama_barang'],
            'kode_barang' => $kodeBarang,
            'kode_kategori' => $post['kode_kategori'],
            'kode_sub_kategori' => $post['kode_sub_kategori'],
            'deskripsi_barang' => $post['deskripsi_barang'],
            'jumlah_barang' => $post['jumlah_barang'],
            'harga_barang' => $post['harga_barang'],
            'total_harga_barang' => $post['jumlah_barang'] * $post['harga_barang'],
            'tanggal_masuk' => $post['tanggal_masuk'],
        ];

        $assetModel->save($dataAsset);
        $idAsset = $assetModel->getInsertID();
        $startIndex = 0;
    }

    // INSERT KE TABEL BARANG
    for ($i = 0; $i < $post['jumlah_barang']; $i++) {
        $nomor = $startIndex + $i + 1;
        $kodeUnik = $kodeBarang . '-' . $post['kode_kategori'] . '-' . $post['kode_sub_kategori'] . '-' . $post['tanggal_masuk'] . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);

        $db->table('barang')->insert([
            'id_asset' => $idAsset,
            'nama_barang' => $post['nama_barang'],
            'kode_unik' => $kodeUnik,
            'harga_barang' => $post['harga_barang'],
            'tanggal_masuk' => $post['tanggal_masuk'],
            'status' => 'tersedia',
        ]);
    }

    // INSERT RIWAYAT PEMBELIAN
    $riwayatModel->save([
        'id_asset' => $idAsset,
        'kode_kategori' => $post['kode_kategori'],
        'kode_sub_kategori' => $post['kode_sub_kategori'],
        'nama_barang' => $post['nama_barang'],
        'kode_barang' => $kodeBarang,
        'deskripsi_barang' => $post['deskripsi_barang'],
        'jumlah_dibeli' => $post['jumlah_barang'],
        'harga_satuan' => $post['harga_barang'],
        'total_harga' => $post['jumlah_barang'] * $post['harga_barang'],
        'tanggal_pembelian' => $post['tanggal_masuk'],
    ]);

    return redirect()->to('/riwayatpembelian/' . $idAsset)->with('message', 'Pembelian berhasil disimpan');
}


public function riwayatPembelian($id_asset = null)
{
    $assetModel = new \App\Models\AssetModel();
    $riwayatModel = new \App\Models\RiwayatPembelianModel();
    
    $asset = null;

    // Ambil data berdasarkan ID asset atau semua
    if ($id_asset) {
        $asset = $assetModel->getAssetWithKategoriWhere($id_asset); 
        $riwayat = $riwayatModel->getRiwayatWithAsset($id_asset);
    } else {
        $riwayat = $riwayatModel->getRiwayatWithAsset(); // ✅ gunakan tetap method JOIN
    }

    $data = [
        'menu' => 'riwayatpembelian',
        'asset' => $asset,
        'riwayat' => $riwayat,
    ];

    return view('pembelian/riwayatpembelian', $data);
}


/* public function riwayatBarang($id_asset = null)
    {
        $assetModel = new \App\Models\AssetModel();
        $barangModel = new \App\Models\BarangModel();

        if ($id_asset) {
            $asset = $assetModel->find($id_asset);
            $barang = $barangModel->where('id_asset', $id_asset)->orderBy('tanggal_masuk', 'DESC')->findAll();
        } else {
            $asset = null;
            $barang = $barangModel->orderBy('tanggal_masuk', 'DESC')->findAll();
        }

        $data = [
            'menu' => 'riwayatbarang',
            'asset' => $asset,
            'barang' => $barang,
        ];
        return view('pembelian/riwayatbarang', $data);
    }
*/
    public function pembelian()
    {
        // Ambil data asset, kategori, sub_kategori, dsb sesuai kebutuhan
        $assetModel = new \App\Models\AssetModel();
        $kategoriModel = new \App\Models\KategoriModel();
        $subKategoriModel = new \App\Models\SubKategoriModel();

        $data = [
            'menu' => 'pembelian',
            'assets' => $assetModel->findAll(),
            'kategori' => $kategoriModel->findAll(),
            'sub_kategori' => $subKategoriModel->findAll(),
        ];

        return view('pembelian/pembelian', $data);
    }

  public function delete($id)
{
    $model = new \App\Models\RiwayatPembelianModel();
    
    // Cari data berdasarkan id_riwayat, bukan id
    $data = $model->where('id_riwayat', $id)->first();

    if (!$data) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Hapus berdasarkan id_riwayat
    $model->where('id_riwayat', $id)->delete();

    return redirect()->to('/pembelian/riwayat')->with('message', 'Data berhasil dihapus.');
}


        
}
