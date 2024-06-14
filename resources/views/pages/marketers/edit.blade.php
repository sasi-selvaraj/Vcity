@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketers'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Edit Marketers</h4>
                        <div class="justify-content-end">
                            <a href="{{ route('marketers.index') }}" class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Edit Marketer -->
                    <form id="editMarketerForm" enctype="multipart/form-data" action="#">
                        @csrf
                        <input type="hidden" id="edit_Marketer_id" value="{{ $marketer->id }}">
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="col-md-4 d-flex align-items-center">
                                            <label for="edit_marketer_id">Marketer ID:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control bg-white" id="edit_marketer_id"
                                                value="{{ $marketer->marketer_vcity_id }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_marketer_name">Marketer Name<span
                                                class="mandatory_fields">*</span></label>
                                        <input type="text" class="form-control" id="edit_marketer_name" name="name"
                                            value="{{ $marketer->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_designation">Designation<span
                                                class="mandatory_fields">*</span></label>
                                        <select name="designation" id="edit_designation" class="form-select">
                                            <option value="">Select Designation</option>
                                            <option value="CC" {{ $marketer->designation == 'CC' ? 'selected' : '' }}>CC
                                            </option>
                                            <option value="CRM" {{ $marketer->designation == 'CRM' ? 'selected' : '' }}>
                                                CRM
                                            </option>
                                            <option value="AD" {{ $marketer->designation == 'AD' ? 'selected' : '' }}>AD
                                            </option>
                                            <option value="Dir" {{ $marketer->designation == 'Dir' ? 'selected' : '' }}>
                                                Dir
                                            </option>
                                            <option value="SD" {{ $marketer->designation == 'SD' ? 'selected' : '' }}>SD
                                            </option>
                                            <option value="CD" {{ $marketer->designation == 'CD' ? 'selected' : '' }}>CD
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @php
                                            $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                                        @endphp
                                        <label for="edit_date">Date</label>
                                        <input type="date" class="form-control" id="edit_date" name="date"
                                            value="{{ $marketer->date }}" max="{{ $minDate }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_email">Email Address</label>
                                        <input type="email" class="form-control" id="edit_email" name="email"
                                            value="{{ $marketer->email }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_father_name">Father's Name</label>
                                        <input type="father_name" class="form-control" id="edit_father_name"
                                            name="father_name" value="{{ $marketer->father_name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        @php
                                            $maxDate = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                                        @endphp
                                        <label for="edit_dob">Date Of Birth</label>
                                        <input type="date" class="form-control" id="edit_dob" name="dob"
                                            value="{{ $marketer->dob }}" max="{{ $maxDate }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_qualification">Educational Qualification</label>
                                        <input type="qualification" class="form-control" id="edit_qualification"
                                            name="qualification" value="{{ $marketer->qualification }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_aadhar_no">Aadhar Number</label>
                                        <input type="number" class="form-control" id="edit_aadhar_no" name="aadhar_no"
                                            value="{{ $marketer->aadhar_no }}" min="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_pan_no">PAN Number</label>
                                        <input type="text" class="form-control" id="edit_pan_no" name="pan_no"
                                            value="{{ $marketer->pan_no }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_acc_no">Account No.</label>
                                        <input type="text" class="form-control" id="edit_acc_no" name="acc_no"
                                            value="{{ $marketer->acc_no }}" pattern="[A-Z0-9]*">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_ifsc_code">IFSC Code</label>
                                        <input type="text" class="form-control" id="edit_ifsc_code" name="ifsc_code"
                                            value="{{ $marketer->ifsc_code }}" pattern="[A-Z0-9]*">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_branch">Branch</label>
                                        <input type="text" class="form-control" id="edit_branch" name="branch"
                                            value="{{ $marketer->branch }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_mobile_no">Mobile Number<span
                                                class="mandatory_fields">*</span></label>
                                        <input type="number" class="form-control" id="edit_mobile_no" name="mobile_no"
                                            value="{{ $marketer->mobile_no }}" min="0">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_pincode">Pincode</label>
                                        <input type="text" class="form-control" id="edit_pincode" name="pincode"
                                            value="{{ $marketer->pincode }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_city">City</label>
                                        <input type="text" class="form-control" id="edit_city" name="city"
                                            value="{{ $marketer->city }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_address">Complete Address</label>
                                        <textarea class="form-control" id="edit_address" name="address" rows="1">{{ $marketer->address }}</textarea>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="form-group col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label for="edit_renewal_date">Last Payment:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control bg-white text-primary" id="edit_last_payment"
                                                    value="{{ $marketer->days_ago }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 d-flex align-items-center">
                                                <label for="edit_renewal_date">Renewal Date:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="date" class="form-control bg-white text-primary" id="edit_renewal_date" name="renewal_date"
                                                    value="{{$marketer->renewal_date}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <fieldset id="edit_directorDetails">
                                    <h5 class="mt-2">Director Details:</h5>
                                    <div class="row border m-1 pt-3">
                                        <div class="form-group col-md-6">
                                            <label for="edit_crm">Customer Relationship Manager(CRM)</label>
                                            <select name="crm" id="edit_crm" class="form-select">
                                                <option value="">Select CRM</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'CRM')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->crm ? 'selected' : '' }}>
                                                                {{ $v->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_crm_id">Customer Relationship Manager(CRM) ID</label>
                                            <select name="crm_id" id="edit_crm_id" class="form-select">
                                                <option value="">Select CRM ID</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'CRM')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->crm ? 'selected' : '' }}>
                                                                {{ $v->marketer_vcity_id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_ad">Associate Director</label>
                                            <select name="ad" id="edit_ad" class="form-select">
                                                <option value="">Select Associate Director</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'AD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->assistant_director ? 'selected' : '' }}>
                                                                {{ $v->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_ad_id">Associate Director ID</label>
                                            <select name="ad_id" id="edit_ad_id" class="form-select">
                                                <option value="">Select Associate Director ID</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'AD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->assistant_director ? 'selected' : '' }}>
                                                                {{ $v->marketer_vcity_id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_director">Director</label>
                                            <select name="director" id="edit_director" class="form-select">
                                                <option value="">Select Director</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'Dir')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->director ? 'selected' : '' }}>
                                                                {{ $v->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_director_id">Director ID</label>
                                            <select name="director_id" id="edit_director_id" class="form-select">
                                                <option value="">Select Director ID</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'Dir')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->director ? 'selected' : '' }}>
                                                                {{ $v->marketer_vcity_id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_senior_director">Senior Director</label>
                                            <select name="senior_director" id="edit_senior_director" class="form-select">
                                                <option value="">Select Senior Director</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'SD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->senior_director ? 'selected' : '' }}>
                                                                {{ $v->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_senior_director_id">Senior Director ID</label>
                                            <select name="senior_director_id" id="edit_senior_director_id"
                                                class="form-select">
                                                <option value="">Select Senior Director ID</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'SD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->senior_director ? 'selected' : '' }}>
                                                                {{ $v->marketer_vcity_id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_chief_director">Chief Director</label>
                                            <select name="chief_director" id="edit_chief_director" class="form-select">
                                                <option value="">Select Chief Director</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'CD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->chief_director ? 'selected' : '' }}>
                                                                {{ $v->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit_chief_director_id">Chief Director ID</label>
                                            <select name="chief_director_id" id="edit_chief_director_id"
                                                class="form-select">
                                                <option value="">Select Chief Director ID</option>
                                                @isset($marketers)
                                                    @foreach ($marketers as $v)
                                                        @if ($v->designation == 'CD')
                                                            <option value="{{ $v->id }}"
                                                                {{ $v->id == $marketer->chief_director ? 'selected' : '' }}>
                                                                {{ $v->marketer_vcity_id }}</option>
                                                        @endif
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('marketers.index') }}" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script>
        // Marketer form validation
        $(document).ready(function() {
            $('#editMarketerForm').validate({
                rules: {
                    marketer_id: {
                        required: true,
                    },
                    name: {
                        required: true,
                        minlength: 3,
                    },
                    designation: {
                        required: true,
                    },
                    email: {
                        email: true,
                    },
                    father_name: {
                        minlength: 3,
                    },
                    pincode: {
                        number: true,
                        minlength: 6,
                        maxlength: 6,
                    },
                    mobile_no: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    aadhar_no: {
                        number: true,
                        minlength: 12,
                        maxlength: 12,
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
                    updateMarketer();
                }
            });
        });


        function updateMarketer() {
            var marketerId = $('#edit_Marketer_id').val();
            var formData = $('#editMarketerForm').serialize();
            var route_url = '/marketers/' + marketerId;
            var method = 'PUT';
            ajaxResponse(route_url, method, formData, function(response) {
                window.location.href = '/marketers';
            });
        }


        $('.delete-attachment').click(function() {
            let id = $(this).data('id');
            let delete_url = "{{ route('marketers.remove.attachment') }}";
            if (id != "") {
                deleteModal(delete_url, id, 'page');
            } else {
                alertNotify('Something went wrong');
            }
        });

        $(document).ready(function() {
            $('#edit_chief_director, #edit_chief_director_id').change(function() {
                $('#edit_crm, #edit_crm_id, #edit_director, #edit_director_id, #edit_ad, #edit_ad_id, #edit_senior_director, #edit_senior_director_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'edit_chief_director') {
                    $('#edit_chief_director_id').val(selectedValue);
                } else if (selectedField == 'edit_chief_director_id') {
                    $('#edit_chief_director').val(selectedValue);
                }
            });
        });

        // Change small to block letters
        $(document).ready(function() {
            $('#edit_acc_no,#edit_ifsc_code,#edit_pan_no').on('input', function() {
                var newValue = $(this).val().toUpperCase();
                $(this).val(newValue);
            });
        });

        $(document).ready(function() {
            var selectedDesignation = $('#edit_designation').val();
            $('#edit_directorDetails select').removeClass('pe-none');
            enableDirectors(selectedDesignation); // Pass the initial value

            $('#edit_designation').on('change', function() {
            $('#edit_crm, #edit_crm_id, #edit_ad, #edit_ad_id,  #edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id').val('');
                var selectedDesignation = $(this).val();
                $('#edit_directorDetails select').removeClass('pe-none');
                enableDirectors(selectedDesignation);
            });

            function enableDirectors(selectedDesignation) {
                switch (selectedDesignation) {
                    case 'CC':
                        $('#edit_crm, #edit_crm_id, #edit_ad, #edit_ad_id,  #edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                            .removeClass('pe-none');
                        break;
                    case 'CRM':
                        $('#edit_ad, #edit_ad_id, #edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                            .removeClass('pe-none');
                        $('#edit_crm, #edit_crm_id')
                            .addClass('pe-none');
                        break;
                    case 'AD':
                        $('#edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                            .removeClass('pe-none');
                        $('#edit_crm, #edit_crm_id, #edit_ad, #edit_ad_id')
                            .addClass('pe-none');
                        break;
                    case 'Dir':
                        $('#edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                            .removeClass('pe-none');
                        $('#edit_crm, #edit_crm_id, #edit_director, #edit_director_id, #edit_ad, #edit_ad_id')
                            .addClass('pe-none');
                        break;
                    case 'SD':
                        $('#edit_chief_director, #edit_chief_director_id')
                            .removeClass('pe-none');
                        $('#edit_crm, #edit_crm_id, #edit_director, #edit_director_id, #edit_ad, #edit_ad_id, #edit_senior_director, #edit_senior_director_id')
                            .addClass('pe-none');
                        break;
                    case 'CD':
                        $('#edit_crm, #edit_crm_id, #edit_director, #edit_director_id, #edit_ad, #edit_ad_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                            .addClass('pe-none');
                        break;
                    default:
                        $('#edit_directorDetails').addClass(
                            'pe-none');
                }
            }

            $('#edit_senior_director, #edit_senior_director_id').change(function() {
            $('#edit_crm, #edit_crm_id, #edit_director, #edit_director_id, #edit_ad, #edit_ad_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'edit_senior_director') {
                    $('#edit_senior_director_id').val(selectedValue);
                } else if (selectedField == 'edit_senior_director_id') {
                    $('#edit_senior_director').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#edit_chief_director, #edit_chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                } else {
                    $('#edit_chief_director, #edit_chief_director_id')
                        .val('');
                    return;
                }
            });

            $('#edit_director, #edit_director_id').change(function() {
            $('#edit_crm, #edit_crm_id, #edit_ad, #edit_ad_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'edit_director') {
                    $('#edit_director_id').val(selectedValue);
                } else if (selectedField == 'edit_director_id') {
                    $('#edit_director').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#edit_senior_director, #edit_senior_director_id').val(response
                                .seniorDirector);
                            $('#edit_chief_director, #edit_chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                } else {
                    $('#edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                        .val('');
                    return;
                }
            });

            $('#edit_ad, #edit_ad_id').change(function() {
            $('#edit_crm, #edit_crm_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'edit_ad') {
                    $('#edit_ad_id').val(selectedValue);
                } else if (selectedField == 'edit_ad_id') {
                    $('#edit_ad').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#edit_director, #edit_director_id').val(response.director);
                            $('#edit_senior_director, #edit_senior_director_id').val(response
                                .seniorDirector);
                            $('#edit_chief_director, #edit_chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                } else {
                    $('#edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                        .val('');
                    return;
                }
            });

            $('#edit_crm, #edit_crm_id').change(function() {
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'edit_crm') {
                    $('#edit_crm_id').val(selectedValue);
                } else if (selectedField == 'edit_crm_id') {
                    $('#edit_crm').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#edit_ad, #edit_ad_id').val(response.assistantDirector);
                            $('#edit_director, #edit_director_id').val(response.director);
                            $('#edit_senior_director, #edit_senior_director_id').val(response
                                .seniorDirector);
                            $('#edit_chief_director, #edit_chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                } else {
                    $('#edit_ad, #edit_ad_id, #edit_director, #edit_director_id, #edit_senior_director, #edit_senior_director_id, #edit_chief_director, #edit_chief_director_id')
                        .val('');
                    return;
                }
            });
        });
    </script>
@endpush
