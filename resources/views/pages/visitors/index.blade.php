@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .underlined {
            text-decoration: underline;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Gate Pass'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Gate Pass</h4>
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
    <!-- View Visitor Modal -->
    <div class="modal fade" id="viewVisitorModal" tabindex="-1" role="dialog" aria-labelledby="viewVisitorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewVisitorModalLabel">View Visitor</h5>
                    <p></p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <form id="viewVisitorForm">
                        <input type="hidden" id="view_visitor_id">
                        <div class="row">
                            <div class="d-flex col-md-6">
                                <label for="view_project">Project Name: &nbsp;</label>
                                <p class="bg-white" id="view_project"></p>
                            </div>
                            <div class="d-flex col-md-6">
                                <label for="view_plot">Plot No.: &nbsp;</label>
                                <p class="bg-white" id="view_plot"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-md-6">
                                <label for="view_director">Director: &nbsp;</label>
                                <p class="bg-white" id="view_director"></p>
                            </div>
                            <div class="d-flex col-md-6">
                                <label for="view_marketer">Marketer: &nbsp;</label>
                                <p class="bg-white" id="view_marketer"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-md-6">
                                <label for="view_customer_name">Customer Name: &nbsp;</label>
                                <p class="bg-white" id="view_customer_name"></p>
                            </div>
                            <div class="d-flex col-md-6">
                                <label for="view_mobile_no">Mobile Number: &nbsp;</label>
                                <p class="bg-white" id="view_mobile_no"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex col-md-6">
                                <label for="view_date">Date: &nbsp;</label>
                                <p class="bg-white" id="view_date"></p>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_image" class="form-label pt-2">Uploaded Image:</label>
                                <a class="my-success view-file m-1 underlined text-primary" href="javascript:void(0)" data-type="" data-title=""
                                    data-file="">
                                    Click here to view
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Visitor Modal -->
    <div class="modal fade" id="deleteVisitorModal" tabindex="-1" role="dialog" aria-labelledby="deleteVisitorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteVisitorModalLabel">Delete Visitor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete Visitor "<span id="visitor-no"></span>" from the Project "<span
                            id="project-name"></span>" ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deleteVisitor"
                        onclick="deleteVisitor()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- viewFileModal --}}
    <div class="modal fade" id="viewFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">Uploaded Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <input type="hidden" name="id" id="id" value="">
                <div class="modal-body viewFile-modal d-flex justify-content-center">
                    <div id="image_attachment" class="">
                        <img id="view_image" width="300px" height="200px">
                    </div>
                </div>
                <div class="modal-footer px-0 pt-3 pb-0 my-3 mx-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
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
        // View Visitor Data
        function viewData(visitorId) {
            var route_url = '/visitors/' + visitorId;
            var method = 'GET';
            var data = null;
            var render = "viewVisitorModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#view_visitor_id").val(response.visitor.id);
                $("#view_project").text(response.visitor.project.project_name);
                if (response.visitor.plot && response.visitor.plot !== null && typeof response.visitor.plot ===
                    'object') {
                    $("#view_plot").text(response.visitor.plot
                        .plot_no);
                } else {
                    $("#view_plot").text('N/A');
                }
                $("#view_director").text(response.visitor.director.name);
                $("#view_marketer").text(response.visitor.marketer.name);
                $("#view_customer_name").text(response.visitor.customer_name);
                $("#view_mobile_no").text(response.visitor.mobile_no);
                $("#view_date").text(response.visitor.date);
                if (response.visitor.image) {
                    var baseUrl = "{{ url('/') }}";
                    var imageUrl = baseUrl + '/storage/' + response.visitor.image;
                    $('#view_image').attr('src', imageUrl);
                } else {
                    $('#view_image').removeAttr('src');
                }
                $("#" + render).modal("show");
            });
        }

        // Delete Modal Popup
        function deleteData(visitorId) {
            $('#deleteVisitorModal').modal('show');
            $('#deleteVisitor').data('visitor-id', visitorId);
        }

        // Delete Visitor Data
        function deleteVisitor() {
            var visitorId = $('#deleteVisitor').data('visitor-id');
            var route_url = '/visitors/' + visitorId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#visitor-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });

        // viewFile
        $(document).on('click', '.view-file', function() {
            $('#viewFileModal').modal('show');
            $('.modal-title').html(title);
            $('.viewFile-modal').html('<img class="img-fluid mx-auto w-100 d-block" src="' + url +
                '" alt="Image">');
        })
    </script>
@endpush
