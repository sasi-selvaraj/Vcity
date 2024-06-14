@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Directors'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Directors List</h4>
                        {{-- <div class="justify-content-end">
                            <button type="button" class="btn btn-primary btn-round text-white"
                                onclick="showAddDirectorModal()">Add Director</button>
                        </div> --}}
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

    <!-- Add Director Modal -->
    <div class="modal fade" id="addDirectorModal" tabindex="-1" role="dialog" aria-labelledby="addDirectorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDirectorModalLabel">Add Director</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="addDirectorForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Director Name<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="director_vcity_id">Director ID</label>
                                <input type="text" class="form-control pe-none" id="director_vcity_id" name="director_vcity_id">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mobile_no">Mobile Number</label>
                                <input type="number" class="form-control" id="mobile_no" name="mobile_no">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="designation">Designation</label>
                                <select name="designation" id="designation" class="form-select">
                                    <option value="">Select Designation</option>
                                    <option value="Dir">Director</option>
                                    <option value="SD">Senior Director</option>
                                    <option value="CD">Chief Director</option>
                                    <option value="AD">Associate Director</option>
                                    <option value="CRM">CRM</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email<span class="mandatory_fields">*</span></label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control" id="address" rows="3"></textarea>
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

    <!-- View Director Modal -->
    <div class="modal fade" id="viewDirectorModal" tabindex="-1" role="dialog" aria-labelledby="viewDirectorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDirectorModalLabel">View Director</h5>
                    <p></p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <form id="viewDirectorForm">
                        <input type="hidden" id="view_director_id">
                        <div class="form-group d-flex">
                            <label for="view_name">Director Name: &nbsp;</label>
                            <p type="text" id="view_name"></p>
                        </div>
                        <div class="form-group d-flex">
                            <label for="view_director_vcity_id">Director ID: &nbsp;</label>
                            <p type="text" id="view_director_vcity_id"></p>
                        </div>
                        <div class="form-group d-flex">
                            <label for="view_mobile_no">Mobile Number: &nbsp;</label>
                            <p type="text" id="view_mobile_no"></p>
                        </div>
                        <div class="form-group d-flex">
                            <label for="view_designation">Designation: &nbsp;</label>
                            <p type="text" id="view_designation"></p>
                        </div>
                        <div class="form-group d-flex">
                            <label for="view_email">Email: &nbsp;</label>
                            <p type="text" id="view_email"></p>
                        </div>
                        <div class="form-group d-flex">
                            <label for="view_address">Address: &nbsp;</label>
                            <p type="text" id="view_address"></p>
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

    <!-- Edit Director Modal -->
    <div class="modal fade" id="editDirectorModal" tabindex="-1" role="dialog"
        aria-labelledby="editDirectorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDirectorModalLabel">Edit Director</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="editDirectorForm">
                    @csrf
                    <div class="modal-body" id="editDirectorModalBody">
                        <input type="hidden" id="edit_director_id">
                        <div class="form-group">
                            <label for="edit_name">Director Name<span class="mandatory_fields">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="edit_director_vcity_id">Director ID</label>
                            <input type="text" class="form-control bg-white" id="edit_director_vcity_id"
                                name="director_vcity_id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_mobile_no">Mobile Number</label>
                            <input type="number" class="form-control" id="edit_mobile_no" name="mobile_no">
                        </div>
                        <div class="form-group">
                            <label for="edit_designation">Designation</label>
                            <select name="designation" id="edit_designation" class="form-select">
                                <option value="">Select Designation</option>
                                <option value="Dir">Director</option>
                                <option value="SD">Senior Director</option>
                                <option value="CD">Chief Director</option>
                                <option value="AD">Associate Director</option>
                                <option value="CRM">Customer Relationship Manager</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email<span class="mandatory_fields">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="edit_address">Address</label>
                            <textarea id="edit_address" name="address" class="form-control" rows="3"></textarea>
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

    <!-- Delete Director Modal -->
    <div class="modal fade" id="deleteDirectorModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteDirectorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDirectorModalLabel">Delete Director</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Director ?"</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deleteDirector"
                        onclick="deleteDirector()">Delete</button>
                </div>
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
        // Add Modal
        function showAddDirectorModal() {
            $('#addDirectorModal').modal('show');
        }

        // Import Modal
        function showImportDirectorModal() {
            $('#importModal').modal('show');
        }

        // Director form validation
        $(document).ready(function() {
            $('#addDirectorForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    mobile_no: {
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },
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
                    addDirector();

                }
            });
        });


        // ADD Director
        function addDirector() {
            var formData = $('#addDirectorForm').serialize();
            var route_url = '/directors';
            var method = 'POST';
            ajaxResponse(route_url, method, formData);
        }

        // View Director Data
        function viewData(directorId) {
            var route_url = '/directors/' + directorId;
            var method = 'GET';
            var data = null;
            var render = "viewDirectorModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#view_director_id").val(response.director.id);
                $("#view_name").text(response.director.name);
                $("#view_director_vcity_id").text(response.director.director_id);
                $("#view_mobile_no").text(response.director.mobile_no);
                $("#view_designation").text(response.director.designation);
                $("#view_email").text(response.director.email);
                $("#view_address").text(response.director.address);
                $("#" + render).modal("show");
            });
        }

        // Edit Director Data
        function editData(directorId) {
            var route_url = '/directors/' + directorId + '/edit';
            var method = 'GET';
            var data = null;
            var render = "editDirectorModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#edit_director_id").val(response.director.id);
                $("#edit_name").val(response.director.name);
                $("#edit_director_vcity_id").val(response.director.director_id);
                $("#edit_mobile_no").val(response.director.mobile_no);
                $("#edit_designation").val(response.director.designation);
                $("#edit_email").val(response.director.email);
                $("#edit_address").val(response.director.address);
                $("#" + render).modal("show");
            });
        }

        $(document).ready(function() {
            $('#editDirectorForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    mobile_no: {
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },
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
                    updateDirector();
                }
            });
        });

        // Update Director Data
        function updateDirector() {
            var directorId = $('#edit_director_id').val();
            var formData = $('#editDirectorForm').serialize();
            var route_url = '/directors/' + directorId;
            var method = 'PUT';
            ajaxResponse(route_url, method, formData);
        }

        // Delete Modal Popup
        function deleteData(directorId) {
            $('#deleteDirectorModal').modal('show');
            $('#deleteDirector').data('director-id', directorId);
        }

        // Delete Director Data
        function deleteDirector() {
            var directorId = $('#deleteDirector').data('director-id');
            var route_url = '/directors/' + directorId;
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
            let url = "{{ route('directors.update.status') }}";
            var data = {
                _token: "{{ csrf_token() }}",
                director_id: director_id,
                status: status,
            };
            ajaxResponse(url, method, data);
        });

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#directors-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
