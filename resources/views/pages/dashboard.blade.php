@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .plot-card {
            width: 110px;
            height: 110px;
            border-radius: 7%;
            margin: 0px;
            line-height: 15px !important;
        }

        .col-2 {
            width: 16.666666% !important;
        }

        .card-body.plot-view {
            padding: 0.5rem !important;
        }

        .status_details {
            font-size: 15px !important;
        }

        .text-color {
            color: #47014f;
        }

        @media screen and (max-width: 768px) {
            .col-2 {
                width: 19% !important;
            }
        }

        @media screen and (min-width: 1480px) {
            .col-2 {
                width: 12% !important;
            }
        }

        @media screen and (min-width: 1700px) {
            .col-2 {
                width: 11% !important;
            }
        }

        @media screen and (min-width: 1900px) {
            .col-2 {
                width: 10% !important;
            }
        }

        @media screen and (min-width: 2200px) {
            .col-2 {
                width: 9% !important;
            }
        }

        @keyframes blink {
            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        .countdown {
            animation: blink 1s linear infinite;
        }
    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="row mt-4">
                        <div class="col-lg-2 mb-lg-0 mb-3">
                            <div class="dropdown">
                                <button class="btn bg-gradient-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false" data-id="">
                                    Projects
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @isset($projects)
                                        @if (!empty($projects))
                                            <li><a class="dropdown-item scroll-y" href="javascript:void(0)"
                                                    data-id="0">Filter By Project</a></li>
                                            @foreach ($projects as $p)
                                                <li><a class="dropdown-item scroll-y" href="javascript:void(0)"
                                                        data-id="{{ $p->id }}">{{ $p->project_name }}</a></li>
                                            @endforeach
                                        @endif
                                    @endisset
                                </ul>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-evenly gap-2 m-0">
                            <button data-status="All" class="btn bg-gradient-light col">All <i
                                    class="fa fa-arrow-down"></i></button>
                            <button data-status="Available" class="btn bg-gradient-success col">Available</button>
                            <button data-status="Hold" class="btn bg-gradient-danger col">Hold</button>
                            {{-- <button data-status="Permanent Hold" class="btn bg-gradient-purple col">Permanent Hold</button> --}}
                            <button data-status="Temporary Booking" class="btn bg-gradient-green col">Temporary
                                Booking</button>
                            <button data-status="Booking" class="btn bg-gradient-primary col">Booking</button>
                            <button data-status="Full Payment" class="btn bg-gradient-warning col">Full Payment</button>
                            <button data-status="Registered" class="btn bg-gradient-info col">Registered</button>
                            {{-- <button data-status="Cancelled" class="btn bg-gradient-red col">Cancelled</button> --}}
                        </div>
                    </div>
                    <div class="row ms-0 me-0 mt-2 py-4 px-3 rounded bg-light shadow plots">
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card z-index-2 h-100">
                                <div class="card-header pb-0 pt-3 bg-transparent">
                                    <h6 class="text-capitalize">Plots Sales overview</h6>
                                    {{-- <p class="text-sm mb-0">
                                        <i class="fa fa-arrow-up text-success"></i>
                                        <span class="font-weight-bold">4% more</span> in 2021
                                    </p> --}}
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="chart">
                                                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="chart">
                                                <canvas id="chart-pie" width="400" height="400"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>

@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Graph
        var ctx1 = document.getElementById("chart-line").getContext("2d");
        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');

        var chartData = @json($chartData);

        new Chart(ctx1, {
            type: "line",
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fb6340",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: Object.values(chartData),
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: false,
                            padding: 10,
                            color: 'blue',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: 'black',
                            padding: 20,
                            font: {
                                size: 12,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
        // Graph End

        // Pie Chart
        var ctx = document.getElementById('chart-pie').getContext('2d');
        var chartData = @json($chartData);

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    label: 'Sales',
                    data: Object.values(chartData),
                    backgroundColor: getRandomColors(Object.keys(chartData).length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        function getRandomColors(count) {
            var colors = [];
            for (var i = 0; i < count; i++) {
                colors.push('rgba(' + Math.floor(Math.random() * 255) + ', ' + Math.floor(Math.random() * 255) + ', ' + Math
                    .floor(Math.random() * 255) + ', 0.7)');
            }
            return colors;
        }
        // Pie Chart End

        $(document).ready(function() {
            if ($(".dropdown-menu li").length > 0) {
                $(".dropdown-menu li:first .dropdown-item").trigger('click');
            }
        })
        $(document).on('click', '.dropdown-item', function() {
            $(".dropdown-toggle").text($(this).text());
            $(".dropdown-toggle").attr('data-id', $(this).data('id'));
            ajaxResponseRender("{{ route('plots.list') }}", 'post', {
                'project_id': $(this).data('id')
            }, 'plots', successCallback);
        });
        $(document).on('click', '.btn', function() {
            ajaxResponseRender("{{ route('plots.status') }}", 'post', {
                'project_id': $(".dropdown-toggle").attr('data-id'),
                'status': $(this).data('status')
            }, 'plots', successCallback);
        });

        function successCallback(res) {
            var plots = "";
            if (res.data.length == 0) {
                plots = `<p>No Plots`;
            }
            if (res.data.length > 0)
                $.each(res.data, function(i, v) {
                    if (v.status == 'Available')
                        var bgcolor = 'bg-gradient-success';
                    else if (v.status == 'Hold')
                        var bgcolor = 'bg-gradient-danger';
                    // else if (v.status == 'Permanent Hold')
                    //     var bgcolor = 'bg-gradient-purple';
                    else if (v.status == 'Temporary Booking')
                        var bgcolor = 'bg-gradient-green';
                    else if (v.status == 'Booking')
                        var bgcolor = 'bg-gradient-primary';
                    else if (v.status == 'Registered')
                        var bgcolor = 'bg-gradient-info';
                    else if (v.status == 'Full Payment')
                        var bgcolor = 'bg-gradient-warning';
                    // else if (v.status == 'Cancelled')
                    //     var bgcolor = 'bg-gradient-red';
                    // if (!(v.status == 'Cancelled')) {
                    plots += `<div class="col-2">
                        <a href="plots/` + v.id + `" class="text-center view-plot">
                    <div class="card plot-card fw-bold text-dark ` + bgcolor + ` mb-3 mx-2">
                        <div class="card-body plot-view">
                        <h6 class="fw-bold card-title text-center mt-1">` + v.plot_no + `</h6>
                        <h8 class="fw-bold card-title text-center mt-1 status_details">` + v.status + `</h8>
                        ${v.days_left !== null ? `<div class="countdown text-center text-color mt-2"> ${v.days_left}</div>` : ''}
                        </div>
                    </div>
                    </a>
                    </div>`;
                    // }

                });
            $(".plots").html(plots);
        }
        $(".plots").html(make_skeleton());

        function make_skeleton() {
            var output = '';
            for (var count = 0; count < 18; count++) {
                output += '<div class="col-2">';
                output += '<div class="card text-white bg-transparent mb-3">';
                output += '<div class="card-header bg-transparent border-bottom"></div>';
                output += '<div class="card-body">';
                output += ' <p class="card-title"></p>';
                output += '</div>';
                output += '</div>';
                output += '</div>';
            }
            return output;
        }
    </script>
@endpush
