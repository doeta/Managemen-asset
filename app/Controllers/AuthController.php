<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class AuthController extends BaseController
{
    public function index()
    {
        //
    }

    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $userModel = new UsersModel();
        $user = $userModel->where('username', $username)->first();



        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/dashboard');
        } else {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

// user pengguna 

    public function listUsers()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses hanya untuk admin');
        }
        $userModel = new \App\Models\UsersModel();
        $users = $userModel->findAll();
         return view('admin/list_users', [
        'users' => $users,
        'menu'  => 'users' // ✅ tambahkan ini
    ]);
    }

    public function createUser()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses hanya untuk admin');
        }
        return view('admin/create_user', [
        'menu' => 'users' // ✅ tambahkan ini juga
    ]);
    }

    public function storeUser()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses hanya untuk admin');
        }
        $userModel = new \App\Models\UsersModel();
        $data = [

            'username' => $this->request->getPost('username'),
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ];
        // Cek username sudah ada
        if ($userModel->where('username', $data['username'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah terdaftar!');
        }
        $userModel->insert($data);
        return redirect()->to('/auth/users')->with('success', 'User berhasil ditambahkan');
    }
    public function deleteUser($id)
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/auth/users')->with('error', 'Akses hanya untuk admin');
        }
        $userModel = new \App\Models\UsersModel();
        $userModel->delete($id);
        return redirect()->to('/auth/users')->with('success', 'User berhasil dihapus');
    }
}

