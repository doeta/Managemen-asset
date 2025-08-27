<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BarangModel;
use App\Models\AssetModel;

class SyncAssetData extends BaseCommand
{
    protected $group       = 'Asset';
    protected $name        = 'asset:sync';
    protected $description = 'Sinkronisasi data asset dan barang';

    public function run(array $params)
    {
        CLI::write('Memulai sinkronisasi data asset...', 'yellow');
        
        $barangModel = new BarangModel();
        $assetModel = new AssetModel();
        $db = \Config\Database::connect();

        // Ambil semua asset
        $assets = $assetModel->findAll();
        $totalAssets = count($assets);
        $syncedCount = 0;

        CLI::write("Total asset yang akan disinkronkan: {$totalAssets}", 'blue');

        foreach ($assets as $asset) {
            try {
                // Hitung jumlah barang dengan status tersedia
                $tersedia = $db->table('barang')
                              ->where('id_asset', $asset['id'])
                              ->where('status', 'tersedia')
                              ->countAllResults();

                // Update jumlah di tabel asset
                $assetModel->update($asset['id'], ['jumlah_barang' => $tersedia]);
                
                $syncedCount++;
                CLI::write("✓ Asset ID {$asset['id']} ({$asset['nama_barang']}): {$tersedia} tersedia", 'green');
                
            } catch (\Exception $e) {
                CLI::error("✗ Error pada Asset ID {$asset['id']}: " . $e->getMessage());
            }
        }

        CLI::write("\nSinkronisasi selesai!", 'yellow');
        CLI::write("Berhasil disinkronkan: {$syncedCount}/{$totalAssets} asset", 'green');
        
        // Tampilkan statistik
        $this->showStatistics();
    }

    private function showStatistics()
    {
        $db = \Config\Database::connect();
        
        CLI::write("\n=== STATISTIK DATA ===", 'cyan');
        
        // Total barang per status
        $statusStats = $db->table('barang')
                         ->select('status, COUNT(*) as total')
                         ->groupBy('status')
                         ->get()
                         ->getResultArray();
        
        CLI::write("Status Barang:", 'blue');
        foreach ($statusStats as $stat) {
            CLI::write("  {$stat['status']}: {$stat['total']}", 'white');
        }
        
        // Total pemakaian
        $totalPemakaian = $db->table('pemakaian')->countAllResults();
        CLI::write("Total Riwayat Pemakaian: {$totalPemakaian}", 'blue');
        
        // Total asset
        $totalAsset = $db->table('asset')->countAllResults();
        CLI::write("Total Asset: {$totalAsset}", 'blue');
    }
} 