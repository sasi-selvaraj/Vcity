@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .card .plot-status {
            /* width: 200px; */
            height: 200px;
            border-radius: 0%;
        }

        .card .plot-details {
            height: 300px;
            border-radius: 0%;
        }

        .table-bordered {
            border-color: #bad4e0;
        }

        .font-weight {
            font-weight: 600;
        }

        .table tbody tr:last-child td {
            border-width: 0 1px !important;
        }
    </style>
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Plot Details'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title justify-content-start">{{ $plot->project->project_name }}</h4>
                        <div class="justify-content-end">
                            <a href="javascript:void(0)" onclick="window.history.back();"
                                class="btn btn-primary btn-round text-white">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div
                                class="card plot-status mb-4 @if ($plot->status == 'Available') bg-gradient-success @elseif ($plot->status == 'Hold') bg-gradient-danger @elseif ($plot->status == 'Temporary Booking') bg-gradient-green @elseif ($plot->status == 'Booking') bg-gradient-primary @elseif ($plot->status == 'Full Payment') bg-gradient-warning @elseif ($plot->status == 'Registered') bg-gradient-info @endif">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                    <div>
                                        <h4 class="text-center">{{ $plot->plot_no }}</h4><br>
                                        <h5 class="text-center">{{ $plot->status }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card plot-status mb-4 bg-mute">
                                <div class="p-4">
                                    <h6>Plot Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="font-weight">Total Sqft: </span>&nbsp;
                                            <span>{{ $plot->plot_sqft ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="font-weight">Facing: </span>&nbsp;
                                            <span>{{ $plot->facing ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="font-weight">Plot No.: </span>&nbsp;
                                            <span>{{ $plot->plot_no ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="font-weight">Marketer: </span>&nbsp;
                                            <span>{{ $plot->payments->isNotEmpty() && $plot->payments->first()->marketers ? $plot->payments->first()->marketers->name : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="font-weight">Block </span>&nbsp;
                                            <span>{{ $plot->block ?? 'N/A'}}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="font-weight">Location: </span>&nbsp;
                                            <span>{{ $plot->location ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card plot-details mb-4 bg-mute">
                                <div class="p-4">
                                    <h6>Payment Details</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="font-weight">GLV Rate: </span>&nbsp;
                                            <span>Rs. {{ $plot->glv_rate ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="font-weight">GLV: </span>&nbsp;
                                            <span>Rs. {{ $plot->glv ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="font-weight">Dev. Rate: </span>&nbsp;
                                            <span>Rs. {{ $plot->dev_rate ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="font-weight">Development Charges: </span>&nbsp;
                                            <span>Rs. {{ $plot->development_charges ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="font-weight">Total Amount: </span>&nbsp;
                                            <span>Rs. {{ $plot->total_amount ?? 'N/A' }}</span>
                                        </div>
                                        @php
                                            $paymentAmount = 0;
                                        @endphp
                                        @foreach ($plot->payments as $payment)
                                            @php
                                                $paymentAmount = $payment->sum('amount_paid');
                                            @endphp
                                        @endforeach
                                        <div class="col-md-12">
                                            <span class="font-weight">Paid Amount: </span>&nbsp;
                                            <span>Rs. {{ $plot->paid_amount ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="font-weight">Balance Amount: </span>&nbsp;
                                            <span>Rs. {{ $plot->balance_amount ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card plot-details mb-4 bg-mute">
                                <div class="p-4">
                                    <h6>Receipt</h6>
                                    <table class="table table-bordered table-responsive table-striped">
                                        <tr>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>PDF</th>
                                        </tr>
                                        @if ($plot->payments->isNotEmpty())
                                            @foreach ($plot->payments as $payment)
                                                @if ($payment->payment_status == config('constants.payment_status.temporary_booking'))
                                                    <tr>
                                                        <td>Temporary Booking</td>
                                                        <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                        <td>{{ $payment->amount_paid ?? 'N/A' }}</td>
                                                        <td><a href="{{ url('/payments/download_receipt/' . $payment->id . '/' . $payment->payment_status) }}"
                                                                class="text-info btn-sm"><i
                                                                    class='fa fa-file-pdf-o'></i></a></td>
                                                    </tr>
                                                @elseif ($payment->payment_status == config('constants.payment_status.booking'))
                                                    <tr>
                                                        <td>Booking</td>
                                                        <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                        <td>{{ $payment->amount_paid ?? 'N/A' }}</td>
                                                        <td><a href="{{ url('/payments/download_receipt/' . $payment->id . '/' . $payment->payment_status) }}"
                                                                class="text-info btn-sm"><i
                                                                    class='fa fa-file-pdf-o'></i></a></td>
                                                    </tr>
                                                @elseif ($payment->payment_status == config('constants.payment_status.full_payment'))
                                                    <tr>
                                                        <td>Full Payment</td>
                                                        <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                        <td>{{ $payment->amount_paid ?? 'N/A' }}</td>
                                                        <td><a href="{{ url('/payments/download_receipt/' . $payment->id . '/' . $payment->payment_status) }}"
                                                                class="text-info btn-sm"><i
                                                                    class='fa fa-file-pdf-o'></i></a></td>
                                                    </tr>
                                                @elseif ($payment->payment_status == config('constants.payment_status.registered'))
                                                    <tr>
                                                        <td>Registered</td>
                                                        <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                        <td>{{ $payment->amount_paid ?? 'N/A' }}</td>
                                                        <td><a href="{{ url('/payments/download_receipt/' . $payment->id . '/' . $payment->payment_status) }}"
                                                                class="text-info btn-sm"><i
                                                                    class='fa fa-file-pdf-o'></i></a></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No payments found</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-secondary mr-3">Close</a>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
@endpush
