@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Marketers'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">View Marketers</h4>
                        <div class="justify-content-end">
                            <a href="{{ route('marketers.index') }}" class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- View Marketer -->
                    <form id="viewMarketerForm">
                        @csrf
                        <input type="hidden" id="view_Marketer_id" value="{{ $marketer->id }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_vcity_marketer_id">Marketer ID: &nbsp;</label>
                                    <p class="bg-white" id="view_vcity_marketer_id">
                                        {{ $marketer->marketer_vcity_id ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_marketer_name">Marketer Name: &nbsp;</label>
                                    <p class="bg-white" id="view_marketer_name">
                                        {{ $marketer->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_date">Date: &nbsp;</label>
                                    <p class="bg-white" id="view_date">
                                        {{ $marketer->date ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_designation">Designation: &nbsp;</label>
                                    <p class="bg-white" id="view_designation">
                                        @if (isset($marketer->designation))
                                            @switch($marketer->designation)
                                                @case('CC')
                                                    Customer Co-ordinator (CC)
                                                @break

                                                @case('CRM')
                                                    Customer Relationship Manager (CRM)
                                                @break

                                                @case('AD')
                                                    Associate Director
                                                @break

                                                @case('Dir')
                                                    Director
                                                @break

                                                @case('SD')
                                                    Senior Director
                                                @break

                                                @case('CD')
                                                    Chief Director
                                                @break

                                                @default
                                                    N/A
                                            @endswitch
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_email">Email Address: &nbsp;</label>
                                    <p class="bg-white" id="view_email">
                                        {{ $marketer->email ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_father_name">Father's Name: &nbsp;</label>
                                    <p class="bg-white" id="view_father_name">
                                        {{ $marketer->father_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_dob">Date Of Birth: &nbsp;</label>
                                    <p class="bg-white" id="view_dob">
                                        {{ $marketer->dob ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_qualification">Educational Qualification: &nbsp;</label>
                                    <p class="bg-white" id="view_qualification">
                                        {{ $marketer->qualification ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_aadhar_no">Aadhar Number: &nbsp;</label>
                                    <p class="bg-white" id="view_aadhar_no">
                                        {{ $marketer->aadhar_no ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_pan_no">PAN Number: &nbsp;</label>
                                    <p class="bg-white" id="view_pan_no">
                                        {{ $marketer->pan_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_acc_no">Account No.: &nbsp;</label>
                                    <p class="bg-white" id="view_acc_no">
                                        {{ $marketer->acc_no ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_ifsc_code">IFSC Code: &nbsp;</label>
                                    <p class="bg-white" id="view_ifsc_code">
                                        {{ $marketer->ifsc_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_branch">Branch: &nbsp;</label>
                                    <p class="bg-white" id="view_branch">
                                        {{ $marketer->branch ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_mobile_no">Mobile Number: &nbsp;</label>
                                    <p class="bg-white" id="view_mobile_no">
                                        {{ $marketer->mobile_no ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_pincode">Pincode: &nbsp;</label>
                                    <p class="bg-white" id="view_pincode">
                                        {{ $marketer->pincode ?? 'N/A' }}</p>
                                </div>
                                <div class="d-flex col-md-6">
                                    <label for="view_city">City: &nbsp;</label>
                                    <p class="bg-white" id="view_city">
                                        {{ $marketer->city ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex col-md-6">
                                    <label for="view_address">Complete Address: &nbsp;</label>
                                    <p class="bg-white" id="view_address">
                                        {{ $marketer->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="col-md-4 d-flex align-items-center">
                                            <label for="edit_renewal_date">Last Payment:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="text-primary"
                                                id="edit_renewal_date">{{ $marketer->days_ago ? $marketer->days_ago : 'N/A' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="col-md-4 d-flex align-items-center">
                                            <label for="edit_renewal_date">Renewal Date:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="text-primary"
                                                id="edit_renewal_date">{{ $marketer->renewal_date ? date('d-m-Y', strtotime($marketer->renewal_date)) : 'N/A' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <h5 class="mt-4">Director Details :</h5>
                                <div class="row border m-1 pt-3">
                                    <div class="d-flex col-md-6">
                                        <label for="view_director">Director: &nbsp;</label>
                                        <p class="bg-white" id="view_director">
                                            {{ $marketer->Director->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="d-flex col-md-6">
                                        <label for="view_director_id">Director ID: &nbsp;</label>
                                        <p class="bg-white" id="view_director_id">
                                            {{ $marketer->Director->marketer_vcity_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row border m-1 pt-3">
                                    <div class="d-flex col-md-6">
                                        <label for="view_ad">Associate Director: &nbsp;</label>
                                        <p class="bg-white" id="view_ad">
                                            {{ $marketer->assistantDirector->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="d-flex col-md-6">
                                        <label for="view_ad_id">Associate Director ID: &nbsp;</label>
                                        <p class="bg-white" id="view_ad_id">
                                            {{ $marketer->assistantDirector->marketer_vcity_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row border m-1 pt-3">
                                    <div class="d-flex col-md-6">
                                        <label for="view_crm">Customer Relationship Manager(CRM): &nbsp;</label>
                                        <p class="bg-white" id="view_crm">
                                            {{ $marketer->CRM->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="d-flex col-md-6">
                                        <label for="view_crm_vcity_id">Customer Relationship Manager(CRM) ID:
                                            &nbsp;</label>
                                        <p class="bg-white" id="view_crm_id">
                                            {{ $marketer->CRM->marketer_vcity_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row border m-1 pt-3">
                                    <div class="d-flex col-md-6">
                                        <label for="view_senior_director">Senior Director: &nbsp;</label>
                                        <p class="bg-white" id="view_senior_director">
                                            {{ $marketer->seniorDirector->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="d-flex col-md-6">
                                        <label for="view_senior_id">Senior Director ID: &nbsp;</label>
                                        <p class="bg-white" id="view_senior_director_id">
                                            {{ $marketer->seniorDirector->marketer_vcity_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row border m-1 pt-3">
                                    <div class="d-flex col-md-6">
                                        <label for="view_chief_director">Chief Director: &nbsp;</label>
                                        <p class="bg-white" id="view_chief_director">
                                            {{ $marketer->chiefDirector->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="d-flex col-md-6">
                                        <label for="view_chief_id">Chief Director ID: &nbsp;</label>
                                        <p class="bg-white" id="view_chief_director_id">
                                            {{ $marketer->chiefDirector->marketer_vcity_id ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('marketers.index') }}" class="btn btn-secondary">Close</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- viewFileModal --}}
    {{-- <div class="modal fade" id="viewFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                <input type="hidden" name="id" id="id" value="">
                <div class="modal-body viewFile-modal">

                </div>
                <div class="modal-footer px-0 pt-3 pb-0 my-3 mx-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
