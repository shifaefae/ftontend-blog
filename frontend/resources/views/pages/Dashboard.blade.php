<!-- resources/views/dashboard.blade.php -->
@extends('layout.App')

@section('title', 'Dashboard - Portal Blog')

@section('content')
<div class="dashboard-container" style="padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:  #fbfbfc 100%; min-height: 100vh;">
    
    <!-- Title -->
    <h1 style="font-size: 28px; margin-bottom: 25px; color: #2d3748; font-weight: 700; background: linear-gradient(135deg, #2d3748 0%, #4988C4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Dashboard Portal Blog</h1>
    
<!-- Stats Cards -->
<div class="stats-cards" style="display: flex; gap: 20px; margin-bottom: 35px; flex-wrap: wrap;">
    <div class="stat-card" style="padding: 25px 35px; 
                                  background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); 
                                  border-radius: 12px; 
                                  flex: 1;
                                  min-width: 200px;
                                  text-align: center;
                                  transform: translateY(0);
                                  transition: all 0.3s ease;">
        <div style="font-size: 14px; color: rgba(255, 255, 255, 0.95); margin-bottom: 10px; font-weight: 500; letter-spacing: 0.5px;">📰 Jumlah Berita</div>
        <div style="font-size: 36px; font-weight: bold; color: #fff;">{{ $jumlahBerita ?? 131 }}</div>
    </div>
    
    <div class="stat-card" style="padding: 25px 35px; 
                                  background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); 
                                  border-radius: 12px; 
                                  flex: 1;
                                  min-width: 200px;
                                  text-align: center;
                                  transform: translateY(0);
                                  transition: all 0.3s ease;">
        <div style="font-size: 14px; color: rgba(255, 255, 255, 0.95); margin-bottom: 10px; font-weight: 500; letter-spacing: 0.5px;">✅ Berita Published</div>
        <div style="font-size: 36px; font-weight: bold; color: #fff;">{{ $beritaPublished ?? 98 }}</div>
    </div>

    <div class="stat-card" style="padding: 25px 35px; 
                                  background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); 
                                  border-radius: 12px; 
                                  flex: 1;
                                  min-width: 200px;
                                  text-align: center;
                                  transform: translateY(0);
                                  transition: all 0.3s ease;">
        <div style="font-size: 14px; color: rgba(255, 255, 255, 0.95); margin-bottom: 10px; font-weight: 500; letter-spacing: 0.5px;">📝 Berita Draft</div>
        <div style="font-size: 36px; font-weight: bold; color: #fff;">{{ $beritaDraft ?? 33 }}</div>
    </div>
</div>

<style>
    .stat-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15) !important;  /* Bayangan netral */
    }
