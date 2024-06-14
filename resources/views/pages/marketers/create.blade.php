@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketers'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Create Marketers</h4>
                        <div class="justify-content-end">
                            <a href="{{ route('marketers.index') }}" class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Add Marketer -->
                    <form id="addMarketerForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center">
                                        <label for="marketer_id" class="my-0">Marketer ID: </label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control bg-white" id="marketer_id"
                                            value="{{ $marketer_vcity_id }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="marketer_name">Marketer Name<span class="mandatory_fields">*</span></label>
                                    <input type="text" class="form-control" id="marketer_name" name="name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="designation">Designation<span class="mandatory_fields">*</span></label>
                                    <select name="designation" id="designation" class="form-select">
                                        <option value="">Select Designation</option>
                                        <option value="CC">CC</option>
                                        <option value="CRM">CRM</option>
                                        <option value="AD">AD</option>
                                        <option value="Dir">Dir</option>
                                        <option value="SD">SD</option>
                                        <option value="CD">CD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    @php
                                        $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        max="{{ $minDate }}" value="{{ $minDate }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="father_name">Father's Name</label>
                                    <input type="text" class="form-control" id="father_name" name="father_name">
                                </div>
                                <div class="form-group col-md-6">
                                    @php
                                        $maxDate = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                                    @endphp
                                    <label for="dob">Date Of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob"
                                        max="{{ $maxDate }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="qualification">Educational Qualification</label>
                                    <input type="qualification" class="form-control" id="qualification"
                                        name="qualification">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="aadhar_no">Aadhar Number</label>
                                    <input type="number" class="form-control" id="aadhar_no" name="aadhar_no"
                                        min="0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="pan_no">PAN Number</label>
                                    <input type="text" class="form-control" id="pan_no" name="pan_no">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="acc_no">Account No.</label>
                                    <input type="text" class="form-control" id="acc_no" name="acc_no">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="branch">Branch</label>
                                    <input type="text" class="form-control" id="branch" name="branch">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="mobile_no">Mobile Number<span class="mandatory_fields">*</span></label>
                                    <input type="number" class="form-control" id="mobile_no" name="mobile_no"
                                        min="0">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pincode">Pincode</label>
                                    <input type="number" class="form-control" id="pincode" name="pincode"
                                        min="0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">Complete Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="1"></textarea>
                                </div>
                            </div>

                            <fieldset id="directorDetails" class="pe-none">
                                <h5 class="mt-2">Director Details:</h5>
                                <div class="row border m-1 pt-3">
                                    <div class="form-group col-md-6">
                                        <label for="crm">Customer Relationship Manager(CRM)</label>
                                        <select name="crm" id="crm" class="form-select">
                                            <option value="">Select CRM</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'CRM')
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="crm_id">Customer Relationship Manager(CRM) ID</label>
                                        <select name="crm_id" id="crm_id" class="form-select">
                                            <option value="">Select CRM ID</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'CRM')
                                                        <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="ad">Associate Director</label>
                                        <select name="ad" id="ad" class="form-select">
                                            <option value="">Select Associate Director</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'AD')
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="ad_id">Associate Director ID</label>
                                        <select name="ad_id" id="ad_id" class="form-select">
                                            <option value="">Select Associate Director ID</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'AD')
                                                        <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="director">Director</label>
                                        <select name="director" id="director" class="form-select">
                                            <option value="">Select Director</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'Dir')
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="director_id">Director ID</label>
                                        <select name="director_id" id="director_id" class="form-select">
                                            <option value="">Select Director ID</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'Dir')
                                                        <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="senior_director">Senior Director</label>
                                        <select name="senior_director" id="senior_director" class="form-select">
                                            <option value="">Select Senior Director</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'SD')
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="senior_director_id">Senior Director ID</label>
                                        <select name="senior_director_id" id="senior_director_id" class="form-select">
                                            <option value="">Select Senior Director ID</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'SD')
                                                        <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="chief_director">Chief Director</label>
                                        <select name="chief_director" id="chief_director" class="form-select">
                                            <option value="">Select Chief Director</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'CD')
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="chief_director_id">Chief Director ID</label>
                                        <select name="chief_director_id" id="chief_director_id" class="form-select">
                                            <option value="">Select Chief Director ID</option>
                                            @isset($directors)
                                                @foreach ($directors as $v)
                                                    @if ($v->designation == 'CD')
                                                        <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}
                                                        </option>
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
                            <button type="submit" class="btn btn-primary">Save</button>
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
            $('#addMarketerForm').validate({
                rules: {
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
                    addMarketer();
                }
            });
        });


        // ADD Marketer
        function addMarketer() {
            var formData = $('#addMarketerForm').serialize();
            var route_url = '/marketers';
            var method = 'POST';
            ajaxResponse(route_url, method, formData, function(response) {
                window.location.href = '/marketers';
            });
        }

        $(document).ready(function() {
            $('#chief_director, #chief_director_id').change(function() {
                $('#crm, #crm_id, #director, #director_id, #ad, #ad_id, #senior_director, #senior_director_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'chief_director') {
                    $('#chief_director_id').val(selectedValue);
                } else if (selectedField == 'chief_director_id') {
                    $('#chief_director').val(selectedValue);
                }
            });
        });

        // Change small to block letters
        $(document).ready(function() {
            $('#acc_no,#ifsc_code,#pan_no').on('input', function() {
                var newValue = $(this).val().toUpperCase();
                $(this).val(newValue);
            });
        });

        $(document).ready(function() {
            $('#designation').on('change', function() {
            $('#crm, #crm_id, #ad, #ad_id,  #director, #director_id, #senior_director, #senior_director_id, #chief_director, #chief_director_id').val('');
                $('#directorDetails').removeClass(
                    'pe-none');
                var selectedDesignation = $(this).val();

                switch (selectedDesignation) {
                    case 'CC':
                        $('#crm, #crm_id, #ad, #ad_id,  #director, #director_id, #senior_director, #senior_director_id, #chief_director, #chief_director_id')
                            .removeClass('pe-none');
                        break;
                    case 'CRM':
                        $('#ad, #ad_id, #director, #director_id, #senior_director, #senior_director_id, #chief_director, #chief_director_id')
                            .removeClass('pe-none');
                        $('#crm, #crm_id')
                            .addClass('pe-none');
                        break;
                    case 'AD':
                        $('#director, #director_id, #senior_director, #senior_director_id, #chief_director, #chief_director_id')
                            .removeClass('pe-none');
                        $('#crm, #crm_id, #ad, #ad_id')
                            .addClass('pe-none');
                        break;
                    case 'Dir':
                        $('#senior_director, #senior_director_id, #chief_director, #chief_director_id')
                            .removeClass('pe-none');
                        $('#crm, #crm_id, #director, #director_id, #ad, #ad_id')
                            .addClass('pe-none');
                        break;
                    case 'SD':
                        $('#chief_director, #chief_director_id')
                            .removeClass('pe-none');
                        $('#crm, #crm_id, #director, #director_id, #ad, #ad_id, #senior_director, #senior_director_id')
                            .addClass('pe-none');
                        break;
                    case 'CD':
                        $('#crm, #crm_id, #director, #director_id, #ad, #ad_id, #senior_director, #senior_director_id, #chief_director, #chief_director_id')
                            .addClass('pe-none');
                        break;
                    default:
                        $('#directorDetails').addClass(
                            'pe-none');
                }
            });

            $('#senior_director, #senior_director_id').change(function() {
                $('#crm, #crm_id, #director, #director_id, #ad, #ad_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'senior_director') {
                    $('#senior_director_id').val(selectedValue);
                } else if (selectedField == 'senior_director_id') {
                    $('#senior_director').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#chief_director, #chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                }
            });

            $('#director, #director_id').change(function() {
                $('#crm, #crm_id, #ad, #ad_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'director') {
                    $('#director_id').val(selectedValue);
                } else if (selectedField == 'director_id') {
                    $('#director').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#senior_director, #senior_director_id').val(response
                                .seniorDirector);
                            $('#chief_director, #chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                }
            });

            $('#ad, #ad_id').change(function() {
                $('#crm, #crm_id').val('');
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'ad') {
                    $('#ad_id').val(selectedValue);
                } else if (selectedField == 'ad_id') {
                    $('#ad').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#director, #director_id').val(response.director);
                            $('#senior_director, #senior_director_id').val(response
                                .seniorDirector);
                            $('#chief_director, #chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                }
            });

            $('#crm, #crm_id').change(function() {
                var selectedValue = $(this).val();
                var selectedField = $(this).attr('id');
                if (selectedField == 'crm') {
                    $('#crm_id').val(selectedValue);
                } else if (selectedField == 'crm_id') {
                    $('#crm').val(selectedValue);
                }
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('fetch.director.details') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            $('#ad, #ad_id').val(response.assistantDirector);
                            $('#director, #director_id').val(response.director);
                            $('#senior_director, #senior_director_id').val(response
                                .seniorDirector);
                            $('#chief_director, #chief_director_id').val(response
                                .chiefDirector);
                        },
                        error: function() {}
                    });
                }
            });
        });
    </script>
@endpush
