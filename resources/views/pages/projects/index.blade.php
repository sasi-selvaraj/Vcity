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
    @include('layouts.navbars.auth.topnav', ['title' => 'Projects'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Projects List</h4>
                        <div class="justify-content-end">
                            <button type="button" class="btn btn-primary btn-round text-white mx-2"
                                onclick="showImportProjectModal()">Import Project</button>
                            <button type="button" class="btn btn-primary btn-round text-white"
                                onclick="showAddProjectModal()">Add Project</button>
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

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="addProjectForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="project_name">Project Name<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control" id="project_name" name="project_name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="total_plots">Total No.of Plots<span class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control" id="total_plots" name="total_plots"
                                    min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="total_no_of_sqft">Total Sq.ft.<span class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control" id="total_no_of_sqft" name="total_no_of_sqft"
                                    min="0">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="total_blocks">Total no. of blocks</label>
                                <input type="number" class="form-control" id="total_blocks" name="total_blocks"
                                    min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="project_location">Project Location</label>
                                <input type="text" class="form-control" id="project_location" name="project_location">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="project_description">Project Description</label>
                                <textarea class="form-control" name="project_description" id="project_description" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="project_image">Project Image</label>
                                <input type="file" class="form-control" id="project_image" name="project_image">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary" id="savebtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="editProjectForm">
                    @csrf
                    <div class="modal-body" id="editProjectModalBody">
                        <input type="hidden" id="edit_project_id">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_project_name">Project Name<span class="mandatory_fields">*</span></label>
                                <input type="text" class="form-control" id="edit_project_name" name="project_name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_total_plots">Total No.of Plots<span
                                        class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control bg-white" id="edit_total_plots"
                                    name="total_plots" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_total_no_of_sqft">Total Sq.ft.<span
                                        class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control bg-white" id="edit_total_no_of_sqft"
                                    name="total_no_of_sqft" min="0">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_total_blocks">Total no. of blocks</label>
                                <input type="number" class="form-control bg-white" id="edit_total_blocks"
                                    name="total_blocks" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_project_location">Location</label>
                                <input type="text" class="form-control bg-white" id="edit_project_location"
                                    name="project_location">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edit_project_description">Project Description</label>
                                <textarea class="form-control" name="project_description" id="edit_project_description" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="edit_project_image">Project Image</label>
                                <input type="file" class="form-control" id="edit_project_image" name="project_image"
                                    placeholder="Select Image">
                            </div>
                        </div>
                        <div class="image_div">
                            <img src="" alt="Project Image" class="w-25" id="edit_image_div">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary" id="savebtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Project Modal -->
    <div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProjectModalLabel">Delete Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete Project "<span id="project-name"></span>" ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deleteProject"
                        onclick="deleteProject()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Import modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="importForm">
                    <div class="data-error">

                    </div>
                    <div class="modal-body">
                        <div class="row mx-0 ">
                            @csrf
                            <div class="col-md-12 py-2">
                                <label for="file" class="form-label">Select File</label>
                                <input type="file" id="file" class="form-control" placeholder="Select file"
                                    name="file">
                                <div id="attachment_div">
                                </div>
                            </div>
                            <div class="col-md-12 py-2">
                                <a href="{{ asset('sample/sample_projects_import.xlsx') }}"
                                    class="text-primary underlined" download>click here</a>
                                to
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
        function showAddProjectModal() {
            $('#addProjectModal').modal('show');
        }

        // Import Modal
        function showImportProjectModal() {
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
                        url: "{{ route('projects.import') }}",
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


        // Project form validation
        $(document).ready(function() {
            $('#addProjectForm').validate({
                rules: {
                    project_name: {
                        required: true,
                        minlength: 3,
                    },
                    total_blocks: {
                        number: true,
                        maxlength: 2,
                    },
                    total_no_of_sqft: {
                        required: true,
                        number: true,
                        max: 9999999999,
                    },
                    total_plots: {
                        required: true,
                        number: true,
                        maxlength: 6,
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
                    addProject();
                }
            });
        });

        // ADD Project
        function addProject(e) {
            var formData = new FormData($('#addProjectForm')[0]);
            var route_url = '/projects';
            var method = 'POST';
            ajaxResponseFormData(route_url, method, formData)
        }
        // function addProject() {
        //     var formData = $('#addProjectForm').serialize();
        //     var route_url = '/projects';
        //     var method = 'POST';
        //     ajaxResponse(route_url, method, formData);
        // }

        // Edit Project Data
        function editData(projectId) {
            var route_url = '/projects/' + projectId + '/edit';
            var method = 'GET';
            var data = null;
            var render = "editProjectModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#edit_project_id").val(response.project.id);
                $("#edit_project_name").val(response.project.project_name);
                $("#edit_project_location").val(response.project.project_location);
                $("#edit_total_blocks").val(response.project.total_blocks);
                $("#edit_total_no_of_sqft").val(response.project.total_no_of_sqft);
                $("#edit_total_plots").val(response.project.total_plots);
                $("#edit_project_description").val(response.project.project_description);

                if (response.project.project_image && response.project.project_image.length > 0) {
                    var baseUrl = "{{ url('/') }}";
                    var imageUrl = baseUrl + '/storage/' + response.project.project_image[0].path;
                    $('#edit_image_div').attr('src', imageUrl).show();
                } else {
                    $('#edit_image_div').removeAttr('src').hide();
                }
                $("#" + render).modal("show");
            });
        }

        // Update Project
        $(document).ready(function() {
            $('#editProjectForm').validate({
                rules: {
                    project_name: {
                        required: true,
                        minlength: 3,
                    },
                    total_blocks: {
                        number: true,
                        maxlength: 2,
                    },
                    total_no_of_sqft: {
                        required: true,
                        number: true,
                        max: 9999999999,
                    },
                    total_plots: {
                        required: true,
                        number: true,
                        maxlength: 6,
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
                    updateProject(form);

                }
            });
        });

        // Update Project Data
        function updateProject(form) {
            var projectId = $('#edit_project_id').val();
            var route_url = '/projects/' + projectId;
            var method = 'POST';
            var formData = new FormData();
            var params = $(form).serializeArray();
            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });

            var imageFiles = $(form).find('[name="project_image"]')[0].files;
            $.each(imageFiles, function(i, file) {
                formData.append('project_image', file);
            });
            formData.append('_method', 'PUT');

            ajaxResponseFormData(route_url, method, formData);
        }

        // function updateProject() {
        //     var projectId = $('#edit_project_id').val();
        //     var formData = $('#editProjectForm').serialize();
        //     var route_url = '/projects/' + projectId;
        //     var method = 'PUT';
        //     ajaxResponse(route_url, method, formData);
        // }

        // Delete Modal Popup
        function deleteData(projectId, projectName) {
            $('#deleteProjectModal').modal('show');
            $('#deleteProject').data('project-id', projectId);
            $('#project-name').text(projectName);
        }

        // Delete Project Data
        function deleteProject() {
            var projectId = $('#deleteProject').data('project-id');
            var route_url = '/projects/' + projectId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#projects-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
