@extends('layout.template')

@section('styles')
    <style>
        #hero {
            background-image: url('{{ asset('icon/bg-balairung.jpg') }}');
            position: relative;
            height: calc(100vh - 56px);
            color: white;
        }

        #hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .btn-start {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            background-color: #ffffff;
            color: #333;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-start:hover {
            background-color: #e0e0e0;
        }
    </style>
@endsection

@section('content')
    <section id="hero" class="d-flex align-items-center justify-content-center text-center">
        <div class="container hero-content">
            <h1 class="display-5 fw-bold">Jelajahi Tata RuangKita<br>Wilayah UGM</h1>
            <p class="lead mt-3">Memetakan penggunaan tata ruang wilayah kampus Universitas Gadjah Mada<br>melalui visualisasi interaktif berbasis WebGIS</p>
            <a href="{{ route('map') }}" class="btn btn-start mt-4">Mulai</a>
        </div>
    </section>
@endsection