</style>
    
    <!-- Diagram Viewers -->
    <div class="diagram-container" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 35px; border: 2px solid transparent; background-clip: padding-box; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #4988C4 0%, #4988C4 50%, #4988C4 100%); border-radius: 16px 16px 0 0;"></div>
        <h2 style="font-size: 20px; margin-bottom: 25px; color: #2d3748; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">📊</span>
            Diagram Data Viewers 
        </h2>
        <canvas id="myChart" style="max-height: 350px;"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        
        const data = {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [{
                label: 'Viewers',
                data: [879, 3595, 2569, 1995, 3678, 500, 4278],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                pointRadius: 6,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 8,
                tension: 0.4,
                fill: true
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#2d3748',
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(45, 55, 72, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        borderColor: '#4988C4',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return 'Viewers: ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4500,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            },
                            font: {
                                size: 12
                            },
                            color: '#4a5568'
                        },
                        grid: {
                            color: 'rgba(102, 126, 234, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#4a5568'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        const myChart = new Chart(ctx, config);
    </script>
    
<!-- Berita Section -->
<div class="berita-section" style="display: grid; grid-template-columns: 73% 25%; gap: 2%; margin-bottom: 20px;">
    
    <!-- Berita Terbaru (73%) -->
    <div>
        <h2 style="font-size: 20px; margin-bottom: 20px; color: #2d3748; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">🔥</span>
            Berita Terbaru
        </h2>
        <div class="berita-box" style="width: 100%; 
                                       background: white; 
                                       border-radius: 16px;
                                       overflow: hidden;
                                       box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                                       border: 2px solid transparent;
                                       position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #4988C4 0%, #4988C4 100%);"></div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #2d3748; width: 5%; font-size: 13px;">No</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #2d3748; width: 12%; font-size: 13px;">Gambar</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #2d3748; width: 30%; font-size: 13px;">Judul</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #2d3748; width: 8%; font-size: 13px;">Admin</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #2d3748; width: 15%; font-size: 13px;">Nama</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #2d3748; width: 15%; font-size: 13px;">Status</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #2d3748; width: 15%; font-size: 13px;">Waktu</th>
                    </tr>
                </thead>
                <tbody id="beritaTerbaruList">
                    <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                        <td style="padding: 16px; text-align: center; color: #4a5568; font-weight: 600;">1</td>
                        <td style="padding: 16px;">
                            <img src="https://via.placeholder.com/80x60" alt="Berita 1" style="width: 80px; height: 60px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </td>
                        <td style="padding: 16px; color: #2d3748; font-weight: 500; line-height: 1.5;">Efek Krisis RAM, Toko di Jepang Sampai "Ngebet" Beli PC Lama Pelanggan</td>
                        <td style="padding: 16px; text-align: center;">
                            <img src="https://via.placeholder.com/40" alt="Admin" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4988C4;">
                        </td>
                        <td style="padding: 16px; color: #4a5568; font-weight: 500;">Admin Satu</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="background: #4988C4 100%; 
                                         color: white; 
                                         padding: 6px 14px; 
                                         border-radius: 20px; 
                                         font-size: 12px; 
                                         font-weight: 600;">Published</span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: #718096; font-size: 13px; font-weight: 500;">2 jam lalu</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                        <td style="padding: 16px; text-align: center; color: #4a5568; font-weight: 600;">2</td>
                        <td style="padding: 16px;">
                            <img src="https://via.placeholder.com/80x60" alt="Berita 2" style="width: 80px; height: 60px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </td>
                        <td style="padding: 16px; color: #2d3748; font-weight: 500; line-height: 1.5;">Ribuan Warga Mojokerto Ikuti “Mlaku Bareng Gus Bupati” di Stadion Gajah Mada</td>
                        <td style="padding: 16px; text-align: center;">
                            <img src="https://via.placeholder.com/40" alt="Admin" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4988C4;">
                        </td>
                        <td style="padding: 16px; color: #4a5568; font-weight: 500;">Admin Dua</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); 
                                         color: #ffff; 
                                         padding: 6px 14px; 
                                         border-radius: 20px; 
                                         font-size: 12px; 
                                         font-weight: 600;">Draft</span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: #718096; font-size: 13px; font-weight: 500;">5 jam lalu</td>
                    </tr>
                    <tr style="transition: background 0.2s;">
                        <td style="padding: 16px; text-align: center; color: #4a5568; font-weight: 600;">3</td>
                        <td style="padding: 16px;">
                            <img src="https://via.placeholder.com/80x60" alt="Berita 3" style="width: 80px; height: 60px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </td>
                        <td style="padding: 16px; color: #2d3748; font-weight: 500; line-height: 1.5;">Pihak Tergugat 1 (Bapenda Kab Sukabumi), Tidak Bisa Hadirkan Saksi Fakta Dan Alat Bukti Dalam Lanjutan Sidang Gugatan Bayar Pajak Waris Tanah Natadipura</td>
                        <td style="padding: 16px; text-align: center;">
                            <img src="https://via.placeholder.com/40" alt="Admin" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4988C4;">
                        </td>
                        <td style="padding: 16px; color: #4a5568; font-weight: 500;">Admin Tiga</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; ">Published</span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: #718096; font-size: 13px; font-weight: 500;">1 hari lalu</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Berita Terpopuler (25%) -->
    <div>
        <h2 style="font-size: 20px; margin-bottom: 20px; color: #2d3748; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">⭐</span>
            Berita Terpopuler
        </h2>
        <div class="berita-box" style="width: 100%; 
                                       background: white; 
                                       border-radius: 16px;
                                       padding: 20px;
                                       box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                                       border: 2px solid transparent;
                                       position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #4988C4 0%, #4988C4 100%); border-radius: 16px 16px 0 0;"></div>
            <div id="beritaTerpopulerList">
                <!-- Item 1 -->
                <div style="padding: 15px; border-radius: 12px; margin-bottom: 15px; background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); border-left: 4px solid #4988C4; transition: all 0.3s;">
                    <div style="color: #ffff; font-weight: 600; font-size: 13px; line-height: 1.5; margin-bottom: 10px;">Bahlil Siap Perangi Mafia Migas, Minta Dukungan Ulama</div>
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                        <span style="background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); color: white; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600;">POLITIK</span>
                        <span style="color: #ffff; font-size: 12px; font-weight: 600;">👁 15.3k</span>
                        <span style="color: #ffff; font-size: 11px;">3 hari lalu</span>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div style="padding: 15px; border-radius: 12px; margin-bottom: 15px; background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); border-left: 4px solid #4988C4; transition: all 0.3s;">
                    <div style="color: #ffff; font-weight: 600; font-size: 13px; line-height: 1.5; margin-bottom: 10px;">Indonesia Percepat Pembangunan Infrastruktur Digital Nasional Internet</div>
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                        <span style="background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); color: white; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600;">TEKNOLOGI</span>
                        <span style="color: #ffff; font-size: 12px; font-weight: 600;">👁 12.8k</span>
                        <span style="color: #ffff; font-size: 11px;">1 minggu lalu</span>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div style="padding: 15px; border-radius: 12px; background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); border-left: 4px solid #4988C4; transition: all 0.3s;">
                    <div style="color: #ffff; font-weight: 600; font-size: 13px; line-height: 1.5; margin-bottom: 10px;">Erick Thohir Tancap Gas, Industri Olahraga Ditarget Jadi Mesin Ekonomi Baru Nasional</div>
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                        <span style="background: linear-gradient(135deg, #4988C4 0%, #4988C4 100%); color: white; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600;">OLAHRAGA</span>
                        <span style="color: #ffff; font-size: 12px; font-weight: 600;">👁 10.5k</span>
                        <span style="color: #ffff; font-size: 11px;">2 hari lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

</div>

<style>
    tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%) !important;
    }
</style>

@endsection