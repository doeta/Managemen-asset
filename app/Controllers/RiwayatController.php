<?php

namespace App\Controllers;
use App\Models\RiwayatModel;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RiwayatController extends BaseController

{
    protected $riwayatModel;

    public function __construct()
    {
        $this->riwayatModel = new RiwayatModel();
    }

    public function index($idBarang)
    {
        $riwayat = $this->riwayatModel
            ->where('id_barang', $idBarang)
            ->join('pengguna', 'pengguna.id = riwayat.id_pengguna', 'left')
            ->orderBy('tanggal_mulai', 'DESC')
            ->findAll();

        return view('Datamaster/riwayat/index', [
            'riwayat' => $riwayat
        ]);
    }

    public function create($idBarang)
    {
        return view('Datamaster/riwayat/create', [
            'id_barang' => $idBarang
        ]);
    }

    public function store()
    {
        $this->riwayatModel->save([
            'id_barang' => $this->request->getPost('id_barang'),
            'id_asset' => $this->request->getPost('id_asset'),
            'id_pengguna' => $this->request->getPost('id_pengguna'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'keterangan' => $this->request->getPost('keterangan'),
            'jumlah_digunakan' => $this->request->getPost('jumlah_digunakan'),
            'satuan_penggunaan' => $this->request->getPost('satuan_penggunaan'),
        ]);

        return redirect()->back()->with('message', 'Riwayat penggunaan barang berhasil ditambahkan.');
    }
}
