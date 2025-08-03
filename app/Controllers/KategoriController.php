<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\SubKategoriModel;

class KategoriController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'menu' => 'kategori',
            'kategori' => $this->kategoriModel->findAll(),
        ];
        return view('Datamaster/kategori/kategori', $data);
    }

    public function create()
    {
        $data['menu'] = 'kategori';
        return view('Datamaster/kategori/createkategori', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_kategori' => 'required',
            'nama_kategori' => 'required',
        ]);
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $this->kategoriModel->save([
            'kode_kategori' => $this->request->getPost('kode_kategori'),
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);
        return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data['menu'] = 'kategori';
        $data['kategori'] = $this->kategoriModel->find($id);
        return view('Datamaster/kategori/editkategori', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_kategori' => 'required',
            'nama_kategori' => 'required',
        ]);
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $this->kategoriModel->update($id, [
            'kode_kategori' => $this->request->getPost('kode_kategori'),
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ]);
        return redirect()->to('/kategori')->with('success', 'Kategori berhasil diupdate.');
    }

    public function delete($id)
    {
        try {
            if ($this->kategoriModel->delete($id)) {
                return redirect()->to('/kategori')->with('success', 'Kategori berhasil dihapus.');
            } else {
                return redirect()->to('/kategori')->with('error', 'Kategori gagal dihapus.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/kategori')->with('error', 'Kategori tidak dapat dihapus karena ada referensi yang terkait.');
        }
    }
}
