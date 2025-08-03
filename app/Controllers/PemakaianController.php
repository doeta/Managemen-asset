<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemakaianModel;
use App\Models\LokasiModel;
use App\Models\PenggunaModel;
use App\Models\KategoriModel;
use App\Models\SubKategoriModel;
use App\Models\AssetModel;

class PemakaianController extends BaseController
{
    public function pemakaian()
    {
        $pemakaianModel = new PemakaianModel();
        $data['menu'] = 'pemakaian';
        $data['pemakaian'] = $pemakaianModel->getPemakaianWithAsset(); // with asset, lokasi, pengguna
        return view('pemakaian/pemakaian', $data);
    }

    public function create()
    {
        $kategoriModel     = new KategoriModel();
        $subKategoriModel  = new SubKategoriModel();
        $lokasiModel       = new LokasiModel();
        $penggunaModel     = new PenggunaModel();
        $assetModel        = new AssetModel();

        $data = [
            'menu'         => 'pemakaian',
            'kategori'     => $kategoriModel->findAll(),
            'sub_kategori' => $subKategoriModel->findAll(),
            'barang'       => $assetModel->getAssetForPemakaian(), // hanya stok > 0 atau semua barang modal
            'lokasi'       => $lokasiModel->findAll(),
            'pengguna'     => $penggunaModel->findAll(),
        ];

        return view('pemakaian/createpemakaian', $data);
    }

   public function simpan()
{
    $post = $this->request->getPost();
    $pemakaianModel = new \App\Models\PemakaianModel();
    $assetModel = new \App\Models\AssetModel();
    $db = \Config\Database::connect();

    $asset = $assetModel->find($post['id_asset']);
    if (!$asset) {
        return redirect()->back()->with('error', 'Asset tidak ditemukan');
    }

    $kategori = $post['kode_kategori'];

    if ($kategori !== 'barang_modal') {
        if ($post['jumlah_digunakan'] > $asset['jumlah_barang']) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok tersedia');
        }

        // ✅ Update stok di tabel asset
        $assetModel->update($post['id_asset'], [
            'jumlah_barang' => $asset['jumlah_barang'] - $post['jumlah_digunakan']
        ]);

        // ✅ Update sebanyak jumlah_digunakan baris di tabel barang
        $jumlah = (int) $post['jumlah_digunakan'];

        $barangTersedia = $db->table('barang')
            ->where('id_asset', $post['id_asset'])
            ->where('status', 'tersedia')
            ->limit($jumlah)
            ->get()
            ->getResultArray();

        foreach ($barangTersedia as $b) {
            $db->table('barang')
                ->where('id', $b['id'])
                ->update([
                    'status' => $post['status'], // terpakai / habis terpakai
                    'id_pengguna' => $post['id_pengguna'],
                    'id_lokasi' => $post['id_lokasi']
                ]);
        }
    } else {
        // Barang Modal - hanya ambil satu untuk diubah
        $barangTersedia = $db->table('barang')
            ->where('id_asset', $post['id_asset'])
            ->where('status', 'tersedia')
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($barangTersedia) {
            $db->table('barang')
                ->where('id', $barangTersedia['id'])
                ->update([
                    'status' => $post['status'],
                    'id_pengguna' => $post['id_pengguna'],
                    'id_lokasi' => $post['id_lokasi']
                ]);
        }
    }

    // Simpan ke tabel pemakaian
    $pemakaianModel->save([
        'id_asset' => $post['id_asset'],
        'kode_kategori' => $post['kode_kategori'],
        'kode_sub_kategori' => $post['kode_sub_kategori'],
        'id_lokasi' => $post['id_lokasi'],
        'id_pengguna' => $post['id_pengguna'],
        'jumlah_digunakan' => $post['jumlah_digunakan'],
        'satuan_penggunaan' => $post['satuan_penggunaan'],
        'tanggal_mulai' => $post['tanggal_mulai'],
        'tanggal_selesai' => $post['tanggal_selesai'],
        'keterangan' => $post['keterangan'],
        'status' => $post['status'],
    ]);

    return redirect()->to('/pemakaian')->with('message', 'Pemakaian berhasil disimpan dan barang diperbarui.');
}



}
