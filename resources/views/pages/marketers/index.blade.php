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
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketers'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Marketer List</h4>
                        <div class="justify-content-end">
                            <a href="{{ route('marketers.hierarchy') }}" class="btn btn-primary btn-round text-white">View Hierarchy</a>
                            <button type="button" class="btn btn-primary btn-round text-white mx-2"
                                onclick="showImportMarketerModal()">Import Marketer</button>
                            <a href="{{ route('marketers.create') }}" class="btn btn-primary btn-round text-white">Add
                                Marketer</a>
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

    <!-- Delete Marketer Modal -->
    <div class="modal fade" id="deleteMarketerModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteMarketerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMarketerModalLabel">Delete Marketer</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true"></i>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Marketer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deleteMarketer"
                        onclick="deleteMarketer()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Import modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Marketer</h5>
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
                                <a href="{{ asset('sample/sample_marketers_import.xlsx') }}" class="text-primary underlined" download>click here</a> to
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

    <!-- Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog"
        aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeStatusModalLabel">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-capitalize">Select Status</h6>
                    <div class="form-group">
                        <form id="submitform">
                            <input type="hidden" name="director_id" id="director_id" value="">
                            <select class="form-control rounded" name="status" id="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button type="button" class="btn btn-primary" id="status_confirmed" data-status="">Update</button>
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
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        // Import 
        function showImportMarketerModal() {
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
                        url: "{{ route('marketers.import') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(data) {
                            loadButton('#import');
                            if (data.success == 1) {
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

        // Delete Modal Popup
        function deleteData(MarketerId) {
            $('#deleteMarketerModal').modal('show');
            $('#deleteMarketer').data('Marketer-id', MarketerId);
        }

        // Delete User Data
        function deleteMarketer() {
            var MarketerId = $('#deleteMarketer').data('Marketer-id');
            var route_url = '/marketers/' + MarketerId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        // Change status
        $(document).on('click', '.verify_status', function(e) {
            $('#status_confirmed').attr('data-status', $(this).data('status'));
            $('#director_id').val($(this).data('director_id'));
            $('#status option[value="' + $(this).data('status') + '"]').prop("selected", true);
            $('#changeStatusModal').modal('show');
        });
        $(document).on('click', '#status_confirmed', function() {
            var status = $('#status').val();
            var director_id = $('#director_id').val();
            let method = 'post';
            let url = "{{ route('marketers.update.status') }}";
            var data = {
                _token: "{{ csrf_token() }}",
                director_id: director_id,
                status: status,
            };
            ajaxResponse(url, method, data);
        });

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#marketer-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
