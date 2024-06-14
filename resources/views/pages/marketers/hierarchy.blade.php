@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .small-text {
            font-size: smaller;
        }
    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketers Hierarchy'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <div class="">
                            <a href="{{ route('marketers.hierarchy') }}" class="btn btn-secondary btn-round text-white"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                            <a href="{{ route('marketers.index') }}" class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset id="directorDetails">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="chief_director">Chief Director</label>
                                <select name="chief_director" id="chief_director" class="select2">
                                    <option value="">Select Chief Director</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'CD')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="senior_director">Senior Director</label>
                                <select name="senior_director" id="senior_director" class="select2">
                                    <option value="">Select Senior Director</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'SD')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="director">Director</label>
                                <select name="director" id="director" class="select2">
                                    <option value="">Select Director</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'Dir')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ad">Associate Director</label>
                                <select name="ad" id="ad" class="select2">
                                    <option value="">Select Associate Director</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'AD')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="crm">CRM</label>
                                <select name="crm" id="crm" class="select2">
                                    <option value="">Select CRM</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'CRM')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cc">CC</label>
                                <select name="cc" id="cc" class="select2">
                                    <option value="">Select CC</option>
                                    @isset($directors)
                                        @foreach ($directors as $v)
                                            @if ($v->designation == 'CC')
                                                <option value="{{ $v->id }}">{{ $v->name }} -
                                                    {{ $v->marketer_vcity_id }}</option>
                                            @endif
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6>Assigned Directors:</h6>
                    <table class="table table-responsive table-bordered table-striped">
                        <tr class="">
                            <th>CC</th>
                            <th>CRM</th>
                            <th>AD</th>
                            <th>Dir</th>
                            <th>SD</th>
                            <th>CD</th>
                        </tr>
                        <tr>
                            <td id="ccFor" style="width: 16.66%;"></td>
                            <td id="crmFor" style="width: 16.66%;"></td>
                            <td id="adFor" style="width: 16.66%;"></td>
                            <td id="dirFor" style="width: 16.66%;"></td>
                            <td id="sdFor" style="width: 16.66%;"></td>
                            <td id="cdFor" style="width: 16.66%;"></td>
                        </tr>
                    </table>

                    <h6 class="mt-4">Assigned To:</h6>
                    <table class="table table-responsive table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>CC</th>
                                <th>CRM</th>
                                <th>AD</th>
                                <th>Dir</th>
                                <th>SD</th>
                                <th>CD</th>
                            </tr>
                            <tr class="align-top">
                                <td id="ccTo" style="width: 16.66%;"></td>
                                <td id="crmTo" style="width: 16.66%;"></td>
                                <td id="adTo" style="width: 16.66%;"></td>
                                <td id="dirTo" style="width: 16.66%;"></td>
                                <td id="sdTo" style="width: 16.66%;"></td>
                                <td id="cdTo" style="width: 16.66%;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                dropdownParent: $('#directorDetails'),
                width: '100%',
            });
            $('#cc').change(function() {
                // $('#crm').select2("destroy");
                // $("#crm").select2();
                $('#crm, #ad, #director, #senior_director, #chief_director').val('');
                $('#crm, #ad, #director, #senior_director, #chief_director').trigger('change.select2');
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:
                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
            $('#crm').change(function() {
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                $('#cc, #ad, #director, #senior_director, #chief_director').val('');
                $('#cc, #ad, #director, #senior_director, #chief_director').trigger('change.select2');
                // $('#crm, #ad, #director, #senior_director, #chief_director').empty().trigger('change');
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:
                                            // Handle any other designations here
                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
            $('#ad').change(function() {
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                $('#cc, #crm, #director, #senior_director, #chief_director').val('')
                $('#cc, #crm, #director, #senior_director, #chief_director').trigger('change.select2');
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:
                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
            $('#director').change(function() {
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                $('#cc, #crm, #ad, #senior_director, #chief_director').val('')
                $('#cc, #crm, #ad, #senior_director, #chief_director').trigger('change.select2');
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:

                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
            $('#senior_director').change(function() {
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                $('#cc, #crm, #ad, #director, #chief_director').val('')
                $('#cc, #crm, #ad, #director, #chief_director').trigger('change.select2');
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:
                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
            $('#chief_director').change(function() {
                $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor')
                    .empty();
                $('#cc, #crm, #ad, #director, #senior_director').val('')
                $('#cc, #crm, #ad, #director, #senior_director').trigger('change.select2');
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('marketers.fetch.hierarchy') }}',
                        method: 'GET',
                        data: {
                            directorId: selectedValue
                        },
                        success: function(response) {
                            if (response.ccName && response.ccName.name) {
                                $('#ccFor').text(response.ccName.name);
                            } else {
                                $('#ccFor').text('N/A');
                            }
                            if (response.crmName && response.crmName.name) {
                                $('#crmFor').text(response.crmName.name);
                            } else {
                                $('#crmFor').text('N/A');
                            }
                            if (response.adName && response.adName.name) {
                                $('#adFor').text(response.adName.name);
                            } else {
                                $('#adFor').text('N/A');
                            }
                            if (response.dirName && response.dirName.name) {
                                $('#dirFor').text(response.dirName.name);
                            } else {
                                $('#dirFor').text('N/A');
                            }
                            if (response.sdName && response.sdName.name) {
                                $('#sdFor').text(response.sdName.name);
                            } else {
                                $('#sdFor').text('N/A');
                            }
                            if (response.cdName && response.cdName.name) {
                                $('#cdFor').text(response.cdName.name);
                            } else {
                                $('#cdFor').text('N/A');
                            }
                            if (response.assignedTo) {
                                response.assignedTo.forEach(function(item) {
                                    var assignedToText = item.name;
                                    switch (item.designation.toLowerCase()) {
                                        case 'cc':
                                            $('#ccTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'crm':
                                            $('#crmTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'ad':
                                            $('#adTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'dir':
                                            $('#dirTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'sd':
                                            $('#sdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        case 'cd':
                                            $('#cdTo').append('<li>' + assignedToText +
                                                '</li>');
                                            break;
                                        default:
                                            break;
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });
                }
            });
        });

        $('#ccTo, #ccFor, #crmTo, #crmFor, #adTo, #adFor, #dirTo, #dirFor, #sdTo, #sdFor, #cdTo, #cdFor').addClass(
            'small-text');
    </script>
@endpush
