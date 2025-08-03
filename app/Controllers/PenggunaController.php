<?php

namespace App\Controllers;
use App\Models\LokasiModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PenggunaController extends BaseController
{

    protected $modelPengguna;

    public function __construct()
    {
        $this->modelPengguna = new \App\Models\PenggunaModel();
    }

    public function pengguna()
    {
        $data = [
            'menu' => 'pengguna',
            'pengguna' => $this->modelPengguna->getPenggunaWithLokasi(),
            
];
        return view('Datamaster/pengguna/pengguna', $data);
    }


    public function create()
    {
        $lokasiModel = new LokasiModel();
        
        $data['menu'] = 'pengguna'; // Set menu aktif
        $data['lokasi'] = $lokasiModel->findAll();
    
        return view('Datamaster/pengguna/createpengguna', $data);
    }

    public function store()
    {
        $data = [
            'menu' => 'pengguna',
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nip' => $this->request->getPost('nip'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),

        ];

        $this->modelPengguna->insert($data);
        return redirect()->to('/pengguna')->with('success', 'Data Pengguna berhasil ditambahkan.');
    }

    
public function edit($id)
{
    $lokasiModel = new LokasiModel();

    // Ambil semua data lokasi

    $data['menu'] = 'pengguna'; // Set menu aktif
    $data['lokasi'] = $lokasiModel->findAll();


    // Ambil data pengguna berdasarkan ID
    $pengguna = $this->modelPengguna->find($id);
    if (!$pengguna) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan');
    }

    // Kirim data pengguna dan lokasi ke view
    $data['pengguna'] = $pengguna;

    return view('Datamaster/pengguna/editpengguna', $data);
}

    public function update($id)
    {
        $data = [

            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nip' => $this->request->getPost('nip'),
            'no_hp' => $this->request->getPost('no_hp'),
            'id_lokasi' => $this->request->getPost('id_lokasi'),
            'alamat' => $this->request->getPost('alamat'),
            'deskripsi_pengguna' => $this->request->getPost('deskripsi_pengguna'),
        ];

        $this->modelPengguna->update($id, $data);
        return redirect()->to('/pengguna')->with('success', 'Data Pengguna berhasil diupdate.');
    }

    public function delete($id)
    {
        try {
            if ($this->modelPengguna->delete($id)) {
                return redirect()->to('/pengguna')->with('success', 'Data Pengguna berhasil dihapus.');
            } else {
                return redirect()->to('/pengguna')->with('error', 'Data Pengguna gagal dihapus.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/pengguna')->with('error', 'Data Pengguna gagal dihapus. Pastikan data tidak sedang digunakan.');
        }
    }
}
