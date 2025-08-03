<?php
namespace App\Controllers;

use App\Models\SubKategoriModel;
use App\Models\KategoriModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;


class SubKategoriController extends BaseController
{
   protected $subKategoriModel;

    public function __construct()
    {
        $this->subKategoriModel = new SubKategoriModel();
    }

    public function index()
    {
        // Ambil data dari database menggunakan model
        $dataPerPage = 10; // Jumlah data per halaman
        $data = [
            'menu' => 'subkategori', // Set menu aktif
            'sub_kategori' => $this->subKategoriModel->getSubKategoriWithKategori(),  // Ambil data dengan pagination
        ];

        // Kirim data ke view
        return view('Datamaster/subkategori/subkategori', $data);
    }


    public function show($id)
    {
        $data['menu'] = 'subkategori'; // Set menu aktif
        //ambil data kategori berdasarkan ID
        $data['sub_kategori'] = $this->subKategoriModel->find($id);

        // Kirim data ke view
        return view('Datamaster/subkategori/subdetailkategori', $data);
    }

    public function create()
    {
        $data['menu'] = 'subkategori'; // Set menu aktif
        $modelKategori = new KategoriModel(); // Correct variable name to match usage
        $data['kategori'] = $modelKategori->findAll();
        return view('Datamaster/subkategori/createsubkategori', $data);
    }

    private function generateKodeSubKategori()
{
    // Ambil sub kategori terakhir berdasarkan kode_sub_kategori
    $lastSub = $this->subKategoriModel
        ->orderBy('kode_sub_kategori', 'DESC')
        ->first();

    if ($lastSub) {
        // Ambil angka dari kode terakhir: KSK01 => 1
        $lastNumber = (int) str_replace('SK', '', $lastSub['kode_sub_kategori']);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1; // Kalau belum ada data
    }

    // Format: KSK01, KSK02, ...
    return 'SK' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
}


    public function store()
    {
    $validation = \Config\Services::validation();

    $validation->setRules([
        'kode_kategori' => 'required',
        'nama_sub_kategori' => 'required',
        'deskripsi_sub_kategori' => 'required',
    ]);

    if (!$this->validate($validation->getRules())) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $data ['menu'] = 'subkategori'; // Set menu aktif
    $kodeKategori = $this->request->getPost('kode_kategori');
    $kodeSubKategori = $this->generateKodeSubKategori($kodeKategori);

    $this->subKategoriModel->save([
        'kode_kategori' => $kodeKategori,
        'kode_sub_kategori' => $kodeSubKategori,
        'nama_sub_kategori' => $this->request->getPost('nama_sub_kategori'),
        'deskripsi_sub_kategori' => $this->request->getPost('deskripsi_sub_kategori'),
    ]);

    return redirect()->to('/subkategori')->with('success', 'Data berhasil disimpan.'); 
    }

    public function edit($id)
    {
        $modelKategori = new KategoriModel();
        
        $data['menu'] = 'subkategori'; // Set menu aktif
        // Ambil semua data kategori
        $data['kategori'] = $modelKategori->findAll();
        
        // Ambil data sub kategori berdasarkan ID
        $data['sub_kategori'] = $this->subKategoriModel->find($id);

        // Kirim data ke view
        return view('Datamaster/subkategori/editsubkategori', $data);
    }

    public function update($id)
    {
        $data = [
            'menu' => 'subkategori', // Set menu aktif
            'id' => $id,
            'kode_kategori' => $this->request->getPost('kode_kategori'),
            'kode_sub_kategori' => $this->request->getPost('kode_sub_kategori'),
            'nama_sub_kategori' => $this->request->getPost('nama_sub_kategori'),
            'deskripsi_kategori' => $this->request->getPost('deskripsi_kategori')
        ]; //ambil dari form 

        $this->subKategoriModel->save($data);
        return redirect()->to('/subkategori');
    }

    public function delete($id)
    {
        try {
            if ($this->subKategoriModel->delete($id)) {
                return redirect()->to('/subkategori')->with('success', 'Data berhasil dihapus.');
            } else {
                return redirect()->to('/subkategori')->with('error', 'Data gagal dihapus.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/subkategori')->with('error', 'Data tidak dapat dihapus karena ada referensi yang terkait.'); 
        }
    }

    

}
