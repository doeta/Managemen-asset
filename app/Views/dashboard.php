<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-left font-weight-bold text-primary">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right bg-light rounded px-3 py-2">
            <li class="breadcrumb-item"><a href="#" class="text-primary">Home</a></li>
            <li class="breadcrumb-item active text-secondary">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid">

      <!-- Asset Statistics Section -->
      <div class="row mb-4">
        <div class="col-12">
          <h4 class="text-secondary mb-3">
            <i class="fas fa-boxes mr-2"></i>Jumlah Asset
          </h4>
        </div>
      </div>
      
      <div class="row mb-5">
        <?php foreach ($barangPerKategori as $index => $kategori): ?>
          <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
              <div class="inner text-white">
                <h3 class="font-weight-bold"><?= number_format($kategori['jumlah']) ?></h3>
                <p class="mb-0 font-weight-light"><?= $kategori['nama_kategori'] ?></p>
              </div>
              <div class="icon">
                <i class="fas fa-box" style="opacity: 0.3;"></i>
              </div>
              <div class="small-box-footer bg-dark bg-opacity-25 text-white rounded-bottom" style="border-radius: 0 0 15px 15px;">
                <span class="font-weight-light">Total Items</span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Vehicle Statistics Section -->
      <div class="row mb-4">
        <div class="col-12">
          <h4 class="text-secondary mb-3">
            <i class="fas fa-car mr-2"></i>Jumlah Kendaraan
          </h4>
        </div>
      </div>

      <div class="row mb-5">
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
            <div class="inner text-white">
              <h3 class="font-weight-bold"><?= number_format($kendaraanMobil) ?></h3>
              <p class="mb-0 font-weight-light">Kendaraan Mobil</p>
            </div>
            <div class="icon">
              <i class="fas fa-car" style="opacity: 0.3;"></i>
            </div>
            <div class="small-box-footer bg-dark bg-opacity-25 text-white rounded-bottom" style="border-radius: 0 0 15px 15px;">
              <span class="font-weight-light">Total Units</span>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px;">
            <div class="inner text-white">
              <h3 class="font-weight-bold"><?= number_format($kendaraanMotor) ?></h3>
              <p class="mb-0 font-weight-light">Kendaraan Motor</p>
            </div>
            <div class="icon">
              <i class="fas fa-motorcycle" style="opacity: 0.3;"></i>
            </div>
            <div class="small-box-footer bg-dark bg-opacity-25 text-white rounded-bottom" style="border-radius: 0 0 15px 15px;">
              <span class="font-weight-light">Total Units</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-chart-bar mr-2"></i>Statistik Aset per Kategori
              </h3>
            </div>
            <div class="card-body p-4">
              <canvas id="kategoriChart" height="120"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-header bg-gradient-success text-white" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-chart-pie mr-2"></i>Distribusi Kendaraan
              </h3>
            </div>
            <div class="card-body p-4">
              <canvas id="kendaraanChart" height="120"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Combined Statistics Chart -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-chart-line mr-2"></i>Overview Statistik Keseluruhan
              </h3>
            </div>
            <div class="card-body p-4">
              <canvas id="combinedChart" height="80"></canvas>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </div><!-- /.content -->
</div><!-- /.wrapper -->

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Asset Categories Chart
  const ctx1 = document.getElementById('kategoriChart').getContext('2d');
  const kategoriChart = new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($barangPerKategori, 'nama_kategori')) ?>,
      datasets: [{
        label: 'Jumlah Aset',
        data: <?= json_encode(array_column($barangPerKategori, 'jumlah')) ?>,
        backgroundColor: [
          'rgba(102, 126, 234, 0.8)',
          'rgba(118, 75, 162, 0.8)',
          'rgba(240, 147, 251, 0.8)',
          'rgba(245, 87, 108, 0.8)',
          'rgba(79, 172, 254, 0.8)',
          'rgba(0, 242, 254, 0.8)'
        ],
        borderColor: [
          'rgba(102, 126, 234, 1)',
          'rgba(118, 75, 162, 1)',
          'rgba(240, 147, 251, 1)',
          'rgba(245, 87, 108, 1)',
          'rgba(79, 172, 254, 1)',
          'rgba(0, 242, 254, 1)'
        ],
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleColor: '#fff',
          bodyColor: '#fff',
          cornerRadius: 10
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: '#6c757d'
          },
          grid: {
            color: 'rgba(0,0,0,0.1)'
          }
        },
        x: {
          ticks: {
            color: '#6c757d'
          },
          grid: {
            display: false
          }
        }
      }
    }
  });

  // Vehicle Distribution Pie Chart
  const ctx2 = document.getElementById('kendaraanChart').getContext('2d');
  const kendaraanChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Mobil', 'Motor'],
      datasets: [{
        label: 'Jumlah Kendaraan',
        data: [<?= $kendaraanMobil ?>, <?= $kendaraanMotor ?>],
        backgroundColor: [
          'rgba(240, 147, 251, 0.8)',
          'rgba(79, 172, 254, 0.8)'
        ],
        borderColor: [
          'rgba(240, 147, 251, 1)',
          'rgba(79, 172, 254, 1)'
        ],
        borderWidth: 3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            usePointStyle: true,
            color: '#6c757d'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleColor: '#fff',
          bodyColor: '#fff',
          cornerRadius: 10
        }
      },
      cutout: '60%'
    }
  });

  // Combined Overview Chart
  const ctx3 = document.getElementById('combinedChart').getContext('2d');
  
  // Prepare data for combined chart
  const assetLabels = <?= json_encode(array_column($barangPerKategori, 'nama_kategori')) ?>;
  const assetData = <?= json_encode(array_column($barangPerKategori, 'jumlah')) ?>;
  
  const allLabels = [...assetLabels, 'Kendaraan Mobil', 'Kendaraan Motor'];
  const allData = [...assetData, <?= $kendaraanMobil ?>, <?= $kendaraanMotor ?>];
  
  const combinedChart = new Chart(ctx3, {
    type: 'line',
    data: {
      labels: allLabels,
      datasets: [{
        label: 'Total Items',
        data: allData,
        borderColor: 'rgba(102, 126, 234, 1)',
        backgroundColor: 'rgba(102, 126, 234, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: 'rgba(102, 126, 234, 1)',
        pointBorderColor: '#fff',
        pointBorderWidth: 3,
        pointRadius: 8,
        pointHoverRadius: 12
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleColor: '#fff',
          bodyColor: '#fff',
          cornerRadius: 10
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: '#6c757d'
          },
          grid: {
            color: 'rgba(0,0,0,0.1)'
          }
        },
        x: {
          ticks: {
            color: '#6c757d',
            maxRotation: 45
          },
          grid: {
            display: false
          }
        }
      }
    }
  });
</script>

<style>
  .content-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0 0 20px 20px;
    margin-bottom: 2rem;
  }
  
  .small-box {
    transition: all 0.3s ease;
    overflow: hidden;
  }
  
  .small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
  }
  
  .small-box .icon {
    transition: all 0.3s ease;
  }
  
  .small-box:hover .icon {
    transform: scale(1.1);
  }
  
  .card {
    transition: all 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
  }
  
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }
  
  .bg-gradient-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%) !important;
  }
  
  .bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
  }
  
  h4 {
    position: relative;
    padding-left: 15px;
  }
  
  h4:before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
  }
</style>

<?= $this->endSection() ?>