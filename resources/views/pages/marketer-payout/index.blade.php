@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketer Payout'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Marketer Payout</h4>
                        <div class="justify-content-end">
                            <button type="button" class="btn btn-primary btn-round text-white"
                                onclick="showAddMarketerPayoutModal()">Add Marketer Payout</button>
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
    <div class="modal fade" id="addMarketerPayoutModal" tabindex="-1" role="dialog"
        aria-labelledby="addMarketerPayoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addMarketerPayoutModalLabel">Add Marketer Payout</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="addMarketerPayoutForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="marketer_name">Marketer Name</label>
                                <select name="marketer_name" id="marketer_name" class="form-select">
                                    <option value="">Select Marketer</option>
                                    @isset($marketers)
                                        @foreach ($marketers as $item)
                                            <option value={{ $item->id }}>{{ $item->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="marketer_id">Marketer ID</label>
                                <select name="marketer_id" id="marketer_id" class="form-select">
                                    <option value="">Select Marketer ID</option>
                                    @isset($marketers)
                                        @foreach ($marketers as $item)
                                            <option value={{ $item->id }}>{{ $item->marketer_vcity_id }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="project">Project</label>
                                <select class="form-select" id="project" name="project">
                                    <option value="">Select Project</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="plot_no">Plot No</label>
                                <select class="form-select bg-white" id="plot_no" name="plot_no">
                                    <option value="">Select Plot No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="plot_sqft">Plot Sqft</label>
                                <input type="text" class="form-control bg-white" id="plot_sqft" name="plot_sqft"
                                    readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="plot_amount">Plot Amount</label>
                                <input type="number" class="form-control bg-white" id="plot_amount" name="plot_amount"
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="commission">Marketer Commission Amount<span class="mandatory_fields">*</span></label>
                                <input type="number" class="form-control" id="commission" name="commission" min="0">
                            </div>
                        </div>
                        <fieldset>
                            <h4 class="mt-2">Director Details:</h4>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-3">
                                    <label for="director">Director</label>
                                    <input type="text" class="form-control bg-white" id="director" name="director"
                                        readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="director_commission">Director Commission</label>
                                    <input type="text" class="form-control bg-white" id="director_commission" name="director_commission">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="assist_director">Associate Director</label>
                                    <input type="text" class="form-control bg-white" id="assist_director"
                                        name="assist_director" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="assist_director_commission">Associate Director Commission</label>
                                    <input type="text" class="form-control bg-white" id="assist_director_commission"
                                        name="assist_director_commission">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="crm">CRM</label>
                                    <input type="text" class="form-control bg-white" id="crm" name="crm"
                                        readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="crm_commission">CRM Commission</label>
                                    <input type="text" class="form-control bg-white" id="crm_commission" name="crm_commission">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="senior_director">Senior Director</label>
                                    <input type="text" class="form-control bg-white" id="senior_director"
                                        name="senior_director" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="senior_director_commission">Senior Director Commission</label>
                                    <input type="text" class="form-control bg-white" id="senior_director_commission"
                                        name="senior_director_commission">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="chief_director">Chief Director</label>
                                    <input type="text" class="form-control bg-white" id="chief_director"
                                        name="chief_director" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="chief_director_commission">Chief Director Commission</label>
                                    <input type="text" class="form-control bg-white" id="chief_director_commission"
                                        name="chief_director_commission">
                                </div>
                            </div>
                        </fieldset>
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
    <div class="modal fade" id="editMarketerPayoutModal" tabindex="-1" role="dialog"
        aria-labelledby="editMarketerPayoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMarketerPayoutModalLabel">Edit Marketer Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="editMarketerPayoutForm">
                    @csrf
                    <div class="modal-body" id="editMarketerPayoutModalBody">
                        <input type="hidden" id="edit_marketer_payout_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="edit_marketer_name">Marketer Name<span class="mandatory_fields">*</span></label>
                                    <select name="marketer_name" id="edit_marketer_name" class="form-select">
                                        <option value="">Select Marketer</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $item)
                                                <option value={{ $item->id }}>{{ $item->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_marketer_id">Marketer ID<span class="mandatory_fields">*</span></label>
                                    <select name="marketer_id" id="edit_marketer_id" class="form-select">
                                        <option value="">Select Marketer ID</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $item)
                                                <option value={{ $item->id }}>{{ $item->marketer_vcity_id }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_project">Project<span class="mandatory_fields">*</span></label>
                                    <select class="form-select" id="edit_project" name="project">
                                        <option value="">Select Project</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="edit_plot_no">Plot No<span class="mandatory_fields">*</span></label>
                                    <select class="form-select bg-white" id="edit_plot_no" name="plot_no">
                                        <option value="">Select Plot No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_plot_sqft">Plot Sqft</label>
                                    <input type="text" class="form-control bg-white" id="edit_plot_sqft"
                                        name="plot_sqft" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_plot_amount">Plot Amount</label>
                                    <input type="number" class="form-control bg-white" id="edit_plot_amount"
                                        name="plot_amount" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="edit_commission">Marketer Commission Amount<span class="mandatory_fields">*</span></label>
                                    <input type="number" class="form-control" id="edit_commission" name="commission"
                                        min="0">
                                </div>
                            </div>
                            <fieldset>
                                <h4 class="mt-2">Director Details:</h4>
                                <div class="row border m-1 pt-3">
                                    <div class="form-group col-md-3">
                                        <label for="edit_director">Director</label>
                                        <input type="text" class="form-control bg-white" id="edit_director"
                                            name="director" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_director_commission">Director Commission</label>
                                        <input type="text" class="form-control bg-white" id="edit_director_commission"
                                            name="director_commission">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_assist_director">Associate Director</label>
                                        <input type="text" class="form-control bg-white" id="edit_assist_director"
                                            name="assist_director" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_assist_director_commission">Assist. Director Commission</label>
                                        <input type="text" class="form-control bg-white" id="edit_assist_director_commission"
                                            name="assist_director_commission">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_crm">CRM</label>
                                        <input type="text" class="form-control bg-white" id="edit_crm"
                                            name="crm" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_crm_commission">CRM Commission</label>
                                        <input type="text" class="form-control bg-white" id="edit_crm_commission"
                                            name="crm_commission">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_senior_director">Senior Director</label>
                                        <input type="text" class="form-control bg-white" id="edit_senior_director"
                                            name="senior_director" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_senior_director_commission">Senior Director Commission</label>
                                        <input type="text" class="form-control bg-white" id="edit_senior_director_commission"
                                            name="senior_director_commission">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_chief_director">Chief Director</label>
                                        <input type="text" class="form-control bg-white" id="edit_chief_director"
                                            name="chief_director" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="edit_chief_director_commission">Chief Director Commission</label>
                                        <input type="text" class="form-control bg-white" id="edit_chief_director_commission"
                                            name="chief_director_commission">
                                    </div>
                                </div>
                            </fieldset>
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

    {{-- View Marketer Payout Modal --}}
    <div class="modal fade" id="viewMarketerPayoutModal" tabindex="-1" role="dialog"
        aria-labelledby="viewMarketerPayoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMarketerPayoutModalLabel">View Marketer Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <form id="viewMarketerPayoutForm">
                    @csrf
                    <div class="modal-body" id="viewMarketerPayoutModalBody">
                        <input type="hidden" id="view_marketer_payout_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="view_marketer_name">Marketer Name:</label>
                                    <label class="bg-white text-muted fs-6" id="view_marketer_name"></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="view_marketer_id">Marketer ID:</label>
                                    <label class="bg-white text-muted fs-6" id="view_marketer_id"></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="view_project">Project:</label>
                                    <label class="bg-white text-muted fs-6" id="view_project"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="view_plot_no">Plot No:</label>
                                    <label class="bg-white text-muted fs-6" id="view_plot_no"></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="view_plot_sqft">Plot Sqft:</label>
                                    <label class="bg-white text-muted fs-6" id="view_plot_sqft"></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="view_plot_amount">Plot Amount:</label>
                                    <label class="bg-white text-muted fs-6" id="view_plot_amount"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="view_commission">Commission Amount:</label>
                                    <label class="bg-white text-muted fs-6" id="view_commission"></label>
                                </div>
                            </div>
                            <fieldset>
                                <h4 class="mt-2">Director Details:</h4>
                                <div class="row border m-1 pt-3">
                                    <div class="form-group col-md-6">
                                        <label for="view_director">Director:</label>
                                        <label class="bg-white text-muted fs-6" id="view_director"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_director_commission">Director Commission:</label>
                                        <label class="bg-white text-muted fs-6" id="view_director_commission"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_assist_director">Associate Director:</label>
                                        <label class="bg-white text-muted fs-6" id="view_assist_director"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_assist_director_commission">Associate Director Commission:</label>
                                        <label class="bg-white text-muted fs-6" id="view_assist_director_commission"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_crm">CRM:</label>
                                        <label class="bg-white text-muted fs-6" id="view_crm"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_crm_commission">CRM Commission:</label>
                                        <label class="bg-white text-muted fs-6" id="view_crm_commission"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_senior_director">Senior Director:</label>
                                        <label class="bg-white text-muted fs-6" id="view_senior_director"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_senior_director_commission">Senior Director Commission:</label>
                                        <label class="bg-white text-muted fs-6" id="view_senior_director_commission"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_chief_director">Chief Director:</label>
                                        <label class="bg-white text-muted fs-6" id="view_chief_director"></label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="view_chief_director_commission">Chief Director Commission:</label>
                                        <label class="bg-white text-muted fs-6" id="view_chief_director_commission"></label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                            aria-label="Close">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Plot Modal -->
    <div class="modal fade" id="deleteMarketerPayoutModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteMarketerPayoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMarketerPayoutModalLabel">Delete Marketer Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this data?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-3" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="delete_marketer_payout"
                        onclick="delete_marketer_payout()">Delete</button>
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
        function showAddMarketerPayoutModal() {
            $('#addMarketerPayoutModal').modal('show');
        }

        // Plot form validation
        $(document).ready(function() {
            $('#addMarketerPayoutForm').validate({
                rules: {
                    marketer_name: {
                        required: true,
                    },
                    marketer_id: {
                        required: true,
                    },
                    project: {
                        required: true,
                    },
                    plot_no: {
                        required: true,
                    },
                    commission: {
                        required: true,
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
                    addMarketerPayout();
                }
            });
        });

        // ADD Plot
        function addMarketerPayout() {
            var formData = $('#addMarketerPayoutForm').serialize();
            var route_url = '/marketer-payout';
            var method = 'POST';
            ajaxResponse(route_url, method, formData);
        }

        // Edit Plot Data
        function editData(marketerPayoutId) {
            var route_url = '/marketer-payout/' + marketerPayoutId + '/edit';
            var method = 'GET';
            var data = null;
            var render = "editMarketerPayoutModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#edit_marketer_payout_id").val(response.marketerPayout.id);
                $("#edit_marketer_name").val(response.marketerPayout.marketer_id);
                $("#edit_marketer_id").val(response.marketerPayout.marketer_id);
                $('#edit_director').val(response.marketerPayout.director != null ? response.marketerPayout.director : 'N/A');
                $('#edit_director_commission').val(response.marketerPayout.director_commission);
                $('#edit_assist_director').val(response.marketerPayout.assistant_director != null ? response.marketerPayout.assistant_director : 'N/A');
                $('#edit_assist_director_commission').val(response.marketerPayout.assistant_director_commission);
                $('#edit_crm').val(response.marketerPayout.crm != null ? response.marketerPayout.crm : 'N/A');
                $('#edit_crm_commission').val(response.marketerPayout.crm_commission);
                $('#edit_senior_director').val(response.marketerPayout.senior_director ? response.marketerPayout.senior_director : 'N/A');
                $('#edit_senior_director_commission').val(response.marketerPayout.senior_director_commission);
                $('#edit_chief_director').val(response.marketerPayout.chief_director ? response.marketerPayout.chief_director : 'N/A');
                $('#edit_chief_director_commission').val(response.marketerPayout.chief_director_commission);
                $('#edit_plot_amount').val(response.marketerPayout.plot.total_amount);
                $('#edit_plot_sqft').val(response.marketerPayout.plot.plot_sqft);
                $('#edit_commission').val(response.marketerPayout.commission);

                // Fetch projects data via AJAX
                $.ajax({
                    url: '{{ route('marketer-payout.fetch-projects') }}',
                    method: 'POST',
                    data: {
                        marketer_id: response.marketerPayout.marketer_id
                    },
                    success: function(projectsResponse) {
                        var editProjectDropdown = $('#edit_project');
                        editProjectDropdown.empty().append($('<option>', {
                            value: '',
                            text: 'Select Project'
                        }));
                        $.each(projectsResponse.projectsData, function(index, project) {
                            var option = $('<option>', {
                                value: project.id,
                                text: project.project_name
                            });
                            if (project.id == response.marketerPayout.project_id) {
                                option.prop('selected', true);
                            }
                            editProjectDropdown.append(option);
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

                $.ajax({
                    url: '{{ route('marketer-payout.fetch-plots') }}',
                    method: 'POST',
                    data: {
                        project_id: response.marketerPayout.project_id
                    },
                    success: function(plotsResponse) {
                        var editPlotDropdown = $('#edit_plot_no');
                        editPlotDropdown.empty().append($('<option>', {
                            value: '',
                            text: 'Select Plot No'
                        }));
                        $.each(plotsResponse, function(index, plot) {
                            var option = $('<option>', {
                                value: plot.id,
                                text: plot.plot_no
                            });
                            if (plot.id == response.marketerPayout.plot_id) {
                                option.prop('selected', true);
                            }
                            editPlotDropdown.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

                $("#" + render).modal("show");
            });
        }

        // View data
        function viewData(marketerPayoutId) {
            var route_url = '/marketer-payout/' + marketerPayoutId;
            var method = 'GET';
            var data = null;
            var render = "viewMarketerPayoutModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#view_marketer_payout_id").text(response.marketerPayout.id);
                $("#view_marketer_name").text(response.marketerPayout.marketer.name ?? 'N/A');
                $("#view_marketer_id").text(response.marketerPayout.marketer.marketer_vcity_id ?? 'N/A');
                $('#view_director').text(response.marketerPayout.director != null ? response.marketerPayout.director : 'N/A');
                $('#view_director_commission').text(response.marketerPayout.director_commission ?? 'N/A');
                $('#view_assist_director').text(response.marketerPayout.assistant_director != null ? response.marketerPayout.assistant_director : 'N/A');
                $('#view_assist_director_commission').text(response.marketerPayout.assistant_director_commission ?? 'N/A');
                $('#view_crm').text(response.marketerPayout.crm != null ? response.marketerPayout.crm: 'N/A');
                $('#view_crm_commission').text(response.marketerPayout.crm_commission ?? 'N/A');
                $('#view_senior_director').text(response.marketerPayout.senior_director != null ? response.marketerPayout.senior_director : 'N/A');
                $('#view_senior_director_commission').text(response.marketerPayout.senior_director_commission ?? 'N/A');
                $('#view_chief_director').text(response.marketerPayout.chief_director != null ? response.marketerPayout.chief_director : 'N/A');
                $('#view_chief_director_commission').text(response.marketerPayout.chief_director_commission ?? 'N/A');
                $('#view_plot_amount').text(response.marketerPayout.plot.total_amount ?? 'N/A');
                $('#view_plot_sqft').text(response.marketerPayout.plot.plot_sqft ?? 'N/A');
                $('#view_commission').text(response.marketerPayout.commission ?? 'N/A');
                $('#view_project').text(response.marketerPayout.project.project_name ?? 'N/A');
                $('#view_plot_no').text(response.marketerPayout.plot.plot_no ?? 'N/A');
                $("#" + render).modal("show");
            });
        }

        $(document).ready(function() {
            $('#editMarketerPayoutForm').validate({
                rules: {
                    marketer_name: {
                        required: true,
                    },
                    project: {
                        required: true,
                    },
                    plot_no: {
                        required: true,
                    },
                    commission: {
                        required: true,
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
                    updateMarketerPayout();
                }
            });
        });

        // Update Plot Data
        function updateMarketerPayout() {
            var marketerPayoutId = $('#edit_marketer_payout_id').val();
            var formData = $('#editMarketerPayoutForm').serialize();
            var route_url = '/marketer-payout/' + marketerPayoutId;
            var method = 'PUT';
            ajaxResponse(route_url, method, formData);
        }

        // Delete Modal Popup
        function deleteData(MarketerPayoutId) {
            $('#deleteMarketerPayoutModal').modal('show');
            $('#delete_marketer_payout').data('marketer_payout-id', MarketerPayoutId);
        }

        // Delete Marketer Payout Data
        function delete_marketer_payout() {
            var MarketerPayoutId = $('#delete_marketer_payout').data('marketer_payout-id');
            var route_url = '/marketer-payout/' + MarketerPayoutId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        // fetch projects and plots based on marketers
        $(document).ready(function() {
            $('#marketer_name, #marketer_id').change(function() {
                $('#project').val('');
                $('#plot_no').val('');
                $('#plot_sqft').val('');
                $('#plot_amount').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');

                if (selectedField == 'marketer_name') {
                    $('#marketer_id').val(selectedValue);
                } else if (selectedField == 'marketer_id') {
                    $('#marketer_name').val(selectedValue);
                }
                $.ajax({
                    url: '{{ route('marketer-payout.fetch-projects') }}',
                    method: 'POST',
                    data: {
                        marketer_id: selectedValue
                    },
                    success: function(response) {
                        $('#director').val(response.marketers.director != null ? response.marketers.director.name : 'N/A');
                        $('#assist_director').val(response.marketers.assistant_director != null ? response.marketers.assistant_director.name : 'N/A');
                        $('#crm').val(response.marketers.crm != null ? response.marketers.crm.name : 'N/A');
                        $('#senior_director').val(response.marketers.senior_director != null ? response.marketers.senior_director.name : 'N/A');
                        $('#chief_director').val(response.marketers.chief_director != null ? response.marketers.chief_director.name : 'N/A');
                        $('#project').empty().append($('<option>', {
                            value: '',
                            text: 'Select Project'
                        }));
                        $.each(response.projectsData, function(index, project) {
                            $('#project').append($('<option>', {
                                value: project.id,
                                text: project.project_name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#project').change(function() {
                var projectId = $(this).val();
                $.ajax({
                    url: '{{ route('marketer-payout.fetch-plots') }}',
                    method: 'POST',
                    data: {
                        project_id: projectId
                    },
                    success: function(response) {
                        $('#plot_no').empty();
                        $('#plot_no').append($('<option>', {
                            value: '',
                            text: 'Select Plot No'
                        }));
                        $.each(response, function(index, plot) {
                            $('#plot_no').append($('<option>', {
                                value: plot.id,
                                text: plot.plot_no
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('change', '#plot_no', function() {
                var route_url = '/payments/getdata/' + $(this).val();
                var method = 'GET';
                var data = null;
                var render = "plot_amount"
                ajaxResponseRender(route_url, method, data, render, function(response) {
                    $("#" + render).val(response.plot_amount.total_amount);
                    $("#plot_sqft").val(response.plot_amount.plot_sqft);
                });
            });
        });

        $(document).ready(function() {
            $('#edit_marketer_name, #edit_marketer_id').change(function() {
                $('#edit_project').val('');
                $('#edit_plot_no').val('');
                $('#edit_plot_sqft').val('');
                $('#edit_plot_amount').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');

                if (selectedField == 'edit_marketer_name') {
                    $('#edit_marketer_id').val(selectedValue);
                } else if (selectedField == 'edit_marketer_id') {
                    $('#edit_marketer_name').val(selectedValue);
                }
                $.ajax({
                    url: '{{ route('marketer-payout.fetch-projects') }}',
                    method: 'POST',
                    data: {
                        marketer_id: selectedValue
                    },
                    success: function(response) {
                        $('#edit_director').val(response.marketers.director);
                        $('#edit_director_id').val(response.marketers.director_vcity_id);
                        $('#edit_assist_director').val(response.marketers.assistant_director);
                        $('#edit_assist_director_id').val(response.marketers.ad_vcity_id);
                        $('#edit_crm').val(response.marketers.crm);
                        $('#edit_crm_id').val(response.marketers.crm_vcity_id);
                        $('#edit_senior_director').val(response.marketers.senior_director);
                        $('#edit_senior_director_id').val(response.marketers.senior_vcity_id);
                        $('#edit_chief_director').val(response.marketers.chief_director);
                        $('#edit_chief_director_id').val(response.marketers.chief_vcity_id);
                        $('#edit_project').empty().append($('<option>', {
                            value: '',
                            text: 'Select Project'
                        }));
                        $.each(response.projectsData, function(index, project) {
                            $('#edit_project').append($('<option>', {
                                value: project.id,
                                text: project.project_name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#edit_project').change(function() {
                var projectId = $(this).val();
                $.ajax({
                    url: '{{ route('marketer-payout.fetch-plots') }}',
                    method: 'POST',
                    data: {
                        project_id: projectId
                    },
                    success: function(response) {
                        $('#edit_plot_no').empty();
                        $('#edit_plot_no').append($('<option>', {
                            value: '',
                            text: 'Select Plot No'
                        }));
                        $.each(response, function(index, plot) {
                            $('#edit_plot_no').append($('<option>', {
                                value: plot.id,
                                text: plot.plot_no
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('change', '#edit_plot_no', function() {
                var route_url = '/payments/getdata/' + $(this).val();
                var method = 'GET';
                var data = null;
                var render = "edit_plot_amount"
                ajaxResponseRender(route_url, method, data, render, function(response) {
                    $("#" + render).val(response.plot_amount.total_amount);
                    $("#edit_plot_sqft").val(response.plot_amount.plot_sqft);
                });
            });
        });

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#marketerpayout-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
