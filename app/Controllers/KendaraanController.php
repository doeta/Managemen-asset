<?php

namespace App\Controllers;
use App\Models\PenggunaModel;
use App\Models\LokasiModel;
use App\Models\RiwayatKendaraanModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class KendaraanController extends BaseController
{
    public function kendaraan()
    {
        $model = model('KendaraanModel');
        $data['menu'] = 'kendaraan';
        $data['kendaraan'] = $model->getKendaraanWithPengguna()->findAll(); // Ambil semua data tanpa paginate
        // $data['pager'] = $model->pager; // Hapus baris ini karena tidak pakai paginate
        return view('Datamaster/kendaraan/kendaraan', $data);
    }
    public function create()
{
    $kendaraanModel = model('KendaraanModel');
    $penggunaModel = model('PenggunaModel');
    $lokasiModel = model('LokasiModel'); // Pastikan model ini sudah ada

    // Mengambil data pengguna
    $data['pengguna'] = $penggunaModel->findAll();  // Ambil semua data pengguna

    // Mengambil data lokasi
    $data['lokasi'] = $lokasiModel->findAll();      // Ambil semua data lokasi

    $data['menu'] = 'kendaraan'; // Set menu aktif
    
    // Kirim data pengguna dan lokasi ke view
    return view('Datamaster/kendaraan/createkendaraan', $data);
}



    public function store()
    {
        $penggunaModel = model('PenggunaModel');
        $model = model('KendaraanModel');
        $lokasiModel = model('LokasiModel'); // Pastikan model ini sudah ada
        
        $riwayatModel = model('RiwayatKendaraan'); // Pastikan model ini sudah ada
        ////pajak
        $pembayaran = $this->request->getPost('pembayaran_pajak');
        $masa_berlaku = date('Y-m-d', strtotime("+1 year", strtotime($pembayaran)));

        $data = [
            'menu' => 'kendaraan',
            'id_pengguna' => $this->request->getPost('id_pengguna'),
            'id_lokasi' => $this->request->getPost('id_lokasi'), // Pastikan id_lokasi ada di form
            'nama_kendaraan' => $this->request->getPost('nama_kendaraan'),
            'no_polisi' => $this->request->getPost('no_polisi'),
            'nomor_polisi_sebelumnya' => $this->request->getPost('nomor_polisi_sebelumnya'),
            'model_kendaraan' => $this->request->getPost('model_kendaraan'),
            'warna' => $this->request->getPost('warna'),
            'merk_kendaraan' => $this->request->getPost('merk_kendaraan'),
            'tipe_kendaraan' => $this->request->getPost('tipe_kendaraan'),
            'harga' => $this->request->getPost('harga'),
            'tahun_kendaraan' => $this->request->getPost('tahun_kendaraan'),
            'no_rangka' => $this->request->getPost('no_rangka'),
            'no_mesin' => $this->request->getPost('no_mesin'),
            'no_bpkb' => $this->request->getPost('no_bpkb'),
            'no_stnk' => $this->request->getPost('no_stnk'),
            'harga_pajak' => $this->request->getPost('harga_pajak'),
            'pembayaran_pajak' => $pembayaran,
            'masa_berlaku' => $masa_berlaku
        ];

        if ($model->insert($data)) {
            $idKendaraan = $model->getInsertID();

            // Insert ke tabel riwayat kendaraan
            $riwayatModel->insert([
                
                'id_kendaraan'   => $idKendaraan,
                'id_pengguna'    => $data['id_pengguna'], // perbaiki di sini
                'id_lokasi'      => $data['id_lokasi'],   // perbaiki di sini
                'nomor_polisi'   => $data['no_polisi'],
                'tanggal_mulai'  => date('Y-m-d'),
                'tanggal_selesai'=> null,
                'keterangan'     => 'Kendaraan baru ditambahkan'
            ]);

            return redirect()->to('/kendaraan')->with('success', 'Data kendaraan & riwayat berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data kendaraan.');
        }
    }

    public function edit($id)
    {
        $model = model('KendaraanModel');
        $penggunaModel = model('PenggunaModel');
        
        
        // Mengambil data kendaraan berdasarkan ID
        $data['kendaraan'] = $model->find($id);

        $data['menu'] = 'kendaraan'; // Set menu aktif
        
        // Mengambil data pengguna
        $data['pengguna'] = $penggunaModel->findAll();  // Ambil semua data pengguna
        
        return view('Datamaster/kendaraan/editkendaraan', $data);
    }
    public function update($id)
    {
        $model = model('KendaraanModel');
        ////pajak
        $pembayaran = $this->request->getPost('pembayaran_pajak');
        $masa_berlaku = date('Y-m-d', strtotime("+1 year", strtotime($pembayaran)));
        $data = [
            'menu' => 'kendaraan',
            'id_pengguna' => $this->request->getPost('id_pengguna'),
            'id_lokasi' => $this->request->getPost('id_lokasi'), // Pastikan id_lokasi ada di form
            'nama_kendaraan' => $this->request->getPost('nama_kendaraan'),
            'no_polisi' => $this->request->getPost('no_polisi'),
            'nomor_polisi_sebelumnya' => $this->request->getPost('nomor_polisi_sebelumnya'),
            'model_kendaraan' => $this->request->getPost('model_kendaraan'),
            'warna' => $this->request->getPost('warna'),
            'merk_kendaraan' => $this->request->getPost('merk_kendaraan'),
            'tipe_kendaraan' => $this->request->getPost('tipe_kendaraan'),
            'harga' => $this->request->getPost('harga'),
            'tahun_kendaraan' => $this->request->getPost('tahun_kendaraan'),
            'no_rangka' => $this->request->getPost('no_rangka'),
            'no_mesin' => $this->request->getPost('no_mesin'),
            'no_bpkb' => $this->request->getPost('no_bpkb'),
            'no_stnk' => $this->request->getPost('no_stnk'),
            'harga_pajak' => $this->request->getPost('harga_pajak'),
            'pembayaran_pajak' => $pembayaran,
            'masa_berlaku' => $masa_berlaku
        ];

        if ($model->update($id, $data)) {
            return redirect()->to('/kendaraan')->with('success', 'Data kendaraan berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data kendaraan.');
        }
    }

    public function show($id)
    {
        $model = model('KendaraanModel');

        $data['menu'] = 'kendaraan'; // Set menu aktif
        
        // Ambil satu kendaraan + pengguna-nya berdasarkan ID
        $data['kendaraan'] = $model->getKendaraanWithPengguna()
                                    ->where('kendaraan.id', $id)
                                    ->first();
    
        // Cek jika data tidak ditemukan
        if (!$data['kendaraan']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Kendaraan dengan ID $id tidak ditemukan");
        }
    
        return view('Datamaster/kendaraan/detailkendaraan', $data);
    }
    
    public function delete($id)
    {
        $data['menu'] = 'kendaraan'; // Set menu aktif
        $model = model('KendaraanModel');
        if ($model->delete($id)) {
            return redirect()->to('/kendaraan')->with('success', 'Data kendaraan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data kendaraan.');
        }
    }

    public function mobil()
    {
        $model = model('KendaraanModel');
        $data['menu'] = 'mobil';
        $data['kendaraan'] = $model->getKendaraanWithPengguna()->where('model_kendaraan', 'mobil')->findAll(); // Ambil semua data mobil
        // $data['pager'] = $model->pager; // Hapus baris ini
        return view('kendaraan/mobil', $data);
    }
    public function riwayatmobil($id)
    {
        $data['menu'] = 'mobil';    
        $riwayatModel = model('RiwayatKendaraan');
        $data['riwayat'] = $riwayatModel->getRiwayatByKendaraan($id);
        $data['kendaraan'] = model('KendaraanModel')->find($id);
        return view('kendaraan/riwayatmobil', $data);
    }

    public function editmobil($id)
    {
        $kendaraanModel = new \App\Models\KendaraanModel();
        $riwayatModel   = new \App\Models\RiwayatKendaraan();
        $penggunaModel  = new \App\Models\PenggunaModel();
        $lokasiModel    = new \App\Models\LokasiModel();

      
        // Ambil detail mobil
        $kendaraan = $kendaraanModel
            ->select('kendaraan.*, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left')
            ->where('kendaraan.id', $id)
            ->first();

        // Ambil riwayat penggunaan mobil
        $riwayat = $riwayatModel
            ->select('riwayat_kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = riwayat_kendaraan.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = riwayat_kendaraan.id_lokasi', 'left')
            ->where('riwayat_kendaraan.id_kendaraan', $id)
            ->orderBy('riwayat_kendaraan.tanggal_mulai', 'DESC')
            ->findAll();

        // Data pengguna dan lokasi untuk form
        $pengguna = $penggunaModel->findAll();
        $lokasi   = $lokasiModel->findAll();

        return view('kendaraan/editmobil', [
            'kendaraan' => $kendaraan,  
            'riwayat'   => $riwayat,
            'pengguna'  => $pengguna,
            'lokasi'    => $lokasi,
            'menu'      => 'mobil', // agar sidebar aktif
        ]);
    }

    public function tambahRiwayatMobil($id)
    {
        $riwayatModel = new \App\Models\RiwayatKendaraan();

        $data = [
            'id_kendaraan'    => $id,
            'nomor_polisi'    => $this->request->getPost('nomor_polisi'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'id_pengguna'     => $this->request->getPost('id_pengguna'),
            'id_lokasi'       => $this->request->getPost('id_lokasi'),
            'keterangan'      => $this->request->getPost('keterangan'),
        ];

        $riwayatModel->insert($data);

        return redirect()->to('/kendaraan/editmobil/' . $id)->with('message', 'Riwayat penggunaan mobil berhasil ditambahkan');
    }

    public function motor()
    {
        $data['menu'] = 'motor';
        $model = model('KendaraanModel');
        $data['kendaraan'] = $model->getKendaraanWithPengguna()->where('model_kendaraan', 'motor')->findAll(); // Ambil semua data motor
        // $data['pager'] = $model->pager; // Hapus baris ini
        return view('kendaraan/motor', $data);
    }
    public function riwayatmotor($id)
    {   
        $data['menu'] = 'motor';
        $riwayatModel = model('RiwayatKendaraan');
        $data['riwayat'] = $riwayatModel->getRiwayatByKendaraan($id);
        $data['kendaraan'] = model('KendaraanModel')->find($id);
        return view('kendaraan/riwayatmotor', $data);
    }


        public function editmotor($id)
    {
        $kendaraanModel = new \App\Models\KendaraanModel();
        $riwayatModel   = new \App\Models\RiwayatKendaraan();
        $penggunaModel  = new \App\Models\PenggunaModel();
        $lokasiModel    = new \App\Models\LokasiModel();

        // Ambil detail motor
        $kendaraan = $kendaraanModel
            ->select('kendaraan.*, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.id = kendaraan.id_lokasi', 'left')
            ->where('kendaraan.id', $id)
            ->first();

        // Ambil riwayat penggunaan motor
        $riwayat = $riwayatModel
            ->select('riwayat_kendaraan.*, pengguna.nama_pengguna, lokasi.nama_lokasi')
            ->join('pengguna', 'pengguna.id = riwayat_kendaraan.id_pengguna', 'left')
            ->join('lokasi', 'lokasi.id = riwayat_kendaraan.id_lokasi', 'left')
            ->where('riwayat_kendaraan.id_kendaraan', $id)
            ->orderBy('riwayat_kendaraan.tanggal_mulai', 'DESC')
            ->findAll();

        // Data pengguna dan lokasi untuk form
        $pengguna = $penggunaModel->findAll();
        $lokasi   = $lokasiModel->findAll();

        return view('kendaraan/editmotor', [
            'kendaraan' => $kendaraan,
            'riwayat'   => $riwayat,
            'pengguna'  => $pengguna,
            'lokasi'    => $lokasi,
            'menu'      => 'motor', // agar sidebar aktif
        ]);
    }
    
public function tambahRiwayatMotor($id)
{
    $riwayatModel = new \App\Models\RiwayatKendaraan();

    $data = [
        'id_kendaraan'    => $id,
        'nomor_polisi'    => $this->request->getPost('nomor_polisi'),
        'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
        'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
        'id_pengguna'     => $this->request->getPost('id_pengguna'),
        'id_lokasi'       => $this->request->getPost('id_lokasi'),
        'keterangan'      => $this->request->getPost('keterangan'),
    ];

    $riwayatModel->insert($data);

    return redirect()->to('/kendaraan/editmotor/' . $id)->with('message', 'Riwayat penggunaan motor berhasil ditambahkan');
}

public function deleteMobil($id)
{
    $model = model('KendaraanModel');
    if ($model->delete($id)) {
        return redirect()->to('/kendaraan/mobil')->with('success', 'Data mobil berhasil dihapus.');
    } else {
        return redirect()->back()->with('error', 'Gagal menghapus data mobil.');
    }
}

public function deleteMotor($id)
{
    $model = model('KendaraanModel');
    if ($model->delete($id)) {
        return redirect()->to('/kendaraan/motor')->with('success', 'Data motor berhasil dihapus.');
    } else {
        return redirect()->back()->with('error', 'Gagal menghapus data motor.');
    }
    
}
    
}
