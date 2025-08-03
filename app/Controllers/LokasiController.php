<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LokasiController extends BaseController
{
    protected $modelLokasi;

    public function __construct()
    {
        $this->modelLokasi = new \App\Models\LokasiModel();
    }

    public function lokasi()
    {
        $data = [
            'menu' => 'lokasi',
            'lokasi' => $this->modelLokasi->findAll(),
           
        ];
        return view('Datamaster/lokasi/lokasi', $data);
    }


    public function create()
    {
        $data['menu'] = 'lokasi';
        return view('Datamaster/lokasi/createlokasi', $data);
    }
    private function generateKodeLokasi()
    {
        // Ambil lokasi terakhir berdasarkan kode_lokasi
        $lastLokasi = $this->modelLokasi
            ->orderBy('kode_lokasi', 'DESC')
            ->first();

        if ($lastLokasi) {
            // Ambil angka dari kode terakhir: KSK01 => 1
            $lastNumber = (int) str_replace('KL', '', $lastLokasi['kode_lokasi']);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'KL' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
    public function store()
    {
        $data = [
            'menu' => 'lokasi',
            'kode_lokasi' => $this->generateKodeLokasi(),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'deskripsi_lokasi' => $this->request->getPost('deskripsi_lokasi'),
        ];

        $this->modelLokasi->insert($data);
        return redirect()->to('/lokasi')->with('success', 'Data Lokasi berhasil ditambahkan.');
    }

   public function edit($id)
{
    $lokasi = $this->modelLokasi->find($id);
    if (!$lokasi) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
    }

    $data = [
        'menu' => 'lokasi',         // Set menu aktif
        'lokasi' => $lokasi         // Data lokasi
    ];

    return view('Datamaster/lokasi/editlokasi', $data);
}


    public function update($id)
    {
        $data = [
            'menu' => 'lokasi',         // Set menu aktif
            'kode_lokasi' => $this->request->getPost('kode_lokasi'),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'deskripsi_lokasi' => $this->request->getPost('deskripsi_lokasi'),
        ];
        

        $this->modelLokasi->update($id, $data);
        return redirect()->to('/lokasi')->with('success', 'Data Lokasi berhasil diupdate.');
    }

    public function delete($id)
    {
        try { 
            if ($this->modelLokasi->delete($id)) {
                return redirect()->to('/lokasi')->with('success', 'Data Lokasi berhasil dihapus.');
            } else {
                return redirect()->to('/lokasi')->with('error', 'Data Lokasi gagal dihapus.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/lokasi')->with('error', 'Data tidak dapat dihapus karena ada referensi yang terkait.');
        }
    }
}
