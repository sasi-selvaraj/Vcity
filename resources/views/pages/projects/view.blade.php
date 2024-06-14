@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .plot-card {
            width: 130px;
            height: 130px;
            border-radius: 0%;
            margin: 1rem;
        }

        .card-body.plot-view {
            padding: 0.5rem !important;
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
                        <h4 class="card-title justify-content-start">{{ $project->project_name }}</h4>
                        <div class="justify-content-end">
                            <a href="{{ route('projects.index') }}" class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="viewProjectForm">
                        <input type="hidden" id="view_project_id" value="{{ $project->id }}">
                        <div class="row">
                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="view_project_name">Project Name: &nbsp;</label>
                                    <p class="bg-white">{{ $project->project_name }}</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="view_project_location">Project Location: &nbsp;</label>
                                    <p class="bg-white">{{ $project->project_location }}</p>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="total_blocks">Total blocks: &nbsp;</label>
                                    <p class="bg-white">{{ $project->total_blocks }}</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="view_total_no_of_sqft">Total area in acres: &nbsp;</label>
                                    <p class="bg-white">{{ $project->total_no_of_sqft }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="view_total_plots">Total No.of Plots: &nbsp;</label>
                                    <p class="bg-white">{{ $project->total_plots }}</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6 pe-none">
                                <div class="d-flex">
                                    <label for="view_project_description">Project Description: &nbsp;</label>
                                    <p class="bg-white">{{ $project->project_description }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="image_div">
                                <label for="project_image">Project Image:</label> <br>
                                <img src="{{asset('storage/' . $project->projectImage[0]->path)}}" alt="Project Image" class="w-25">
                            </div>
                        </div>
                    </form>
                </div>
                {{-- ------------------------------- --}}
                <div class="card-header">
                    <h4 class="card-title justify-content-start">Plots</h4>
                </div>
                <div class="card-body">
                    @php
                        $array = [
                            'bg-secondary',
                            'bg-success',
                            'bg-danger',
                            'bg-warning',
                            'bg-info',
                            'bg-dark',
                            'bg-primary',
                        ];
                        $array1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
                        $k = 0;
                    @endphp
                    <div class="row bg-light shadow plots">
                        @if (isset($project) && !empty($project))
                            @php
                                $plotsExist = false;
                            @endphp
                            @foreach ($project->plots as $key => $val)
                                @if (isset($val) && !empty($val))
                                    @php
                                        $status = $val->status;
                                        switch ($status) {
                                            case 'Available':
                                                $bgcolor = 'bg-gradient-success';
                                                break;
                                            case 'Registered':
                                                $bgcolor = 'bg-gradient-info';
                                                break;
                                            case 'Full Payment':
                                                $bgcolor = 'bg-gradient-warning';
                                                break;
                                            case 'Temporary Booking':
                                                $bgcolor = 'bg-gradient-green';
                                                break;
                                            case 'Hold':
                                                $bgcolor = 'bg-gradient-danger';
                                                break;
                                            case 'Permanent Hold':
                                                $bgcolor = 'bg-gradient-purple';
                                                break;
                                            case 'Booking':
                                                $bgcolor = 'bg-gradient-primary';
                                                break;
                                            default:
                                                $bgcolor = 'bg-dark';
                                                break;
                                        }
                                    @endphp
                                    <div class="col-2">
                                        <a href="{{ url('plots/' . $val->id) }}" class="text-center view-plot">
                                            <div class="card plot-card fw-bold text-dark {{ $bgcolor }} mb-3 mx-2">
                                                <div class="card-body plot-view">
                                                    <h6 class="card-title font-weight-bolder" style="font-size: 15px;">{{ strlen($val->project->project_name) > 5 ? substr($val->project->project_name, 0, 5) . '...' : $val->project->project_name }}
                                                    </h6>
                                                    <h5 class="fw-bold card-title text-center mt-1"> {{ $val->plot_no }}
                                                    </h5>
                                                    <h7 class="fw-bold card-title text-center mt-1">{{ $val->status }}
                                                    </h7>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @php
                                        $plotsExist = true;
                                    @endphp
                                @endif
                            @endforeach
                            @if (!$plotsExist)
                                <div class="col-12 p-4">
                                    <h5><i>No Plots Available for this Project...</i></h5>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary mr-3">Close</a>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
@endpush
