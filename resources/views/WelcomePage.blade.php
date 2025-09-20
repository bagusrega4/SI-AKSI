@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Header -->
        <div class="text-center py-4">
            <h2 class="fw-bold text-primary">Selamat Datang di <span class="text-dark">SI-AKSI</span></h2>
            <p class="text-muted">Sistem Internalisasi berAKHLAK Secara Integratif</p>
            <hr class="w-25 mx-auto">
        </div>

        <!-- Deskripsi -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <p class="lead">
                    <strong>SI-AKSI</strong> adalah platform untuk menginternalisasi nilai-nilai 
                    <span class="text-primary">berAKHLAK</span> secara <em>integratif</em>.  
                    Sistem ini dirancang untuk memperkuat budaya kerja ASN melalui nilai dasar yang menjadi 
                    pedoman sikap dan perilaku kerja sehari-hari.
                </p>
            </div>
        </div>

        <!-- Cards Nilai AKHLAK (6 poin) -->
        <div class="row g-3 mb-5">
            @php
                $values = [
                    ['icon' => 'bi-people', 'title' => 'Berorientasi Pelayanan', 'desc' => 'Memberikan pelayanan terbaik dengan sepenuh hati demi kepuasan masyarakat.', 'color' => 'primary'],
                    ['icon' => 'bi-clipboard-check', 'title' => 'Akuntabel', 'desc' => 'Bekerja secara jujur, bertanggung jawab, dan transparan.', 'color' => 'success'],
                    ['icon' => 'bi-lightbulb', 'title' => 'Kompeten', 'desc' => 'Terus belajar dan mengembangkan kapabilitas untuk memberikan hasil terbaik.', 'color' => 'warning'],
                    ['icon' => 'bi-heart', 'title' => 'Harmonis', 'desc' => 'Menghargai perbedaan, menjaga keharmonisan, dan bekerja sama dengan baik.', 'color' => 'danger'],
                    ['icon' => 'bi-shield-lock', 'title' => 'Loyal', 'desc' => 'Setia pada bangsa, negara, dan organisasi dengan penuh integritas.', 'color' => 'info'],
                    ['icon' => 'bi-lightning', 'title' => 'Adaptif', 'desc' => 'Mampu menyesuaikan diri dengan perubahan dan inovasi.', 'color' => 'secondary'],
                ];
            @endphp

            @foreach ($values as $value)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <div class="mb-3 text-{{ $value['color'] }}">
                            <i class="bi {{ $value['icon'] }}" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold">{{ $value['title'] }}</h5>
                        <p class="small text-muted">
                            {{ $value['desc'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection

@section('script')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
