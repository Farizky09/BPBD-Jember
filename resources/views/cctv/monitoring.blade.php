@extends('layouts.master')

@section('main')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="page-title">
                    <i class="fas fa-video"></i> Monitoring CCTV Real-Time
                </h1>
                <p class="text-muted">Pantau tingkat air secara real-time dari sistem CCTV</p>
            </div>
        </div>

        @include('components.cctv-realtime-monitor')
    </div>

    <style>
        .page-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .container-fluid {
            padding: 20px;
        }
    </style>
@endsection
