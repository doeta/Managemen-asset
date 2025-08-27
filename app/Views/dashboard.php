<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-left font-weight-bold text-primary">
            <i class= ""></i>Dashboard Manajemen Asset
          </h1>
          <p class="text-muted mb-0">Kementerian Komunikasi dan Informatika Kabupaten Kebumen</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right bg-light rounded px-3 py-2">
            <li class="breadcrumb-item"><a href="" class="text-primary">Home</a></li>
            <li class="breadcrumb-item active text-secondary">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid">

      <!-- Main Statistics Row -->
      <div class="row mb-4">
        <!-- Total Asset -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="info-box shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <span class="info-box-icon bg-transparent text-white" style="border-radius: 15px 0 0 15px;">
              <i class="fas fa-cubes"></i>
            </span>
            <div class="info-box-content text-white">
              <span class="info-box-text font-weight-light">Total Aset</span>
              <span class="info-box-number font-weight-bold"><?= number_format($totalAset) ?></span>
              <span class="info-box-more">Unit Terdaftar</span>
            </div>
          </div>
        </div>

        <!-- Total Asset Value -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="info-box shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <span class="info-box-icon bg-transparent text-white" style="border-radius: 15px 0 0 15px;">
              <i class="fas fa-money-bill-wave"></i>
            </span>
            <div class="info-box-content text-white">
              <span class="info-box-text font-weight-light">Nilai Aset</span>
              <span class="info-box-number font-weight-bold">Rp <?= number_format($totalNilaiAset ?? 0, 0, ',', '.') ?></span>
              <span class="info-box-more">Total Investasi</span>
            </div>
          </div>
        </div>

        <!-- Active Users -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="info-box shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <span class="info-box-icon bg-transparent text-white" style="border-radius: 15px 0 0 15px;">
              <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content text-white">
              <span class="info-box-text font-weight-light">Pengguna Aktif</span>
              <span class="info-box-number font-weight-bold"><?= number_format($penggunaAktif) ?></span>
              <span class="info-box-more">Pengguna Terdaftar</span>
            </div>
          </div>
        </div>

        <!-- Total Usage -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="info-box shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(135deg, #1bf463ff 0%, #0ceca1ff 100%);">
            <span class="info-box-icon bg-transparent text-white" style="border-radius: 15px 0 0 15px;">
              <i class="fas fa-clipboard-check"></i>
            </span>
            <div class="info-box-content text-white">
              <span class="info-box-text font-weight-light">Total Pemakaian</span>
              <span class="info-box-number font-weight-bold"><?= number_format($totalPemakaian) ?></span>
              <span class="info-box-more">Aktivitas Tercatat</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Asset Categories Section -->
      <div class="row mb-4">
        <div class="col-12">
          <h4 class="text-secondary mb-3">
            <i class="fas fa-boxes mr-2"></i>Distribusi Aset per Kategori
          </h4>
        </div>
      </div>
      
      <div class="row mb-5">
        <?php foreach ($barangPerKategori as $index => $kategori): ?>
          <?php 
            $gradients = [
              'linear-gradient(135deg, #5666acff 0%, #764ba2 100%)',
              'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
              'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
              'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
              'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
              'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            ];
            $currentGradient = $gradients[$index % count($gradients)];
          ?>
          <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="small-box shadow-sm border-0" style="background: <?= $currentGradient ?>; border-radius: 15px;">
              <div class="inner text-white p-3">
                <h3 class="font-weight-bold mb-2"><?= number_format($kategori['jumlah']) ?></h3>
                <p class="mb-1 font-weight-normal"><?= $kategori['nama_kategori'] ?></p>
                <div class="progress mb-2" style="height: 4px;">
                  <div class="progress-bar bg-white" role="progressbar" 
                       style="width: <?= ($kategori['jumlah'] / max(array_column($barangPerKategori, 'jumlah'))) * 100 ?>%"></div>
                </div>
              </div>
              <div class="icon" style="position: absolute; top: 15px; right: 15px;">
                <i class="fas fa-box" style="opacity: 0.3; font-size: 2rem;"></i>
              </div>
              <a href="/dataassets?kategori=<?= urlencode($kategori['nama_kategori']) ?>" 
                class="small-box-footer bg-dark bg-opacity-25 text-white text-decoration-none" 
                style="border-radius: 0 0 15px 15px; padding: 8px 15px;">
                <span class="font-weight-light">Lihat Detail</span>
                <i class="fas fa-arrow-circle-right ml-2"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Vehicle Statistics Section -->
      <div class="row mb-4">
        <div class="col-12">
          <h4 class="text-secondary mb-3">
            <i class="fas fa-car mr-2"></i>Statistik Kendaraan Dinas
          </h4>
        </div>
      </div>

      <div class="row mb-5">
        <!-- Car Statistics -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
            <div class="inner text-white p-3">
              <h3 class="font-weight-bold mb-2"><?= number_format($kendaraanMobil) ?></h3>
              <p class="mb-1 font-weight-normal">Kendaraan Mobil</p>
              <div class="progress mb-2" style="height: 4px;">
                <div class="progress-bar bg-white" role="progressbar" 
                     style="width: <?= ($kendaraanMobil + $kendaraanMotor) > 0 ? ($kendaraanMobil / ($kendaraanMobil + $kendaraanMotor)) * 100 : 0 ?>%"></div>
              </div>
            </div>
            <div class="icon" style="position: absolute; top: 15px; right: 15px;">
              <i class="fas fa-car" style="opacity: 0.3; font-size: 2rem;"></i>
            </div>
            <a href="/kendaraan/mobil" 
               class="small-box-footer bg-dark bg-opacity-25 text-white text-decoration-none" 
               style="border-radius: 0 0 15px 15px; padding: 8px 15px;">
              <span class="font-weight-light">Kelola Mobil</span>
              <i class="fas fa-cogs ml-2"></i>
            </a>
          </div>
        </div>
        
        <!-- Motorcycle Statistics -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px;">
            <div class="inner text-white p-3">
              <h3 class="font-weight-bold mb-2"><?= number_format($kendaraanMotor) ?></h3>
              <p class="mb-1 font-weight-normal">Kendaraan Motor</p>
              <div class="progress mb-2" style="height: 4px;">
                <div class="progress-bar bg-white" role="progressbar" 
                     style="width: <?= ($kendaraanMobil + $kendaraanMotor) > 0 ? ($kendaraanMotor / ($kendaraanMobil + $kendaraanMotor)) * 100 : 0 ?>%"></div>
              </div>
            </div>
            <div class="icon" style="position: absolute; top: 15px; right: 15px;">
              <i class="fas fa-motorcycle" style="opacity: 0.3; font-size: 2rem;"></i>
            </div>
            <a href="/kendaraan/motor" 
               class="small-box-footer bg-dark bg-opacity-25 text-white text-decoration-none" 
               style="border-radius: 0 0 15px 15px; padding: 8px 15px;">
              <span class="font-weight-light">Kelola Motor</span>
              <i class="fas fa-cogs ml-2"></i>
            </a>
          </div>
        </div>

        <?php /*
        <!-- Tax Due Alert -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 15px;">
            <div class="inner text-white p-3">
              <h3 class="font-weight-bold mb-2"><?= number_format($pajakJatuhTempo) ?></h3>
              <p class="mb-1 font-weight-normal">Pajak Jatuh Tempo</p>
              <small class="text-white-50">dalam 30 hari</small>
            </div>
            <div class="icon" style="position: absolute; top: 15px; right: 15px;">
              <i class="fas fa-exclamation-triangle" style="opacity: 0.4; font-size: 2rem;"></i>
            </div>
            <a href="/pajak/jatuh-tempo" 
               class="small-box-footer bg-dark bg-opacity-25 text-white text-decoration-none" 
               style="border-radius: 0 0 15px 15px; padding: 8px 15px;">
              <span class="font-weight-light">Lihat Detail</span>
              <i class="fas fa-calendar-alt ml-2"></i>
            </a>
          </div>
        </div>
        */ ?>
        

        <!-- Total Vehicles -->
        <div class="col-lg-3 col-md-6 col-12 mb-3">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 15px;">
            <div class="inner text-dark p-3">
              <h3 class="font-weight-bold mb-2"><?= number_format($kendaraanMobil + $kendaraanMotor) ?></h3>
              <p class="mb-1 font-weight-normal">Total Kendaraan</p>
              <small class="text-muted">Seluruh unit kendaraan dinas</small>
            </div>
            <div class="icon" style="position: absolute; top: 15px; right: 15px;">
              <i class="fas fa-warehouse" style="opacity: 0.3; font-size: 2rem;"></i>
            </div>
            <a href="/kendaraan" 
               class="small-box-footer bg-dark bg-opacity-25 text-white text-decoration-none" 
               style="border-radius: 0 0 15px 15px; padding: 8px 15px;">
              <span class="font-weight-light">Lihat Semua</span>
              <i class="fas fa-list ml-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Chart Section -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-header bg-gradient-primary text-white d-flex align-items-center" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold flex-grow-1">
                <i class="fas fa-chart-bar mr-2"></i>Statistik Aset per Kategori
              </h3>
              <button class="btn btn-light btn-sm" onclick="toggleChartType('kategoriChart')">
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
            <div class="card-body p-4">
              <canvas id="kategoriChart" height="120"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card shadow border-0" style="border-radius: 15px;">
            <div class="card-header bg-gradient-success text-white d-flex align-items-center" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold flex-grow-1">
                <i class="fas fa-chart-pie mr-2"></i>Distribusi Kendaraan
              </h3>
              <div class="badge badge-light">
                <?= number_format($kendaraanMobil + $kendaraanMotor) ?> Total
              </div>
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
            <div class="card-header bg-gradient-info text-white d-flex align-items-center justify-content-between" style="border-radius: 15px 15px 0 0;">
              <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-chart-line mr-2"></i>Overview Statistik Keseluruhan
              </h3>
              <div>
                <span class="badge badge-light mr-2">Total: <?= number_format($totalAset + $kendaraanMobil + $kendaraanMotor) ?> Unit</span>
                <button class="btn btn-light btn-sm" onclick="refreshChart('combinedChart')">
                  <i class="fas fa-refresh"></i>
                </button>
              </div>
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
  // Color schemes
  const colors = {
    primary: ['rgba(102, 126, 234, 0.8)', 'rgba(118, 75, 162, 0.8)', 'rgba(240, 147, 251, 0.8)', 'rgba(245, 87, 108, 0.8)', 'rgba(79, 172, 254, 0.8)', 'rgba(0, 242, 254, 0.8)'],
    primaryBorder: ['rgba(102, 126, 234, 1)', 'rgba(118, 75, 162, 1)', 'rgba(240, 147, 251, 1)', 'rgba(245, 87, 108, 1)', 'rgba(79, 172, 254, 1)', 'rgba(0, 242, 254, 1)'],
    vehicles: ['rgba(240, 147, 251, 0.8)', 'rgba(79, 172, 254, 0.8)'],
    vehiclesBorder: ['rgba(240, 147, 251, 1)', 'rgba(79, 172, 254, 1)']
  };

  // Asset Categories Chart
  const ctx1 = document.getElementById('kategoriChart').getContext('2d');
  let kategoriChart = new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($barangPerKategori, 'nama_kategori')) ?>,
      datasets: [{
        label: 'Jumlah Aset',
        data: <?= json_encode(array_column($barangPerKategori, 'jumlah')) ?>,
        backgroundColor: colors.primary,
        borderColor: colors.primaryBorder,
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
          cornerRadius: 10,
          callbacks: {
            label: function(context) {
              return 'Jumlah: ' + context.parsed.y.toLocaleString('id-ID') + ' unit';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: '#6c757d',
            callback: function(value) {
              return value.toLocaleString('id-ID');
            }
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
      },
      animation: {
        duration: 1500,
        easing: 'easeInOutQuart'
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
        backgroundColor: colors.vehicles,
        borderColor: colors.vehiclesBorder,
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
          cornerRadius: 10,
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed * 100) / total).toFixed(1);
              return context.label + ': ' + context.parsed.toLocaleString('id-ID') + ' unit (' + percentage + '%)';
            }
          }
        }
      },
      cutout: '60%',
      animation: {
        animateScale: true,
        duration: 1500
      }
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
          cornerRadius: 10,
          callbacks: {
            label: function(context) {
              return 'Total: ' + context.parsed.y.toLocaleString('id-ID') + ' unit';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: '#6c757d',
            callback: function(value) {
              return value.toLocaleString('id-ID');
            }
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
      },
      animation: {
        duration: 2000,
        easing: 'easeInOutQuart'
      }
    }
  });

  // Chart interaction functions
  function toggleChartType(chartId) {
    if (chartId === 'kategoriChart') {
      kategoriChart.config.type = kategoriChart.config.type === 'bar' ? 'doughnut' : 'bar';
      kategoriChart.update();
    }
  }

  function refreshChart(chartId) {
    if (chartId === 'combinedChart') {
      combinedChart.update();
    }
  }

  // Auto refresh charts every 5 minutes
  setInterval(() => {
    console.log('Auto refreshing charts...');
    // You can add AJAX calls here to fetch fresh data
  }, 300000);
</script>

<style>
  .content-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0 0 20px 20px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
  
  .info-box, .small-box {
    transition: all 0.3s ease;
    overflow: hidden;
  }
  
  .info-box:hover, .small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
  }
  
  .small-box .icon {
    transition: all 0.3s ease;
  }
  
  .small-box:hover .icon {
    transform: scale(1.1) rotate(5deg);
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
    padding-left: 20px;
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

  .progress {
    background-color: rgba(255,255,255,0.2);
  }

  .small-box-footer:hover {
    background-color: rgba(0,0,0,0.4) !important;
  }

  .badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
  }

  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }

  .info-box-number {
    animation: pulse 2s infinite;
  }

  @media (max-width: 768px) {
    .col-lg-3, .col-md-6 {
      margin-bottom: 1rem;
    }
    
    h4 {
      font-size: 1.1rem;
    }
    
    .info-box-number {
      font-size: 1.5rem !important;
    }
  }
</style>

<?= $this->endSection() ?>