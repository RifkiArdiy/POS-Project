@extends('layouts.template')

@section('content')
    {{-- <div class="row">
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
    </div> --}}
    <!-- Chart -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Penjualan Detail per Bulan</h3>

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
                    {{-- <canvas id="penjualanChart"></canvas> --}}
                    <select id="chartType">
                        <option value="line">Line</option>
                        <option value="bar">Bar</option>
                    </select>

                    <select id="rangeFilter">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="thismonth">This Month</option>
                    </select>

                    <canvas id="incomeChart" height="100"></canvas>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <!-- USERS LIST -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Penjualan Terakhir</h3>

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
                    <h3 class="card-title">Barang Terjual</h3>
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
        let chart;
        const ctx = document.getElementById('incomeChart').getContext('2d');
        function loadChart(range = '12months', type = 'line') {
            fetch(`/income-by-range?range=${range}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        console.error("Server error:", data.message);
                        return;
                    }

                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const fullLabels = monthNames.map((m, i) => `${m} ${new Date().getFullYear()}`);
                    const values = new Array(12).fill(0); // 12 bulan default 0

                    data.forEach(item => {
                        const label = `${monthNames[item.bulan - 1]} ${item.tahun}`;
                        const index = fullLabels.indexOf(label);
                        if (index !== -1) {
                            values[index] = item.total;
                        }
                    });

                    if (chart) chart.destroy();

                    chart = new Chart(ctx, {
                        type: type,
                        data: {
                            labels: fullLabels,
                            datasets: [{
                                label: 'Total Income',
                                data: values,
                                borderColor: 'rgba(255, 159, 64, 1)',
                                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: type === 'line'
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }

        document.getElementById('rangeFilter').addEventListener('change', function () {
            const range = this.value;
            const type = document.getElementById('chartType').value;
            loadChart(range, type);
        });

        document.getElementById('chartType').addEventListener('change', function () {
            const type = this.value;
            const range = document.getElementById('rangeFilter').value;
            loadChart(range, type);
        });

        // Load default chart
        loadChart();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pieCtx = document.getElementById('pieChart').getContext('2d');

            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($barangTerjual->pluck('barang_nama')) !!},
                    datasets: [{
                        data: {!! json_encode($barangTerjual->pluck('total')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(199, 199, 199, 0.7)'
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
                                color: '#ffffff'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return `${label}: ${value} terjual`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush