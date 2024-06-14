@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Payment'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">Payment</h4>
                        <div class="justify-content-end">
                            {{-- <button type="button" class="btn btn-primary btn-round text-white mx-2"
                                onclick="showImportPaymentsModal()">Import Payments</button> --}}
                            <button type="button" class="btn btn-primary btn-round text-white"
                                onclick="showAddPaymentsModal()">Add Payment</button>
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

    <!-- Add Payments Modal -->
    <div class="modal fade" id="addPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addPaymentsModalLabel">Add Payments</h4>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true"></i>
                </div>
                <form id="addPaymentsForm">
                    @csrf
                    <div class="modal-body">
                        <fieldset class="mb-2">
                            <h5>Project Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="project">Project<span class="mandatory_fields">*</span></label>
                                    <select name="project_id" id="project" class="form-select">
                                        <option value="">Select Project</option>
                                        @isset($projects)
                                            @foreach ($projects as $v)
                                                <option value="{{ $v->id }}">{{ $v->project_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="plot_no">Plot No<span class="mandatory_fields">*</span></label>
                                    <select name="plot_id" id="plot_no" class="form-select">
                                        <option value="">Select Plot No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="marketer">Marketer<span class="mandatory_fields">*</span></label>
                                    <select name="marketer_id" id="marketer" class="select2">
                                        <option value="">Select Marketer</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="reference_id">Reference ID</label>
                                    <select name="reference_id" id="reference_id" class="select2">
                                        <option value="">Select Reference Id</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $v)
                                                <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="total_amount">Total Amount</label>
                                    <input type="text" class="form-control bg-white" id="total_amount"
                                        name="total_amount" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="amount_paid">Amount Paid:<span class="mandatory_fields">*</span></label>
                                    <input type="number" id="amount_paid" name="amount_paid" class="form-control"
                                        min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="balance_amount">Balance Amount:</label>
                                    <input type="number" id="balance_amount" name="balance_amount"
                                        class="form-control bg-white" readonly>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4 pt-3 align-items-end">
                                    <div class="d-flex align-items-end">
                                        <label for="glv" style="font-weight: 600;">GLV: &nbsp;&nbsp;</label>
                                        <input type="hidden" id="glvInput">
                                        <p style="line-height: 0.6;" id="glv"></p>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <label for="development_charges" style="font-weight: 600;">Dev. Charges:
                                            &nbsp;&nbsp;</label>
                                        <input type="hidden" id="devInput">
                                        <p style="line-height: 0.6;" id="development_charges"></p>
                                    </div>
                                </div>
                                <div class="col-md-3 pt-3 align-items-end">
                                    <div class="d-flex align-items-end">
                                        <label for="facing" style="font-weight: 600;">Facing: &nbsp;&nbsp;</label>
                                        <p style="line-height: 0.6;" id="facing"></p>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <label for="block" style="font-weight: 600;">Block: &nbsp;&nbsp;</label>
                                        <p style="line-height: 0.6;" id="block"></p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Customer Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="customer_name">Customer Name<span
                                            class="mandatory_fields">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="father_or_husband_name">Father/Husband Name</label>
                                    <input type="text" class="form-control" id="father_or_husband_name"
                                        name="father_or_husband_name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mobile_no">Mobile Number</label>
                                    <input type="number" class="form-control" id="mobile_no" name="mobile_no"
                                        min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="whatsapp_no">Whatsapp Number</label>
                                    <input type="number" class="form-control" id="whatsapp_no" name="whatsapp_no"
                                        min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="1"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Payment Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="payment_type">Payment Type:</label>
                                    <select id="payment_type" name="payment_type" class="form-select">
                                        <option value="">Select Payment</option>
                                        <option value="Cash">Cash</option>
                                        <option value="IMPS">IMPS</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    @php
                                        $maxDate = \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp
                                    <label for="payment_date">Payment Date: &nbsp;&nbsp;<span id="booking_date"
                                            class="d-none">Booking Date</span></label>
                                    <input type="date" id="payment_date" name="payment_date" class="form-control"
                                        max="{{ $maxDate }}" value="{{ $maxDate }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="payment_status">Payment Status:<span
                                            class="mandatory_fields">*</span></label>
                                    <select id="payment_status" name="payment_status" class="form-select">
                                        <option value="">Select Payment</option>
                                        <option value="{{ config('constants.payment_status.temporary_booking') }}">
                                            Development Charges
                                        </option>
                                        <option value="{{ config('constants.payment_status.booking') }}">Plot Amount
                                        </option>
                                        <option value="{{ config('constants.payment_status.full_payment') }}">Full Payment
                                        </option>
                                        <option value="{{ config('constants.payment_status.registered') }}">Registered
                                        </option>
                                        <option value="{{ config('constants.payment_status.cancelled') }}">Cancelled
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="particulars">Particulars</label>
                                    <input type="text" class="form-control" id="particulars" name="particulars">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="payment_details">Payment Details:</label>
                                    <textarea name="payment_details" id="payment_details" class="form-control" rows="1"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Bank Details:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="bank">Bank:</label>
                                    <input type="text" id="bank" name="bank" class="form-control"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="branch">Branch:</label>
                                    <input type="text" id="branch" name="branch" class="form-control"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ref_no">Ref. no.:</label>
                                    <input type="text" id="ref_no" name="ref_no" class="form-control"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cheque_number">Cheque Number:</label>
                                    <input type="text" id="cheque_number" name="cheque_number" class="form-control"
                                        autocomplete="off">
                                </div>
                                @php
                                    $maxDate = \Carbon\Carbon::now()->addYears(2)->format('Y-m-d');
                                    $minDate = \Carbon\Carbon::now()->subYears(2)->format('Y-m-d');
                                @endphp
                                <div class="form-group col-md-4">
                                    <label for="cheque_date">Cheque Date:</label>
                                    <input type="date" id="cheque_date" name="cheque_date" class="form-control"
                                        min="{{ $minDate }}" max="{{ $maxDate }}">
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                            aria-hidden="true">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentsModal" tabindex="-1" role="dialog"
        aria-labelledby="editPaymentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentsModalLabel">Edit Payments</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close" aria-hidden="true"></i>
                </div>
                <form id="editPaymentsForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_payment_id">
                        <fieldset class="mb-2">
                            <h5>Project Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="edit_project">Project<span class="mandatory_fields">*</span></label>
                                    <select name="project_id" id="edit_project" class="form-select bg-white">
                                        <option value="">Select Project</option>
                                        @isset($projects)
                                            @foreach ($projects as $v)
                                                <option value="{{ $v->id }}">{{ $v->project_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <input type="hidden" id="edit_plot_value" name="edit_plot_value">
                                <div class="form-group col-md-4">
                                    <label for="edit_plot_no">Plot No<span class="mandatory_fields">*</span></label>
                                    <select name="plot_id" id="edit_plot_no" class="form-select bg-white">
                                        <option id="edit_plot_no_option" value="">Select Plot No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_marketer">Marketer<span class="mandatory_fields">*</span></label>
                                    <select name="marketer_id" id="edit_marketer" class="select2 bg-white">
                                        <option value="">Select Marketer</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_reference_id">Reference ID</label>
                                    <select name="reference_id" id="edit_reference_id" class="select2 bg-white">
                                        <option value="">Select Reference Id</option>
                                        @isset($marketers)
                                            @foreach ($marketers as $v)
                                                <option value="{{ $v->id }}">{{ $v->marketer_vcity_id }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_total_amount">Total Amount</label>
                                    <input type="text" class="form-control bg-white" id="edit_total_amount"
                                        name="total_amount" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_amount_paid">Amount Paid:<span
                                            class="mandatory_fields">*</span></label>
                                    <input type="number" id="edit_amount_paid" name="amount_paid"
                                        class="form-control bg-white" min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_balance_amount">Balance Amount:</label>
                                    <input type="number" id="edit_balance_amount" name="balance_amount"
                                        class="form-control bg-white pe-none" readonly>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4 pt-3 align-items-end">
                                    <div class="d-flex align-items-end">
                                        <label for="edit_glv" style="font-weight: 600;">GLV: &nbsp;&nbsp;</label>
                                        <input type="hidden" id="edit_glvInput">
                                        <p style="line-height: 0.6;" id="edit_glv"></p>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <label for="edit_development_charges" style="font-weight: 600;">Dev. Charges:
                                            &nbsp;&nbsp;</label>
                                        <input type="hidden" id="edit_devInput">
                                        <p style="line-height: 0.6;" id="edit_development_charges"></p>
                                    </div>
                                </div>
                                <div class="col-md-3 pt-3 align-items-end">
                                    <div class="d-flex align-items-end">
                                        <label for="edit_facing" style="font-weight: 600;">Facing: &nbsp;&nbsp;</label>
                                        <p style="line-height: 0.6;" id="edit_facing"></p>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <label for="edit_block" style="font-weight: 600;">Block: &nbsp;&nbsp;</label>
                                        <p style="line-height: 0.6;" id="edit_block"></p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Customer Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="edit_customer_name">Customer Name<span
                                            class="mandatory_fields">*</span></label>
                                    <input type="text" class="form-control bg-white" id="edit_customer_name"
                                        name="customer_name" autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_father_or_husband_name">Father/Husband Name</label>
                                    <input type="text" class="form-control bg-white" id="edit_father_or_husband_name"
                                        name="father_or_husband_name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_mobile_no">Mobile Number</label>
                                    <input type="number" class="form-control bg-white" id="edit_mobile_no"
                                        name="mobile_no" min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_whatsapp_no">Whatsapp Number</label>
                                    <input type="number" class="form-control bg-white" id="edit_whatsapp_no"
                                        name="whatsapp_no" min="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_address">Address</label>
                                    <textarea class="form-control bg-white" id="edit_address" name="address" rows="1"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Payment Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="edit_payment_type">Payment Type:</label>
                                    <select id="edit_payment_type" name="payment_type" class="form-select bg-white">
                                        <option value="">Select Payment</option>
                                        <option value="Cash">Cash</option>
                                        <option value="IMPS">IMPS</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    @php
                                        $maxDate = \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp
                                    <label for="edit_payment_date">Payment Date: &nbsp;&nbsp;<span id="edit_booking_date"
                                            class="d-none">Booking Date</span></label>
                                    <input type="date" id="edit_payment_date" name="payment_date"
                                        class="form-control bg-white" max="{{ $maxDate }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_payment_status">Payment Status:<span
                                            class="mandatory_fields">*</span></label>
                                    <select id="edit_payment_status" name="payment_status" class="form-select bg-white">
                                        <option value="">Select Payment</option>
                                        <option value="{{ config('constants.payment_status.temporary_booking') }}">
                                            Development Charges
                                        </option>
                                        <option value="{{ config('constants.payment_status.booking') }}">Plot Amount
                                        </option>
                                        <option value="{{ config('constants.payment_status.full_payment') }}">Full Payment
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_particulars">Particulars</label>
                                    <input type="text" class="form-control bg-white" id="edit_particulars"
                                        name="particulars">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_payment_details">Payment Details:</label>
                                    <textarea name="payment_details" id="edit_payment_details" class="form-control bg-white" rows="1"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Bank Details:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group col-md-4">
                                    <label for="edit_bank">Bank:</label>
                                    <input type="text" id="edit_bank" name="bank" class="form-control bg-white"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_branch">Branch:</label>
                                    <input type="text" id="edit_branch" name="branch" class="form-control bg-white"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_ref_no">Ref. no.:</label>
                                    <input type="text" id="edit_ref_no" name="ref_no" class="form-control bg-white"
                                        autocomplete="off">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="edit_cheque_number">Cheque Number:</label>
                                    <input type="text" id="edit_cheque_number" name="cheque_number"
                                        class="form-control bg-white" autocomplete="off">
                                </div>
                                @php
                                    $maxDate = \Carbon\Carbon::now()->addYears(2)->format('Y-m-d');
                                    $minDate = \Carbon\Carbon::now()->subYears(2)->format('Y-m-d');
                                @endphp
                                <div class="form-group col-md-4">
                                    <label for="edit_cheque_date">Cheque Date:</label>
                                    <input type="date" id="edit_cheque_date" name="cheque_date"
                                        class="form-control bg-white" min="{{ $minDate }}"
                                        max="{{ $maxDate }}">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                            aria-hidden="true">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Payment Modal -->
    <div class="modal fade" id="viewPaymentsModal" tabindex="-1" role="dialog"
        aria-labelledby="viewPaymentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPaymentsModalLabel">View Payments</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close" aria-hidden="true"></i>
                </div>
                <div class="modal-body">
                    <form id="viewPaymentsForm">
                        <input type="hidden" id="view_payments_id">
                        <fieldset class="mb-2">
                            <h5>Project Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_project" class="fs-6">Project:</label>
                                    <label class="bg-white text-muted fs-6" id="view_project"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_plot_no" class="fs-6">Plot No:</label>
                                    <label class="bg-white text-muted fs-6" id="view_plot_no"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_marketer" class="fs-6">Marketer:</label>
                                    <label class="bg-white text-muted fs-6" id="view_marketer"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_reference_id" class="fs-6">Reference ID:</label>
                                    <label class="bg-white text-muted fs-6" id="view_reference_id"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_total_amount">Total Amount:</label>
                                    <label id="view_total_amount" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_amount_paid">Amount Paid:</label>
                                    <label id="view_amount_paid" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_balance_amount">Balance Amount:</label>
                                    <label id="view_balance_amount" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_glv">GLV:</label>
                                    <label id="view_glv" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_development_charges">Development Charges:</label>
                                    <label id="view_development_charges" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_facing">Facing:</label>
                                    <label id="view_facing" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_block">Block:</label>
                                    <label id="view_block" class="bg-white text-muted"></label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Customer Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_customer_name" class="fs-6">Customer Name:</label>
                                    <label class="bg-white text-muted fs-6" id="view_customer_name"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_father_or_husband_name" class="fs-6">Father/Husband Name:</label>
                                    <label class="bg-white text-muted fs-6" id="view_father_or_husband_name"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_mobile_no" class="fs-6">Mobile Number:</label>
                                    <label class="bg-white text-muted fs-6" id="view_mobile_no"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_whatsapp_no" class="fs-6">Whatsapp Number:</label>
                                    <label class="bg-white text-muted fs-6" id="view_whatsapp_no"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_address" class="fs-6">Address:</label>
                                    <label class="bg-white text-muted fs-6" id="view_address"></label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Payment Information:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_payment_type">Payment Type:</label>
                                    <label id="view_payment_type" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_payment_date">Payment Date:</label>
                                    <label id="view_payment_date" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_payment_status">Payment Status:</label>
                                    <label class="bg-white text-muted fs-6" id="view_payment_status"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_particulars">Particulars:</label>
                                    <label id="view_particulars" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_payment_details">Payment Details:</label>
                                    <label id="view_payment_details" class="bg-white text-muted"></label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-2">
                            <h5>Bank Details:</h5>
                            <div class="row border m-1 pt-3">
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_bank">Bank:</label>
                                    <label id="view_bank" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_branch">Branch:</label>
                                    <label id="view_branch" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_ref_no">Ref. no.:</label>
                                    <label id="view_ref_no" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_cheque_number">Cheque Number:</label>
                                    <label id="view_cheque_number" class="bg-white text-muted"></label>
                                </div>
                                <div class="form-group pe-none col-md-4">
                                    <label for="view_cheque_date">Cheque Date:</label>
                                    <label id="view_cheque_date" class="bg-white text-muted"></label>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deletePaymentsModal" tabindex="-1" role="dialog"
        aria-labelledby="deletePaymentsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePaymentsModalLabel">Delete Payment</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close" aria-hidden="true"></i>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this payment?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Cancel</button>
                    <button type="button" class="btn btn-primary" id="deletePayments"
                        onclick="deletePayments()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Popup --}}
    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Do you want to print receipt for this payment?</h5>
                    <i class="fa fa-times fa-2xl" type="button" class="close" data-bs-dismiss="modal"
                        aria-label="Close" aria-hidden="true"></i>
                </div>
                <div class="modal-body" id="receipt-print">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Cancel</button>
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
        function showAddPaymentsModal() {
            $('#addPaymentsModal').modal('show');
            $(".select2").select2({
                dropdownParent: $('#addPaymentsModal'),
                width: '100%',
            });
        }

        // Payments form validation
        $(document).ready(function() {
            $('#addPaymentsForm').validate({
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 3,
                    },
                    mobile_no: {
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    whatsapp_no: {
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    project_id: {
                        required: true,
                    },
                    plot_id: {
                        required: true,
                    },
                    marketer_id: {
                        required: true,
                    },
                    payment_date: {
                        date: true
                    },
                    amount_paid: {
                        required: true,
                        number: true,
                        max: 9999999999,
                    },
                    payment_status: {
                        required: true
                    },
                    cheque_date: {
                        date: true
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
                    // if ((parseFloat($('#amount_paid').val()) > parseFloat($('#devInput').val())) && ($('#payment_status').val() == 1)) {
                    //     alertNotify("Notification", "error", "Enter Valid Development Charges");
                    // } else {
                    // }
                    addPayments();
                }
            });
        });


        // ADD Payments
        function addPayments() {
            var formData = $('#addPaymentsForm').serialize();
            var route_url = '/payments';
            var method = 'POST';
            ajaxResponse(route_url, method, formData, function(data) {
                if (data.success == 1) {
                    $('#receiptModal').modal('show');
                    var receiptUrl = "{{ url('/payments/download_receipt/') }}/" + data.data.id + "/" + data.data
                        .payment_status;
                    var downloadButton = "<div class='d-flex justify-content-center'><a href='" + receiptUrl +
                        "' class='btn btn-info'>Preview and Download</a></div>";
                    $('#receipt-print').html(downloadButton);
                } else {
                    alertNotify("Notification", "error", data.message);
                }
            });
        }

        // Plot list
        $(document).on('change', '#project', function() {
            $("#balance_amount, #total_amount, #payments_id, #customer_name, #father_or_husband_name, #mobile_no, #whatsapp_no, #address, #bank, #branch, #ref_no")
                .val('');
            $('#marketer').val('').trigger('change');
            $("#facing").text('');
            $("#block").text('');
            $("#development_charges").text('');
            $("#devInput").val('');
            $("#glv").text('');
            $("#glvInput").val('');
            var route_url = '/payments/selectPlots/' + $(this).val();
            var method = 'GET';
            var data = null;
            var render = "plot_no";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                var plots = '<option value="">Select Plot No</option>';
                $.each(response, function(i, v) {
                    plots += `<option value=` + v.id + `>` + v.plot_no + `</option>`;
                })
                $("#" + render).html(plots);
                $("#" + render).trigger('change');
            });
        });
        $(document).ready(function() {
            $(document).on('change', '#plot_no', function() {
                var route_url = '/payments/getdata/' + $(this).val();
                var method = 'GET';
                var data = null;
                var render = "balance_amount"
                ajaxResponseRender(route_url, method, data, render, function(response) {
                    $("#" + render).val(response.plot_amount.balance_amount);
                    $('#amount_paid, #customer_name, #father_or_husband_name, #mobile_no, #whatsapp_no, #address, #bank, #branch, #ref_no')
                        .val('');
                    $('#marketer').val('').trigger('change');
                    $("#facing").text('');
                    $("#block").text('');
                    $("#development_charges").text('');
                    $("#devInput").val('');
                    $("#glv").text('');
                    $("#glvInput").val('');
                    if ($("#balance_amount").val() == 0) {
                        $('#amount_paid').val(0);
                    }
                    $("#total_amount").val(response.plot_amount.total_amount);
                    var oldBalance = response.plot_amount.balance_amount;
                    $('#amount_paid').off('input').on('input', function() {
                        var amountPaid = parseFloat($(this).val());
                        if (!isNaN(amountPaid)) {
                            var newBalance = oldBalance - amountPaid;
                            $("#" + render).val(newBalance.toFixed(2));
                        } else {
                            $("#" + render).val(oldBalance);
                        }
                    });
                    $("#facing").text(response.plot_amount.facing);
                    $("#block").text(response.plot_amount.block);
                    $("#development_charges").text(response.plot_amount.development_charges);
                    $("#devInput").val(response.plot_amount.development_charges);
                    $("#glv").text(response.plot_amount.glv);
                    $("#glvInput").val(response.plot_amount.glv);
                    if (response.paymentData && Object.keys(response.paymentData)
                        .length > 0) {
                        $("#payments_id").val(response.paymentData.id);
                        $("#customer_name").val(response.paymentData.customer_name);
                        $("#father_or_husband_name").val(response.paymentData
                            .father_or_husband_name);
                        $("#mobile_no").val(response.paymentData.mobile_no);
                        $("#whatsapp_no").val(response.paymentData.whatsapp_no);
                        $("#address").val(response.paymentData.address);
                        $("#marketer").val(response.paymentData.marketer_id).trigger('change');
                        $("#reference_id").val(response.paymentData.marketer_id).trigger('change');
                        $("#bank").val(response.paymentData.bank);
                        $("#branch").val(response.paymentData.branch);
                        $("#ref_no").val(response.paymentData.ref_no);
                    }
                });
            });
            var trigger_status = 0;
            $('#marketer, #reference_id').change(function() {
                if (trigger_status == 0) {
                    var selectedValue = $(this).val();
                    var selectedField = $(this).attr('id');
                    // alert(selectedValue);

                    if (selectedField == 'marketer') {
                        trigger_status = 1;
                        $('#reference_id').val(selectedValue).trigger("change");
                    } else if (selectedField == 'reference_id') {
                        trigger_status = 1;
                        $('#marketer').val(selectedValue).trigger("change");
                    }
                } else {
                    trigger_status = 0;
                }
            });

            $('#payment_status').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue == '5') {
                    $('#amount_paid').val(0);
                }
            });

            // Select2 validation hides
            $("select").on("select2:close", function(e) {
                $(this).valid();
            });
        });

        // Edit Payments Data
        function editData(PaymentsId) {
            var route_url = '/payments/' + PaymentsId + '/edit';
            var method = 'GET';
            var data = null;
            var render = "editPaymentsModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#edit_payment_id").val(response.payment.id);
                $("#edit_customer_name").val(response.payment.customer_name);
                $("#edit_father_or_husband_name").val(response.payment.father_or_husband_name);
                $("#edit_mobile_no").val(response.payment.mobile_no);
                $("#edit_whatsapp_no").val(response.payment.whatsapp_no);
                $("#edit_address").val(response.payment.address);
                $("#edit_reference_id").val(response.payment.reference_id);
                $("#edit_project").val(response.payment.project_id);
                $("#edit_facing").text(response.payment.plots.facing);
                $("#edit_block").text(response.payment.plots.block);
                $("#edit_development_charges").text(response.payment.plots.development_charges);
                $("#edit_devInput").val(response.payment.plots.development_charges);
                $("#edit_glv").text(response.payment.plots.glv);
                $("#edit_glvInput").val(response.payment.plots.glv);
                $.ajax({
                    url: '/payments/plots/' + response.payment.project_id,
                    method: 'GET',
                    data: {
                        project_id: response.payment.project_id
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
                            if (plot.id == response.payment.plot_id) {
                                option.prop('selected', true);
                            }
                            editPlotDropdown.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
                $("#edit_total_amount").val(response.payment.plots.total_amount);
                $("#edit_amount_paid").val(response.payment.amount_paid);
                $("#edit_balance_amount").val(response.payment.plots.balance_amount);
                var oldBalance = parseFloat(response.payment.plots.balance_amount);
                var oldAmountPaid = parseFloat(response.payment.amount_paid);
                $('#edit_amount_paid').off('input').on('input', function() {
                    var amountPaid = parseFloat($(this).val());
                    if (!isNaN(amountPaid)) {
                        var newAmountPaid = oldAmountPaid - amountPaid;
                        var newBalance = oldBalance + newAmountPaid;
                        $("#edit_balance_amount").val(newBalance.toFixed(2));
                    } else {
                        $("#edit_balance_amount").val((oldBalance + oldAmountPaid).toFixed(2));
                    }
                });
                $("#edit_marketer").val(response.payment.marketer_id);
                $("#edit_payment_date").val(response.payment.payment_date);
                $("#edit_payment_status").val(response.payment.payment_status);
                $("#edit_payment_details").val(response.payment.payment_details);
                $("#edit_particulars").val(response.payment.particulars);
                $("#edit_payment_type").val(response.payment.payment_type);
                $("#edit_cheque_number").val(response.payment.cheque_number);
                $("#edit_cheque_date").val(response.payment.cheque_date);
                $("#edit_bank").val(response.payment.bank);
                $("#edit_branch").val(response.payment.branch);
                $("#edit_ref_no").val(response.payment.ref_no);

                // Check if payment_status is 4, then disable the edit_amount_paid field
                if (response.disableAmountPaid) {
                    $("#edit_project").addClass("pe-none");
                    $("#edit_plot_no").addClass("pe-none");
                    $("#edit_marketer").prop("disabled", true);
                    $("#edit_reference_id").prop("disabled", true);
                    $("#edit_payment_status").addClass("pe-none");
                    $("#edit_amount_paid").addClass("pe-none");
                } else {
                    $("#edit_project").removeClass("pe-none");
                    $("#edit_plot_no").removeClass("pe-none");
                    $("#edit_marketer").prop("disabled", false);
                    $("#edit_reference_id").prop("disabled", false);
                    $("#edit_payment_status").removeClass("pe-none");
                    $("#edit_amount_paid").removeClass("pe-none");
                }
                if (response.payment.payment_status == 1) {
                    $('#edit_booking_date').removeClass("d-none");
                } else {
                    $('#edit_booking_date').addClass("d-none");
                }

                $("#" + render).modal("show");

                $(".select2").select2({
                    dropdownParent: $("#" + render),
                    width: '100%',
                });
            });
        }


        // Payments edit form validation
        $(document).ready(function() {
            $('#editPaymentsForm').validate({
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 3,
                    },
                    mobile_no: {
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    whatsapp_no: {
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },
                    project_id: {
                        required: true,
                    },
                    plot_id: {
                        required: true,
                    },
                    marketer_id: {
                        required: true,
                    },
                    payment_date: {
                        date: true
                    },
                    amount_paid: {
                        required: true,
                        number: true,
                        max: 9999999999,
                    },
                    payment_status: {
                        required: true
                    },
                    cheque_date: {
                        date: true
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
                    // if ((parseFloat($('#edit_amount_paid').val()) > parseFloat($('#edit_devInput').val())) && ($('#edit_payment_status').val() == 1)) {
                    //     alertNotify("Notification", "error", "Enter Valid Development Charges");
                    // } else {
                    // }
                    updatePayments();
                }
            });
        });

        // Update Payments
        function updatePayments() {
            var paymentId = $('#edit_payment_id').val();
            var formData = $('#editPaymentsForm').serialize();
            var route_url = '/payments/' + paymentId;;
            var method = 'PUT';
            ajaxResponse(route_url, method, formData, function(data) {
                if (data.success == 1) {
                    $('#receiptModal').modal('show');
                    var receiptUrl = "{{ url('/payments/download_receipt/') }}/" + data.data.id + "/" +
                        data.data
                        .payment_status;
                    var downloadButton = "<div class='d-flex justify-content-center'><a href='" +
                        receiptUrl +
                        "' class='btn btn-info'>Preview and Download</a></div>";
                    $('#receipt-print').html(downloadButton);
                } else {
                    alertNotify("Notification", "error", data.message);
                }
            });
        }

        // edit fetch data by project and plot
        $(document).on('change', '#edit_project', function() {
            $("#edit_facing").text('');
            $("#edit_block").text('');
            $("#edit_development_charges").text('');
            $("#edit_devInput").val('');
            $("#edit_glv").text('');
            $("#edit_glvInput").val('');
            var route_url = '/payments/selectPlots/' + $(this).val();
            var method = 'GET';
            var data = null;
            var render = "edit_plot_no";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                var plots = '<option value="">Select Plot No</option>';
                $.each(response, function(i, v) {
                    plots += `<option value=` + v.id + `>` + v.plot_no + `</option>`;
                })
                $("#" + render).html(plots);
                $("#" + render).trigger('change');
            });
        });
        $(document).ready(function() {
            $(document).on('change', '#edit_plot_no', function() {
                var route_url = '/payments/getdata/' + $(this).val();
                var method = 'GET';
                var data = null;
                var render = "edit_balance_amount"
                ajaxResponseRender(route_url, method, data, render, function(response) {
                    $("#" + render).val(response.plot_amount.balance_amount);
                    $("#edit_total_amount").val(response.plot_amount.total_amount);
                    var oldBalance = response.plot_amount.balance_amount;
                    $('#edit_amount_paid').off('input').on('input', function() {
                        var amountPaid = parseFloat($(this).val());
                        if (!isNaN(amountPaid)) {
                            var newBalance = oldBalance - amountPaid;
                            $("#" + render).val(newBalance.toFixed(2));
                        } else {
                            $("#" + render).val(oldBalance);
                        }
                    });
                    $("#edit_facing").text(response.plot_amount.facing);
                    $("#edit_block").text(response.plot_amount.block);
                    $("#edit_development_charges").text(response.plot_amount.development_charges);
                    $("#edit_devInput").val(response.plot_amount.development_charges);
                    $("#edit_glv").text(response.plot_amount.glv);
                    $("#edit_glvInput").val(response.plot_amount.glv);
                    if (response.paymentData && Object.keys(response.paymentData)
                        .length > 0) {
                        $("edit_payments_id").val(response.paymentData.id);
                        $("edit_customer_name").val(response.paymentData.customer_name);
                        $("edit_father_or_husband_name").val(response.paymentData
                            .father_or_husband_name);
                        $("edit_mobile_no").val(response.paymentData.mobile_no);
                        $("edit_whatsapp_no").val(response.paymentData.whatsapp_no);
                        $("edit_address").val(response.paymentData.address);
                        $("edit_marketer").val(response.paymentData.marketer_id);
                        $("edit_reference_id").val(response.paymentData.marketers
                            .marketer_id);
                        $("edit_bank").val(response.paymentData.bank);
                        $("edit_branch").val(response.paymentData.branch);
                        $("edit_ref_no").val(response.paymentData.ref_no);
                    }
                });
            });

            var edit_trigger_status = 0;
            $('#edit_marketer, #edit_reference_id').change(function() {
                if (edit_trigger_status == 0) {
                    var selectedValue = $(this).val();
                    var selectedField = $(this).attr('id');
                    if (selectedField == 'edit_marketer') {
                        edit_trigger_status = 1;
                        $('#edit_reference_id').val(selectedValue).trigger("change");
                    } else if (selectedField == 'reference_id') {
                        edit_trigger_status = 1;
                        $('#edit_marketer').val(selectedValue).trigger("change");
                    }
                } else {
                    edit_trigger_status = 0;
                }
            });

            $('#payment_status').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue == 1) {
                    $('#booking_date').removeClass("d-none");
                } else {
                    $('#booking_date').addClass("d-none");
                }
            });
            $('#edit_payment_status').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue == 1) {
                    $('#edit_booking_date').removeClass("d-none");
                } else {
                    $('#edit_booking_date').addClass("d-none");
                }
            });
        });

        // View Payments Data
        function viewData(PaymentsId) {
            var route_url = '/payments/' + PaymentsId;
            var method = 'GET';
            var data = null;
            var render = "viewPaymentsModal";
            ajaxResponseRender(route_url, method, data, render, function(response) {
                $("#view_payments_id").val(response.payment.id ?? 'N/A');
                $("#view_customer_name").text(response.payment.customer_name ?? 'N/A');
                $("#view_father_or_husband_name").text(response.payment.father_or_husband_name ?? 'N/A');
                $("#view_mobile_no").text(response.payment.mobile_no ?? 'N/A');
                $("#view_whatsapp_no").text(response.payment.whatsapp_no ?? 'N/A');
                $("#view_address").text(response.payment.address ?? 'N/A');
                $("#view_reference_id").text(response.payment.marketers.marketer_vcity_id ?? 'N/A');
                $("#view_project").text(response.payment.projects.project_name ?? 'N/A');
                $("#view_plot_no").text(response.payment.plots.plot_no ?? 'N/A');
                $("#view_total_amount").text(response.payment.plots.total_amount ?? 'N/A');
                $("#view_amount_paid").text(response.payment.amount_paid ?? 'N/A');
                $("#view_balance_amount").text(response.payment.plots.balance_amount ?? 'N/A');
                $("#view_facing").text(response.payment.plots.facing ?? 'N/A');
                $("#view_block").text(response.payment.plots.block ?? 'N/A');
                $("#view_development_charges").text(response.payment.plots.development_charges ?? 'N/A');
                $("#view_glv").text(response.payment.plots.glv ?? 'N/A');
                $("#view_marketer").text(response.payment.marketers.name ?? 'N/A');
                $("#view_payment_date").text(response.payment.payment_date ?? 'N/A');
                $("#view_payment_status").text(response.payment.payment_status == 1 ?
                    'Development Charges' : response
                    .payment.payment_status == 2 ? 'Plot Amount' : response.payment
                    .payment_status == 3 ?
                    'Full Payment' : 'Development Charges');
                $("#view_payment_details").text(response.payment.payment_details ?? 'N/A');
                $("#view_particulars").text(response.payment.particulars ?? 'N/A');
                $("#view_payment_type").text(response.payment.payment_type ?? 'N/A');
                $("#view_cheque_number").text(response.payment.cheque_number ?? 'N/A');
                $("#view_cheque_date").text(response.payment.cheque_date ?? 'N/A');
                $("#view_bank").text(response.payment.bank ?? 'N/A');
                $("#view_branch").text(response.payment.branch ?? 'N/A');
                $("#view_ref_no").text(response.payment.ref_no ?? 'N/A');
                $("#" + render).modal("show");
            });
        }

        // Delete Modal Popup
        function deleteData(PaymentsId) {
            $('#deletePaymentsModal').modal('show');
            $('#deletePayments').data('Payments-id', PaymentsId);
        }

        // Delete User Data
        function deletePayments() {
            var PaymentsId = $('#deletePayments').data('Payments-id');
            var route_url = '/payments/' + PaymentsId;
            var method = 'DELETE';
            var data = null;
            ajaxResponse(route_url, method, data);
        }

        // custom change the text if data is empty in datatable
        $(document).ready(function() {
            $('#payments-table').on('processing.dt', function(e, settings, processing) {
                if (!processing && !$(this).DataTable().data().any()) {
                    $(this).find('.dt-empty').text('No data available');
                }
            });
        });
    </script>
@endpush
