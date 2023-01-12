<x-app-layout>
    @section('title', 'Dashboard')
    <x-slot name="breadcrumbs">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="/dashboard">
                        <i class="mdi mdi-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </x-slot>

    {{-- content --}}
    <div class="container">
        <div class="row">
            @foreach ($results as $i => $result)
            <div class="col-12 col-md-6 col-lg-3 ps-0 {{$i === count($results) - 1 ? 'pe-0' : 'pe-3'}}">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 85px; height: 85px;">
                                    <i class="{{$result['icon']}} text-white" style="font-size: 60px"></i>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <p class="m-0 text-muted">{{$result['title']}}</p>
                                <p class="fw-semi-bold m-0 h1 p-0">{{$result['value']}}</p>
                            </div>
                        </div>
                        <hr class="mt-4">
                        <div class="text-center m-0 p-0">
                            <a href="{{$result['link']}}" class="btn btn-link">
                                More Info
                                <i class="mdi mdi-arrow-right-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-5">
            <div class="col-12 col-md-8 ps-0">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <canvas id="transactions" width="100%"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 pe-0" style="height: 100%">
                <div class="card border-0 shadow h-100">
                    <div class="card-body" style="height: 372px">
                        <canvas id="total-transactions" width="100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end content --}}


    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', event => {
            lineChart()
            doughnutChart()
        })
        function lineChart() {
            const labels = [ 
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Ags',
                'Sep',
                'Okt',
                'Nov',
                'Des',
            ];
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Outgoing',
                        data: JSON.parse('{{json_encode($outgoingTransactions)}}'),
                        backgroundColor: 'blue',
                        borderColor: 'blue',
                        borderWidth: 2,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Incoming',
                        data: JSON.parse('{{json_encode($incomingTransactions)}}'),
                        backgroundColor: 'red',
                        borderColor: 'red',
                        borderWidth: 2,
                        yAxisID: 'y1',
                    },
                ]
            };
            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Product Transaction in 2022'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',

                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    }
                },
            };
            new Chart(
                document.getElementById('transactions'),
                config
            );
        }

        function doughnutChart() {
            const data = {
                labels: [
                    'Incoming',
                    'Outgoing',
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: JSON.parse('{{json_encode($transactions)}}'),
                    backgroundColor: [
                        'red',
                        'blue'
                    ],
                    hoverOffset: 4
                }]
            };
            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true
                }
            }
            new Chart(
                document.getElementById('total-transactions'),
                config
            );
        }
    </script>
    @endsection
</x-app-layout>