@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketer Report'])
    <style>
        table colgroup:not(:first-child) {
            display: none;
        }
    </style>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Marketer Report</h4>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex ">
                        <div class="form-group col-md-4">
                            <label for="date_range">Select Date Range:</label>
                            <input type="text" id="date_range" name="date_range" class="form-control" />
                            {{-- <button id="reset_btn" class="btn btn-xs btn-warning">Reset</button> --}}
                        </div>
                        <div class="form-group px-2 col-md-4">
                            <label for="date_range">Select Project:</label>
                            <select name="select_project" id="select_project" class="form-select">
                                <option value="">All Projects</option>
                                @foreach ($projects as $item)
                                    <option value="{{ $item->id }}">{{ $item->project_name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table dataTable" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Marketer Vcity ID</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>Plots Sold</th>
                                    <th>Total Sqft</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        {{-- {{ $dataTable->table() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Marketer Commission Modal -->
    <div class="modal fade" id="viewMarketerProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="viewMarketerProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMarketerProgressModalLabel">View Payments</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true"></i>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="view_payments_id">
                    <div class="row">
                        <div class="form-group pe-none col-md-4">
                            <label for="view_marketer_id" class="fs-6">Marketer ID:</label>
                            <label class="bg-white text-muted fs-6" id="view_marketer_id"></label>
                        </div>
                        <div class="form-group pe-none col-md-4">
                            <label for="view_marketer_name" class="fs-6">Marketer Name:</label>
                            <label class="bg-white text-muted fs-6" id="view_marketer_name"></label>
                        </div>
                        <div class="form-group pe-none col-md-4">
                            <label for="view_email" class="fs-6">Email:</label>
                            <label class="bg-white text-muted fs-6" id="view_email"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group pe-none col-md-4">
                            <label for="view_mobile_no" class="fs-6">Mobile Number:</label>
                            <label class="bg-white text-muted fs-6" id="view_mobile_no"></label>
                        </div>
                        <div class="form-group pe-none col-md-4">
                            <label for="view_plots" class="fs-6">Plots Sold:</label>
                            <label class="bg-white text-muted fs-6" id="view_plots"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.min.css">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}

    <script>
        var table;
        $(function() {
            $("#date_range").daterangepicker({
                opens: 'right',
                locale: {
                    format: 'DD-MM-YYYY'
                },
            }, function(start, end, label) {
                // alert("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
            });
            $("#date_range").change(function() {

                table.destroy();
                initializeDataTable();
            });
            $("#select_project").change(function() {

                table.destroy();
                initializeDataTable();
            });
            // $("#reset_btn").click(function() {
            //     $("#date_range").val('');
            //     table.destroy();
            //     initializeDataTable();
            // });
        });
        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            var date_range = $("#date_range").val();
            var project = $("#select_project").val();
            if (date_range === "") {
                date_range = null;
            }
            if (project === "") {
                project = null;
            }
            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('marketer-progress.datatable_ajax') }}",
                    data: {
                        date_range: date_range,
                        project: project,
                    },
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'id'
                    // },
                    {
                        data: 'marketer_id',
                        name: 'marketer_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'sold',
                        name: 'sold'
                    },
                    {
                        data: 'total_sqft',
                        name: 'total_sqft'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['excel'],
                rowCallback: function(row, data) {
                    if (data.total_sqft >= 33000) {
                        $(row).addClass('text-danger');
                    }
                }
            });
        }

        // View Marketer Commission Data
        function viewData(marketerId) {
            var date_range = $("#date_range").val();
            var project = $("#select_project").val();
            if (date_range === "") {
                date_range = null;
            }
            if (project === "") {
                project = null;
            }
            var route_url = '/marketer-progress/show/' + marketerId;
            var method = 'GET';
            var data = {
                date_range: date_range,
                project: project,
            };
            var render = "viewMarketerProgressModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#view_marketer_id").val(response.marketerProgress.id);
                $("#view_marketer_name").text(response.marketerProgress.name ?? 'N/A');
                $("#view_email").text(response.marketerProgress.email ?? 'N/A');
                $("#view_mobile_no").text(response.marketerProgress.mobile_no ?? 'N/A');

                var plotList = $("#view_plots");
                plotList.empty();
                var plotCounter = 1;
                response.totalPlots.forEach(function(plot) {
                    var plotDetails = $("<div>");
                    plotDetails.append("<strong>" + plotCounter + ". Plot:</strong> " + plot.plot_no +
                        " from <strong>Project:</strong> " + plot.project.project_name + " <br>");
                    plotList.append(plotDetails);
                    plotCounter++;
                });
                $("#" + render).modal("show");
            });
        }

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#dataTable').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
