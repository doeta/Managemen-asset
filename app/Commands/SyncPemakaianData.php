<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BarangModel;
use App\Models\PemakaianModel;

class SyncPemakaianData extends BaseCommand
{
    protected $group       = 'Pemakaian';
    protected $name        = 'pemakaian:sync';
    protected $description = 'Sinkronisasi data pemakaian dengan data barang';

    public function run(array $params)
    {
        CLI::write('Memulai sinkronisasi data pemakaian...', 'yellow');

        $barangModel = new BarangModel();
        $pemakaianModel = new PemakaianModel();
        $db = \Config\Database::connect();

        // Ambil semua barang
        $barang = $barangModel->findAll();
        $totalBarang = count($barang);
        $syncedCount = 0;

        CLI::write("Total barang yang akan disinkronkan: {$totalBarang}", 'blue');

        foreach ($barang as $item) {
            try {
                // Update semua record pemakaian yang terkait dengan asset ini
                // yang masih aktif (tanggal_selesai null atau tanggal_selesai > hari ini)
                $updated = $db->table('pemakaian')
                    ->where('id_asset', $item['id_asset'])
                    ->where('(tanggal_selesai IS NULL OR tanggal_selesai >= CURDATE())', null, false)
                    ->update([
                        'id_pengguna' => $item['id_pengguna'],
                        'id_lokasi' => $item['id_lokasi']
                    ]);

                if ($updated > 0) {
                    $syncedCount++;
                    CLI::write("✓ Barang ID {$item['id']} (Asset ID {$item['id_asset']}): {$updated} record pemakaian diupdate", 'green');
                }

            } catch (\Exception $e) {
                CLI::error("✗ Error pada Barang ID {$item['id']}: " . $e->getMessage());
            }
        }

        CLI::write("\nSinkronisasi selesai!", 'yellow');
        CLI::write("Berhasil disinkronkan: {$syncedCount} barang", 'green');

        // Tampilkan statistik
        $this->showStatistics();
    }

    private function showStatistics()
    {
        $db = \Config\Database::connect();

        CLI::write("\n=== STATISTIK DATA ===", 'cyan');

        // Total pemakaian
        $totalPemakaian = $db->table('pemakaian')->countAllResults();
        CLI::write("Total Riwayat Pemakaian: {$totalPemakaian}", 'blue');

        // Total pemakaian aktif
        $pemakaianAktif = $db->table('pemakaian')
            ->where('(tanggal_selesai IS NULL OR tanggal_selesai >= CURDATE())', null, false)
            ->countAllResults();
        CLI::write("Total Pemakaian Aktif: {$pemakaianAktif}", 'blue');

        // Total barang
        $totalBarang = $db->table('barang')->countAllResults();
        CLI::write("Total Barang: {$totalBarang}", 'blue');

        // Total asset
        $totalAsset = $db->table('asset')->countAllResults();
        CLI::write("Total Asset: {$totalAsset}", 'blue');

        // Statistik pengguna
        $penggunaStats = $db->table('pemakaian')
            ->select('pengguna.nama_pengguna, COUNT(*) as total_pemakaian')
            ->join('pengguna', 'pengguna.id = pemakaian.id_pengguna', 'left')
            ->groupBy('pemakaian.id_pengguna')
            ->orderBy('total_pemakaian', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        CLI::write("\nTop 5 Pengguna dengan Pemakaian Terbanyak:", 'blue');
        foreach ($penggunaStats as $stat) {
            CLI::write("  {$stat['nama_pengguna']}: {$stat['total_pemakaian']} pemakaian", 'white');
        }
    }
} 