@extends('layouts/app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(139, 0, 0, 0.3);
    }
    
    .hero-section h2 {
        color: white;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .hero-section p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
    }
    
    .description-box {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-left: 4px solid #8B0000;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .value-card {
        border-radius: 20px;
        transition: all 0.3s ease;
        border: none;
        background: white;
        position: relative;
        overflow: hidden;
    }
    
    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .value-card:hover::before {
        transform: scaleX(1);
    }
    
    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--icon-bg);
        transition: all 0.3s ease;
    }
    
    .value-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .value-card h5 {
        color: #2d3748;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .value-card p {
        color: #718096;
        line-height: 1.6;
    }
    
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 3rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, #8B0000, #DC143C);
        border-radius: 2px;
    }
    
    /* Layout untuk 7 cards */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    @media (min-width: 768px) {
        .values-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .values-grid .value-card:nth-child(7) {
            grid-column: 2;
        }
    }
    
    @media (min-width: 992px) {
        .values-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .values-grid .value-card:nth-child(5),
        .values-grid .value-card:nth-child(6),
        .values-grid .value-card:nth-child(7) {
            grid-column: auto;
        }
        
        .values-grid .value-card:nth-child(5) {
            grid-column: 1 / 2;
        }
        
        .values-grid .value-card:nth-child(6) {
            grid-column: 2 / 3;
        }
        
        .values-grid .value-card:nth-child(7) {
            grid-column: 3 / 4;
        }
    }
</style>

<div class="container">
    <div class="page-inner">
        <!-- Hero Section -->
        <div class="hero-section text-center">
            <h2 class="fw-bold">Selamat Datang di SI-AKSI</h2>
            <p class="mb-0">Sistem Internalisasi berAKHLAK Secara Integratif</p>
        </div>

        <!-- Deskripsi -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="description-box">
                    <p class="lead mb-0">
                        <strong>SI-AKSI</strong> adalah platform inovatif untuk menginternalisasi nilai-nilai 
                        <span class="fw-bold" style="color: #8B0000;">berAKHLAK</span> secara <em>integratif</em>.  
                        Sistem ini dirancang untuk memperkuat budaya kerja ASN melalui 7 nilai dasar yang menjadi 
                        pedoman sikap dan perilaku kerja sehari-hari, menciptakan lingkungan kerja yang profesional, 
                        harmonis, dan berorientasi pada pelayanan terbaik.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold section-title">Nilai-Nilai berAKHLAK</h3>
        </div>

        <!-- Cards Nilai AKHLAK (7 poin) -->
        <div class="values-grid">
            @php
                $values = [
                    [
                        'icon' => 'bi-people-fill', 
                        'title' => 'Berorientasi Pelayanan', 
                        'desc' => 'Komitmen memberikan pelayanan prima yang melampaui harapan demi kepuasan masyarakat', 
                        'color' => '#667eea',
                        'bg' => 'rgba(102, 126, 234, 0.1)'
                    ],
                    [
                        'icon' => 'bi-clipboard-check-fill', 
                        'title' => 'Akuntabel', 
                        'desc' => 'Bertanggung jawab penuh atas kepercayaan yang diberikan dan berintegritas tinggi', 
                        'color' => '#48bb78',
                        'bg' => 'rgba(72, 187, 120, 0.1)'
                    ],
                    [
                        'icon' => 'bi-lightbulb-fill', 
                        'title' => 'Kompeten', 
                        'desc' => 'Terus belajar, berkembang, dan meningkatkan kapabilitas diri secara berkelanjutan', 
                        'color' => '#f6ad55',
                        'bg' => 'rgba(246, 173, 85, 0.1)'
                    ],
                    [
                        'icon' => 'bi-heart-fill', 
                        'title' => 'Harmonis', 
                        'desc' => 'Saling peduli, menghormati, dan menghargai perbedaan dalam keberagaman', 
                        'color' => '#fc8181',
                        'bg' => 'rgba(252, 129, 129, 0.1)'
                    ],
                    [
                        'icon' => 'bi-shield-fill-check', 
                        'title' => 'Loyal', 
                        'desc' => 'Berdedikasi tinggi dan mengutamakan kepentingan Bangsa dan Negara di atas segalanya', 
                        'color' => '#4299e1',
                        'bg' => 'rgba(66, 153, 225, 0.1)'
                    ],
                    [
                        'icon' => 'bi-lightning-charge-fill', 
                        'title' => 'Adaptif', 
                        'desc' => 'Terus berinovasi dan antusias dalam menggerakkan serta menghadapi perubahan dengan positif', 
                        'color' => '#9f7aea',
                        'bg' => 'rgba(159, 122, 234, 0.1)'
                    ],
                    [
                        'icon' => 'bi-diagram-3-fill', 
                        'title' => 'Kolaboratif', 
                        'desc' => 'Membangun kerja sama yang sinergis dan produktif untuk mencapai tujuan bersama', 
                        'color' => '#ed8936',
                        'bg' => 'rgba(237, 137, 54, 0.1)'
                    ],
                ];
            @endphp

            @foreach ($values as $value)
            <div class="value-card shadow-sm h-100" style="--card-color: {{ $value['color'] }}; --icon-bg: {{ $value['bg'] }};">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper">
                        <i class="bi {{ $value['icon'] }}" style="font-size: 2.5rem; color: {{ $value['color'] }};"></i>
                    </div>
                    <h5 class="fw-bold">{{ $value['title'] }}</h5>
                    <p class="small mb-0">{{ $value['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-4 mb-5">
            <p class="text-muted">
                <i class="bi bi-info-circle me-2"></i>
                Mari bersama-sama mewujudkan nilai-nilai berAKHLAK dalam setiap aspek pekerjaan kita
            </p>
        </div>

    </div>
</div>
@endsection

@section('script')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection