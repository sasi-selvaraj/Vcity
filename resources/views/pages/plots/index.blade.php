@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .underlined {
            text-decoration: underline;
        }

        .underlined:hover {
            color: #ff5715 !important;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Plots'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Plots List</h4>
                        <div class="justify-content-end">
                            <button type="button" class="btn btn-primary btn-round text-white mx-2"
                                onclick="showImportPlotModal()">Import Plots</button>
                            <button type="button" class="btn btn-primary btn-round text-white"
                                onclick="showAddPlotModal()">Add Plot</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Plot Modal -->
    <div class="modal fade" id="addPlotModal" tabindex="-1" role="dialog" aria-labelledby="addPlotModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addPlotModalLabel">Add Plot</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="addPlotForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="project_name">Project Name<span class="mandatory_fields">*</span></label>
                                <select name="project_name" id="project_name" class="form-select">
                                    <option value="">Select Project</option>
                                    @if (count($projects))
                                        @foreach ($projects as $item)
                                            <option value="{{ $item->id }}">{{ $item->project_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="plot_no">Plot No<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control" id="plot_no" name="plot_no">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="block">Block<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control" id="block" name="block">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="facing">Facing</label>
                                <input type="text" class="form-control" id="facing" name="facing">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="plot_sqft">Plot Sq.ft.<span class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control" id="plot_sqft" name="plot_sqft" min="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="glv_rate">GLV Rate</label>
                                <input type="number" class="form-control bg-white" id="glv_rate" name="glv_rate"
                                    min="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="glv">Guide Line Value</label>
                                <input type="number" class="form-control bg-white" id="glv" name="glv"
                                    min="0" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dev_rate">Dev. Rate</label>
                                <input type="number" class="form-control bg-white" id="dev_rate" name="dev_rate"
                                    min="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="development_charges">Development Charges</label>
                                <input type="number" class="form-control bg-white" id="development_charges"
                                    name="development_charges" min="0" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="total_amount">Total Amount</label>
                                <input type="number" class="form-control bg-white" id="total_amount"
                                    name="total_amount" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Plot Status<span class="mandatory_fields">*</span></label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Hold">Hold</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="location">Location</label>
                                <input type="text" class="form-control bg-white" id="location" name="location">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plot Modal -->
    <div class="modal fade" id="editPlotModal" tabindex="-1" role="dialog" aria-labelledby="editPlotModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlotModalLabel">Edit Plot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="editPlotForm">
                    @csrf
                    <div class="modal-body" id="editPlotModalBody">
                        <input type="hidden" id="edit_plot_id">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="edit_project_name">Project Name<span class="mandatory_fields">*</span></label>
                                <select name="project_name" id="edit_project_name" class="form-select">
                                    <option value="">Select Project</option>
                                    @if (count($projects))
                                        @foreach ($projects as $item)
                                            <option value="{{ $item->id }}">{{ $item->project_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_plot_no">Plot No<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control bg-white" id="edit_plot_no" name="plot_no">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_block">Block<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control bg-white" id="edit_block" name="block">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="edit_facing">Facing</label>
                                <input type="text" class="form-control" id="edit_facing" name="facing">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_plot_sqft">Plot Sq.ft.<span class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control bg-white" id="edit_plot_sqft" name="plot_sqft"
                                    min="0">
                            </div>
                            {{-- <div class="form-group col-md-4">
                                <label for="edit_sqft_rate">Sq.ft. Rate</label>
                                <input type="number" class="form-control bg-white" id="edit_sqft_rate" name="sqft_rate"
                                    min="0">
                            </div> --}}
                            <div class="form-group col-md-4">
                                <label for="edit_glv_rate">GLV Rate</label>
                                <input type="number" class="form-control bg-white" id="edit_glv_rate" name="glv_rate"
                                    min="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_glv">Guide Line Value</label>
                                <input type="number" class="form-control bg-white" id="edit_glv" name="glv"
                                    min="0" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_dev_rate">Dev. Rate</label>
                                <input type="number" class="form-control bg-white" id="edit_dev_rate" name="dev_rate"
                                    min="0">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_development_charges">Development Charges</label>
                                <input type="number" class="form-control bg-white" id="edit_development_charges"
                                    name="development_charges" min="0" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="edit_total_amount">Total Amount</label>
                                <input type="number" class="form-control bg-white" id="edit_total_amount"
                                    name="total_amount" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_status">Plot Status<span class="mandatory_fields">*</span></label>
                                <select name="status" id="edit_status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Hold">Hold</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="edit_location">Location</label>
                                <input type="text" class="form-control bg-white" id="edit_location" name="location">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_description">Description</label>
                                <textarea class="form-control" name="description" id="edit_description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Plot Modal -->
    <div class="modal fade" id="deletePlotModal" tabindex="-1" role="dialog" aria-labelledby="deletePlotModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePlotModalLabel">Delete Plot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete Plot "<span id="plot-no"></span>" from the Project "<span
                            id="project-name"></span>" ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deletePlot"
                        onclick="deletePlot()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Import modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Plot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="importForm">
                    <div class="data-error">

                    </div>
                    <div class="modal-body">
                        <div class="row mx-0 ">
                            @csrf
                            <div class="py-2">
                                <label for="file" class="form-label">Select File</label>
                                <input type="file" id="file" class="form-control" placeholder="Select file"
                                    name="file">
                                <div id="attachment_div">
                                </div>
                            </div>
                            <div class="col-md-12 py-2">
                                <a href="{{ asset('sample/sample_plots_import.xlsx') }}" class="text-primary underlined"
                                    download>click here</a> to
                                download a sample format.
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary import" id="import">Import</button>
                    </div>
                </form>
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
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        // Add Modal
        function showAddPlotModal() {
            $('#addPlotModal').modal('show');
        }

        // Import Modal
        function showImportPlotModal() {
            $('#importModal').modal('show');
        }

        $(document).ready(function() {
            $('#importForm').validate({
                rules: {
                    // Your validation rules here
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("select2-hidden-accessible")) {
                        error.insertAfter(element.siblings('span.select2'));
                    } else if (element.hasClass("floating-input")) {
                        element.closest('.form-floating-label').addClass("error-cont")
                            .append(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);
                    loadButton('#import');
                    $.ajax({
                        type: "POST",
                        url: "{{ route('plots.import') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(data) {
                            loadButton('#import');
                            if (data.success == 1) {
                                // table.fnDraw();
                                alertNotify('Success', 'success', data.message);
                                if (data.success == 1) {
                                    $(".fa.fa-refresh").trigger("click");
                                    if ($("body").hasClass("modal-open")) {
                                        $(".modal").modal("hide");
                                        $(".modal form").get(0).reset();
                                        window.location.reload();
                                    }
                                    if (redirect != null) {
                                        window.location = redirect;
                                    }
                                } else {
                                    // alertNotify("Error", "error", result.message);
                                }
                            } else {
                                if (data.type == 1) {
                                    let content = "";
                                    $.each(data.message, function(key, val) {
                                        content += val + `<br>`;
                                    })
                                    let html = `<div class="alert alert-red alert-dismissible m-4" role="alert">
                                        ${content}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                            class="fa fa-times" id="import-error-close" aria-hidden="true"></i></button>
                                    </div>`;
                                    $('.data-error').html(html);
                                    // alertNotify('Error', 'error', content);
                                } else if (data.error && data.error != "") {
                                    alertNotify('Error', 'error', data.error[0]);
                                } else {
                                    alertNotify('Error', 'error', data.message);
                                }
                            }
                        }
                    });
                    return false;
                }
            });
        });


        // Plot form validation
        $(document).ready(function() {
            $('#addPlotForm').validate({
                rules: {
                    project_name: {
                        required: true,
                    },
                    plot_no: {
                        required: true,
                    },
                    block: {
                        required: true,
                    },
                    plot_sqft: {
                        // required: true,
                        number: true,
                        max: 999999,
                    },
                    sqft_rate: {
                        number: true,
                        max: 999999,
                    },
                    total_amount: {
                        number: true,
                        max: 999999999999,
                    },
                    // status: {
                    //     required: true,
                    // },
                },
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                submitHandler: function(form) {
                    addPlot();

                }
            });
        });


        // ADD Plot
        function addPlot() {
            var formData = $('#addPlotForm').serialize();
            var route_url = '/plots';
            var method = 'POST';
            ajaxResponse(route_url, method, formData);
        }

        // Edit Plot Data
        function editData(plotId) {
            var route_url = '/plots/' + plotId + '/edit';
            var method = 'GET';
            var data = null;
            var render = "editPlotModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#edit_plot_id").val(response.plot.id);
                $("#edit_plot_no").val(response.plot.plot_no);
                $("#edit_block").val(response.plot.block);
                $("#edit_facing").val(response.plot.facing).trigger("change");
                $("#edit_facing option[value='" + response.plot.facing + "']").text(response.plot.facing);
                $("#edit_plot_sqft").val(response.plot.plot_sqft);
                $("#edit_sqft_rate").val(response.plot.sqft_rate);
                $("#edit_location").val(response.plot.location);
                $("#edit_market_value").val(response.plot.market_value);
                $("#edit_glv").val(response.plot.glv);
                $("#edit_glv_rate").val(response.plot.glv_rate);
                $("#edit_development_charges").val(response.plot.development_charges);
                $("#edit_dev_rate").val(response.plot.dev_rate);
                $("#edit_gst").val(response.plot.gst);
                $("#edit_status_updated_at").val(response.plot.status_updated_at);
                $("#edit_plot_sqft").val(response.plot.plot_sqft);
                $("#edit_plot_sqft").val(response.plot.plot_sqft);
                $("#edit_total_amount").val(response.plot.total_amount);
                $("#edit_project_name").val(response.plot.project.id).trigger("change");
                $("#edit_project_name option[value='" + response.plot.project.id + "']").text(response.plot.project
                    .project_name);
                $("#edit_status").val(response.plot.status).trigger("change");
                $("#edit_status option[value='" + response.plot.status + "']").text(response.plot.status);
                $("#edit_description").val(response.plot.description);
                $("#" + render).modal("show");
            });


        }

        $(document).ready(function() {
            $('#editPlotForm').validate({
                rules: {
                    project_name: {
                        required: true,
                    },
                    plot_no: {
                        required: true,
                    },
                    block: {
                        required: true,
                    },
                    plot_sqft: {
                        // required: true,
                        number: true,
                        max: 999999,
                    },
                    sqft_rate: {
                        number: true,
                        max: 999999,
                    },
                    total_amount: {
                        number: true,
                        max: 9999999999,
                    },
                    // status: {
                    //     required: true,
                    // },
                },
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                submitHandler: function(form) {
                    updatePlot();
                }
            });
        });

        // Update Plot Data
        function updatePlot() {
            var plotId = $('#edit_plot_id').val();
            var formData = $('#editPlotForm').serialize();
            var route_url = '/plots/' + plotId;
            var method = 'PUT';
            ajaxResponse(route_url, method, formData);
        }

        // Delete Modal Popup
        function deleteData(plotId, plotNo, projectName) {
            $('#deletePlotModal').modal('show');
            $('#deletePlot').data('plot-id', plotId);
            $('#plot-no').text(plotNo);
            $('#project-name').text(projectName);
        }

        // Delete Plot Data
        function deletePlot() {
            var plotId = $('#deletePlot').data('plot-id');
            var route_url = '/plots/' + plotId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        $(document).ready(function() {
            function calculateGlv() {
                var plotSqft = parseFloat($('#plot_sqft').val());
                var glvRate = parseFloat($('#glv_rate').val());
                var totalGlv = plotSqft * glvRate;
                $('#glv').val(isNaN(totalGlv) ? 0 : totalGlv);

                var glv = parseFloat($('#glv').val());
                var development_charges = parseFloat($('#development_charges').val());
                console.log(development_charges);
                var totalAmount = glv + development_charges;
                $('#total_amount').val(isNaN(totalAmount) ? 0 : totalAmount);
            }
            $('#plot_sqft, #glv_rate').on('input', calculateGlv);

            function calculateDev() {
                var plotSqft = parseFloat($('#plot_sqft').val());
                var devRate = parseFloat($('#dev_rate').val());
                var totalDev = plotSqft * devRate;
                $('#development_charges').val(isNaN(totalDev) ? 0 : totalDev);

                var plotSqft = parseFloat($('#glv').val());
                var sqftRate = parseFloat($('#development_charges').val());
                var totalAmount = plotSqft + sqftRate;
                $('#total_amount').val(isNaN(totalAmount) ? 0 : totalAmount);
            }
            $('#plot_sqft, #dev_rate').on('input', calculateDev);
        });

        $(document).ready(function() {
            function calculateGlv() {
                var plotSqft = parseFloat($('#edit_plot_sqft').val());
                var glvRate = parseFloat($('#edit_glv_rate').val());
                var totalGlv = plotSqft * glvRate;
                $('#edit_glv').val(isNaN(totalGlv) ? 0 : totalGlv);

                var plotSqft = parseFloat($('#edit_glv').val());
                var sqftRate = parseFloat($('#edit_development_charges').val());
                var totalAmount = plotSqft + sqftRate;
                $('#edit_total_amount').val(isNaN(totalAmount) ? 0 : totalAmount);
            }
            $('#edit_plot_sqft, #edit_glv_rate, #edit_dev_rate').on('input', calculateGlv);

            function calculateDev() {
                var plotSqft = parseFloat($('#edit_plot_sqft').val());
                var devRate = parseFloat($('#edit_dev_rate').val());
                var totalDev = plotSqft * devRate;
                $('#edit_development_charges').val(isNaN(totalDev) ? 0 : totalDev);

                var plotSqft = parseFloat($('#edit_glv').val());
                var sqftRate = parseFloat($('#edit_development_charges').val());
                var totalAmount = plotSqft + sqftRate;
                $('#edit_total_amount').val(isNaN(totalAmount) ? 0 : totalAmount);
            }
            $('#edit_plot_sqft, #edit_dev_rate, #edit_glv_rate').on('input', calculateDev);
        });

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#plots-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
