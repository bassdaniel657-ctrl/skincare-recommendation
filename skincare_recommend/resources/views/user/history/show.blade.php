@extends('layouts.user')

@section('content')

<div class="content-header">
    <div class="container-fluid text-center">
        <h1 class="m-0 text-primary">
            <i class="fas fa-history mr-2"></i>
            Detail Riwayat Pencarian
        </h1>
        <p class="text-muted">Analisis lengkap hasil rekomendasi dan penilaian Anda</p>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    {{-- ===================== --}}
    {{-- QUERY BADGES --}}
    {{-- ===================== --}}
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="card bg-gradient-primary text-white shadow-lg">
                <div class="card-body text-center py-4">

                    <h4 class="mb-3">
                        <i class="fas fa-search mr-2"></i>
                        Pencarian Anda:
                    </h4>

                    @php $queryArray = explode('|', $history->query); @endphp

                    @foreach ($queryArray as $ingredient)
                        <span class="badge badge-light text-primary px-3 py-2 mr-2 mb-2">
                            <i class="fas fa-leaf mr-1"></i>{{ trim($ingredient) }}
                        </span>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- COSINE TABLE --}}
    {{-- ===================== --}}
    <div class="row justify-content-center mb-5">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-calculator mr-2"></i>
                        Rekomendasi (Cosine Similarity)
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kandungan</th>
                                    <th>Kandungan Cocok</th>
                                    <th>Status Relevansi</th>
                                    <th>Penilaian Anda</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($history->evaluations->where('similarity_type','cosine') as $evaluation)

                                @php
                                    $rec = $history->recommendations
                                        ->where('product_id',$evaluation->product_id)
                                        ->where('similarity_type','cosine')
                                        ->first();
                                @endphp

                                <tr class="{{ ($rec->is_relevant ?? false) ? 'table-success' : 'table-warning' }}">

                                    <td><strong>{{ $rec->product->product_name ?? '-' }}</strong></td>

                                    <td><small>{{ Str::limit($rec->product->ingredients ?? '-',80) }}</small></td>

                                    <td>
                                        <span class="badge badge-success mb-1">
                                            {{ $rec->common_ingredients_count }} kandungan
                                        </span>

                                        @if($rec->common_ingredients_count > 0)
                                            <div>
                                                @foreach($rec->common_ingredients as $ing)
                                                    <span class="badge badge-info mr-1 mb-1">{{ $ing }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <small class="text-muted">Tidak ada kecocokan</small>
                                        @endif
                                    </td>

                                    <td>
                                        @if($rec->is_relevant)
                                            <span class="badge badge-success px-3 py-2">Relevan</span>
                                        @else
                                            <span class="badge badge-warning px-3 py-2">Tidak Relevan</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($rec->user_feedback === 1)
                                            <span class="badge badge-success px-3 py-2">Disukai</span>
                                        @elseif($rec->user_feedback === 0)
                                            <span class="badge badge-danger px-3 py-2">Tidak Disukai</span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-2">Belum Dinilai</span>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- EUCLIDEAN TABLE --}}
    {{-- ===================== --}}
    <div class="row justify-content-center mb-5">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-ruler mr-2"></i>
                        Rekomendasi (Euclidean Distance)
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kandungan</th>
                                    <th>Kandungan Cocok</th>
                                    <th>Status Relevansi</th>
                                    <th>Penilaian Anda</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($history->evaluations->where('similarity_type','euclidean') as $evaluation)

                                @php
                                    $rec = $history->recommendations
                                        ->where('product_id',$evaluation->product_id)
                                        ->where('similarity_type','euclidean')
                                        ->first();
                                @endphp

                                <tr class="{{ ($rec->is_relevant ?? false) ? 'table-success' : 'table-warning' }}">

                                    <td><strong>{{ $rec->product->product_name ?? '-' }}</strong></td>

                                    <td><small>{{ Str::limit($rec->product->ingredients ?? '-',80) }}</small></td>

                                    <td>
                                        <span class="badge badge-success mb-1">{{ $rec->common_ingredients_count }} kandungan</span>

                                        @if($rec->common_ingredients_count > 0)
                                            @foreach($rec->common_ingredients as $ing)
                                                <span class="badge badge-info mr-1 mb-1">{{ $ing }}</span>
                                            @endforeach
                                        @else
                                            <small class="text-muted">Tidak ada kecocokan</small>
                                        @endif
                                    </td>

                                    <td>
                                        @if($rec->is_relevant)
                                            <span class="badge badge-success px-3 py-2">Relevan</span>
                                        @else
                                            <span class="badge badge-warning px-3 py-2">Tidak Relevan</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($rec->user_feedback === 1)
                                            <span class="badge badge-success px-3 py-2">Disukai</span>
                                        @elseif($rec->user_feedback === 0)
                                            <span class="badge badge-danger px-3 py-2">Tidak Disukai</span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-2">Belum Dinilai</span>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BACK BUTTON --}}
    <div class="row justify-content-center mb-5">
        <div class="col-12 text-center">
            <a href="{{ route('user.history') }}" class="btn btn-secondary btn-lg px-5 py-3">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Riwayat
            </a>
        </div>
    </div>

</div>
</section>

@endsection
