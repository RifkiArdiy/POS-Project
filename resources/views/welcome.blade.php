@extends('layouts.template')

@section('content')
    <div class="row">
        <!-- Bento Boxes -->
        <a class="col-md-4 mb-3" href="{{ url('penjualan') }}">
            <div class="card text-dark h-100 shadow-sm card-outline card-primary">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="card-title">Total Penjualan</h5>
                    <p class="card-text h3 mb-0 mt-auto text-end">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </a>

        <a class="col-md-4 mb-3" href="{{ url('stok') }}">
            <div class="card text-dark h-100 shadow-sm card-outline card-danger">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="card-title">Total Stok</h5>
                    <p class="card-text h3 mb-0 mt-auto text-end">{{ $totalStok }}</p>
                </div>
            </div>
        </a>

        <a class="col-md-4 mb-3" href="{{ url('user') }}">
            <div class="card text-dark h-100 shadow-sm card-outline card-warning">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text h3 mb-0 mt-auto text-end">{{ $totalUser }}</p>
                </div>
            </div>
        </a>
    </div>
    <!-- Chart -->
    <div class="col">
        <div class="card">
            <div class="card-header">Total Penjualan Detail per Bulan</div>
            <div class="card-body">
                <canvas id="penjualanChart"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <!-- USERS LIST -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Latest Orders</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    {{-- <th>Kasir</th> --}}
                                    <th>Pembeli</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualanTerakhir as $item)
                                    <tr>
                                        <td>{{ $item->penjualan_kode }}</td>
                                        {{-- <td>{{ $item->user->nama ?? '-' }}</td> --}}
                                        <td>{{ $item->pembeli }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="{{ route('penjualan.create')}}" class="btn btn-sm btn-info float-left">Place New Order</a>
                    <a href="{{ route('penjualan.index')}}" class="btn btn-sm btn-secondary float-right">View All Orders</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!--/.card -->
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pie Chart User</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"
                        style="min-height: 250px; height: 305px; max-height: 310px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- /.col -->

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('penjualanChart').getContext('2d');
        const penjualanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($penjualanDetail->pluck('tanggal')->map(function ($tgl) {
                    return \Carbon\Carbon::parse($tgl)->format('d F'); // contoh: 01 January
                })) !!},
                datasets: [{
                    label: 'Jumlah Penjualan Detail per Hari',
                    data: {!! json_encode($penjualanDetail->pluck('total')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pieCtx = document.getElementById('pieChart').getContext('2d');

            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($userByLevel->pluck('level_nama')) !!},
                    datasets: [{
                        data: {!! json_encode($userByLevel->pluck('total')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',    // merah
                            'rgba(54, 162, 235, 0.7)',    // biru
                            'rgba(255, 206, 86, 0.7)',    // kuning
                            'rgba(75, 192, 192, 0.7)',    // hijau
                            'rgba(153, 102, 255, 0.7)'    // ungu
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#ffffff' // <- ini buat warna teks di legend (putih)
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return `${label}: ${value} user`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush