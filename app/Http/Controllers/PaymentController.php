<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Mail\PdfMail;
use App\Models\Director;
use App\Models\Marketer;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class PaymentController extends Controller
{
    public function index(PaymentsDataTable $dataTable)
    {
        $projects = Project::get();
        $plots = Plot::where('status', '!=', 'Registered')->get();
        $marketers = Marketer::where('status', 1)->get();
        return $dataTable->render('pages.payments.index', compact('projects', 'plots', 'marketers'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string',
                'mobile_no' => 'nullable|numeric|digits:10',
                'whatsapp_no' => 'nullable|numeric|digits:10',
                'project_id' => 'required',
                'plot_id' => 'required',
                'marketer_id' => 'required',
                'reference_id' => 'required',
                'payment_status' => 'required',
                'amount_paid' => 'required|numeric|max:999999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $payment = Payment::where('plot_id', $request->plot_id)
                    ->where('payment_status', $request->payment_status)
                    ->where('payment_status', '!=', config('constants.payment_status.temporary_booking'))
                    ->where('payment_status', '!=', config('constants.payment_status.booking'))
                    ->where('payment_status', '!=', config('constants.payment_status.cancelled'))
                    ->first();
                if ($payment) {
                    $response = [
                        'success' => 0,
                        'message' => 'Payment record with same payment status already exists!',
                    ];
                } else {
                    $fullPayment = Payment::where('plot_id', $request->plot_id)->where('payment_status', config('constants.payment_status.full_payment'))->first();
                    if ($fullPayment && ($request->payment_status == config('constants.payment_status.temporary_booking') || $request->payment_status == config('constants.payment_status.booking'))) {
                        $response = [
                            'success' => 0,
                            'message' => 'Full payment already done',
                        ];
                    } else {
                        $plotBalance = Plot::where('id', $request->plot_id)->first();
                        if ($request->payment_status == config('constants.payment_status.temporary_booking') && $request->amount_paid >= $plotBalance->balance_amount) {
                            $response = [
                                'success' => 0,
                                'message' => 'The amount you entered should be lesser than the balance amount!',
                            ];
                        } else {
                            if ($request->payment_status == config('constants.payment_status.booking') && $request->amount_paid > $plotBalance->balance_amount) {
                                $response = [
                                    'success' => 0,
                                    'message' => 'The amount you entered should be lesser than or equal to the balance amount!',
                                ];
                            } else {
                                if (($request->payment_status == config('constants.payment_status.temporary_booking') || $request->payment_status == config('constants.payment_status.booking')) && $request->amount_paid <= 0) {
                                    $response = [
                                        'success' => 0,
                                        'message' => 'Enter payment amount greater than 0',
                                    ];
                                } else {
                                    if ($request->payment_status == config('constants.payment_status.full_payment') && $request->amount_paid != $plotBalance->balance_amount) {
                                        $response = [
                                            'success' => 0,
                                            'message' => 'Full Payment amount should be equal to the balance amount!',
                                        ];
                                    } else {
                                        $plotExists = Payment::where('plot_id', $request->plot_id)->first();
                                        $plotLatest = Payment::where('plot_id', $request->plot_id)->latest()->first();
                                        if (isset($plotExists) && $plotExists->marketer_id != $request->marketer_id) {
                                            $response = [
                                                'success' => 0,
                                                'message' => 'Payment Record Already Exists with Marketer ' . $plotExists->marketers->name . '!',
                                            ];
                                        } else {
                                            if ($request->payment_status == config('constants.payment_status.registered') && $request->balance_amount != 0) {
                                                $response = [
                                                    'success' => 0,
                                                    'message' => 'Full Payment should be made before registering a plot!',
                                                ];
                                            } else {
                                                if ($request->payment_status == config('constants.payment_status.cancelled') && $plotLatest->payment_status == config('constants.payment_status.registered')) {
                                                    $response = [
                                                        'success' => 0,
                                                        'message' => 'Plot already registered, Could not be cancelled!',
                                                    ];
                                                } else {
                                                    if ($request->payment_status == 1) {
                                                        $initialUpdatedAt = now()->timezone('Asia/Kolkata');
                                                    }
                                                    if ($request->payment_status == 2) {
                                                        $partialUpdatedAt = now()->timezone('Asia/Kolkata');
                                                    }
                                                    $payments = Payment::create([
                                                        'customer_name' => $request->customer_name,
                                                        'project_id' => $request->project_id,
                                                        'father_or_husband_name' => $request->father_or_husband_name,
                                                        'mobile_no' => $request->mobile_no,
                                                        'whatsapp_no' => $request->whatsapp_no,
                                                        'address' => $request->address,
                                                        'reference_id' => $request->reference_id,
                                                        'plot_id' => $request->plot_id,
                                                        'marketer_id' => $request->marketer_id,
                                                        'payment_date' => $request->payment_date,
                                                        'payment_status' => $request->payment_status,
                                                        'payment_details' => $request->payment_details,
                                                        'particulars' => $request->particulars,
                                                        'amount_paid' => $request->amount_paid,
                                                        'amount_in_words' =>  $this->convertToWords($request->amount_paid),
                                                        'payment_type' => $request->payment_type,
                                                        'cheque_number' => $request->cheque_number,
                                                        'cheque_date' => $request->cheque_date,
                                                        'bank' => $request->bank,
                                                        'branch' => $request->branch,
                                                        'ref_no' => $request->ref_no,
                                                        'ref_no' => $request->ref_no,
                                                        'initial_updated_at' => $initialUpdatedAt ?? null,
                                                        'partial_updated_at' => $partialUpdatedAt ?? null,
                                                    ]);
                                                    $paymentAll = Payment::where('plot_id', $request->plot_id)->get();
                                                    foreach ($paymentAll as $value) {
                                                        $value->update([
                                                            'customer_name' => $request->customer_name,
                                                            'father_or_husband_name' => $request->father_or_husband_name,
                                                            'mobile_no' => $request->mobile_no,
                                                            'whatsapp_no' => $request->whatsapp_no,
                                                            'address' => $request->address,
                                                        ]);
                                                    }
                                                    $plot = Plot::find($request->plot_id);
                                                    $cancelPayment = Payment::where('plot_id', $request->plot_id)->where('payment_status', '!=', config('constants.payment_status.cancelled'))->get();
                                                    if ($request->payment_status == config('constants.payment_status.temporary_booking')) {
                                                        $plot->status = 'Temporary Booking';
                                                    } elseif ($request->payment_status == config('constants.payment_status.booking')) {
                                                        $plot->status = 'Booking';
                                                    } elseif ($request->payment_status == config('constants.payment_status.full_payment')) {
                                                        $plot->status = 'Full Payment';
                                                    } elseif ($request->payment_status == config('constants.payment_status.registered')) {
                                                        $plot->status = 'Registered';
                                                    } elseif ($request->payment_status == config('constants.payment_status.cancelled')) {
                                                        $plot->status = 'Available';
                                                        $plot->balance_amount = $plot->total_amount;
                                                        $plot->paid_amount = 0;
                                                        $plot->save();
                                                        if ($cancelPayment !== null) {
                                                            $cancelPayment->each->delete();
                                                        }
                                                    }
                                                    $plot->balance_amount -= $request->amount_paid;
                                                    $plot->paid_amount += $request->amount_paid;
                                                    $plot->save();
                                                    $response = [
                                                        'success' => 1,
                                                        'message' => 'Payment Added Successfully!',
                                                        'data' => $payments,
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $response = [
                'success' => 0,
                'message' => 'Unknown error',
                'error' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function show($id)
    {
        $payment = Payment::with('projects', 'plots', 'marketers')->find($id);
        return response()->json(['payment' => $payment]);
    }

    public function edit($id)
    {
        $payment = Payment::with('projects', 'plots', 'marketers')->find($id);
        $plots = Plot::where('project_id', $payment->project_id)->get();
        $otherPayments = Payment::where('plot_id', $payment->plot_id)
            ->where('payment_status', 4)
            ->pluck('payment_status')
            ->toArray();
        $disableAmountPaid = !empty($otherPayments);
        return response()->json([
            'payment' => $payment,
            'plots' => $plots,
            'disableAmountPaid' => $disableAmountPaid
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string',
                'mobile_no' => 'nullable|numeric|digits:10',
                'whatsapp_no' => 'nullable|numeric|digits:10',
                'project_id' => 'required',
                'plot_id' => 'required',
                'marketer_id' => 'required',
                'reference_id' => 'required',
                'payment_status' => 'required',
                'amount_paid' => 'required|numeric|max:999999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $plot = Plot::find($request->plot_id);
                $payment = Payment::where('id', '!=', $id)->where('plot_id', $request->plot_id)
                    ->where('payment_status', $request->payment_status)
                    ->where('payment_status', '!=', config('constants.payment_status.temporary_booking'))
                    ->where('payment_status', '!=', config('constants.payment_status.booking'))
                    ->first();
                if ($payment) {
                    $response = [
                        'success' => 0,
                        'message' => 'Payment record with same payment status already exists!',
                    ];
                } else {
                    if (($request->payment_status == config('constants.payment_status.temporary_booking') || $request->payment_status == config('constants.payment_status.booking')) && $request->amount_paid <= 0) {
                        $response = [
                            'success' => 0,
                            'message' => 'Please enter payment amount greater than 0',
                        ];
                    } else {
                        $plotTotalAmount = $plot->total_amount;
                        $checkPaymentTotal = Payment::where('plot_id', $request->plot_id)->where('id', '!=', $id)->sum('amount_paid');
                        $checkValidAmount = ($request->amount_paid + $checkPaymentTotal) < $plotTotalAmount;
                        $checkValidPlotAmount = ($request->amount_paid + $checkPaymentTotal) <= $plotTotalAmount;
                        if ($request->payment_status == config('constants.payment_status.temporary_booking') && !$checkValidAmount) {
                            $response = [
                                'success' => 0,
                                'message' => 'The amount you entered should be lesser than the balance amount!',
                            ];
                        } elseif ($request->payment_status == config('constants.payment_status.booking') && !$checkValidPlotAmount) {
                            $response = [
                                'success' => 0,
                                'message' => 'The amount you entered should be lesser than or equal to the balance amount!',
                            ];
                        } elseif ($request->payment_status == config('constants.payment_status.full_payment') && ($checkPaymentTotal ? $request->amount_paid + $checkPaymentTotal != $plotTotalAmount : ($request->amount_paid != $plotTotalAmount))) {
                            $response = [
                                'success' => 0,
                                'message' => 'Full Payment amount should be equal to the balance amount!',
                            ];
                        } else {
                            $payment = Payment::where('id', $id)->first();
                            $plotUpdate = Plot::where('id', $payment->plot_id)->first();
                            $paymentPreviousAmount = $payment->amount_paid;
                            $plotExists = Payment::where('plot_id', $request->plot_id)->get();
                            $hasDifferentMarketer = false;
                            foreach ($plotExists as $pe) {
                                if ($pe->marketer_id != $request->marketer_id) {
                                    $hasDifferentMarketer = true;
                                    break;
                                }
                            }
                            if ($plotExists->count() > 1 && $hasDifferentMarketer) {
                                $response = [
                                    'success' => 0,
                                    'message' => 'Payment Record Already Exists with Marketer ' . $pe->marketers->name . '!',
                                ];
                            } else {
                                if ($request->payment_status == 1 && $payment->payment_status != 1) {
                                    $initialUpdatedAt = now()->timezone('Asia/Kolkata');
                                }
                                if ($request->payment_status == 2 && $payment->payment_status != 2) {
                                    $partialUpdatedAt = now()->timezone('Asia/Kolkata');
                                }
                                $payment->update([
                                    'project_id' => $request->project_id,
                                    'plot_id' => $request->plot_id,
                                    'marketer_id' => $request->marketer_id,
                                    'payment_date' => $request->payment_date,
                                    'payment_status' => $request->payment_status,
                                    'payment_details' => $request->payment_details,
                                    'particulars' => $request->particulars,
                                    'amount_paid' => $request->amount_paid,
                                    'amount_in_words' =>  $this->convertToWords($request->amount_paid),
                                    'payment_type' => $request->payment_type,
                                    'cheque_number' => $request->cheque_number,
                                    'cheque_date' => $request->cheque_date,
                                    'bank' => $request->bank,
                                    'branch' => $request->branch,
                                    'ref_no' => $request->ref_no,
                                    'initial_updated_at' => $initialUpdatedAt ?? $payment->initial_updated_at,
                                    'partial_updated_at' => $partialUpdatedAt ?? $payment->partial_updated_at,
                                ]);
                                $paymentAll = Payment::where('plot_id', $request->plot_id)->get();
                                foreach ($paymentAll as $value) {
                                    $value->update([
                                        'customer_name' => $request->customer_name,
                                        'father_or_husband_name' => $request->father_or_husband_name,
                                        'mobile_no' => $request->mobile_no,
                                        'whatsapp_no' => $request->whatsapp_no,
                                        'address' => $request->address,
                                    ]);
                                }

                                if ($request->payment_status == config('constants.payment_status.temporary_booking')) {
                                    $plot->status = 'Temporary Booking';
                                } elseif ($request->payment_status == config('constants.payment_status.booking')) {
                                    $plot->status = 'Booking';
                                } elseif ($request->payment_status == config('constants.payment_status.full_payment')) {
                                    $plot->status = 'Full Payment';
                                } elseif ($request->payment_status == config('constants.payment_status.registered')) {
                                    $plot->status = 'Registered';
                                } elseif ($request->payment_status == config('constants.payment_status.cancelled')) {
                                    $plot->status = 'Available';
                                    $plot->balance_amount = $plot->total_amount;
                                    $plot->paid_amount = 0;
                                    $plot->save();
                                    // return;
                                }
                                if ($request->plot_id != $plotUpdate->id) {
                                    $plotUpdate->balance_amount = $plotUpdate->balance_amount + $paymentPreviousAmount;
                                    $plotUpdate->paid_amount -= $payment->amount_paid;
                                    $plotUpdate->save();
                                    $plot->balance_amount = $plot->balance_amount - $request->amount_paid;
                                    $plot->paid_amount += $request->amount_paid;
                                    $plot->save();
                                } else {
                                    $plot->balance_amount = $plot->balance_amount + ($paymentPreviousAmount - $request->amount_paid);
                                    $plot->paid_amount -= $paymentPreviousAmount - $request->amount_paid;
                                    $plot->save();
                                }
                                $response = [
                                    'success' => 1,
                                    'message' => 'Payment Updated Successfully!',
                                    'data' => $payment,
                                ];
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $response = [
                'success' => 0,
                'message' => 'Unknown error',
                'error' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $plot_id = $payment->plot_id;

            $fullPaymentExists = Payment::where('plot_id', $plot_id)->where('payment_status', config('constants.payment_status.full_payment'))->exists();
            $partialPaymentExists = Payment::where('plot_id', $plot_id)->where('payment_status', config('constants.payment_status.booking'))->exists();

            if ($payment->payment_status == config('constants.payment_status.temporary_booking') && ($fullPaymentExists || $partialPaymentExists)) {
                $response = [
                    'success' => 0,
                    'message' => 'Cannot delete development charges because full payment or plot amount exists for this plot.',
                ];
            } elseif ($payment->payment_status == config('constants.payment_status.booking') && $fullPaymentExists) {
                $response = [
                    'success' => 0,
                    'message' => 'Cannot delete plot amount because a full payment exists for this plot.',
                ];
            } else {
                $payment->delete();
                $paymentTotalAmount = 0;
                $paymentTotal = Payment::where('plot_id', $plot_id)->get();
                foreach ($paymentTotal as $value) {
                    $paymentTotalAmount += $value->amount_paid;
                }
                $last_payment = Payment::where('plot_id', $plot_id)->latest()->first();
                $plot = Plot::find($plot_id);
                if ($last_payment) {
                    $plot->balance_amount = $plot->total_amount - $paymentTotalAmount;
                    $plot->paid_amount = $paymentTotalAmount;
                    if ($last_payment->payment_status == config('constants.payment_status.temporary_booking')) {
                        $plot->status = 'Temporary Booking';
                    } elseif ($last_payment->payment_status == config('constants.payment_status.booking')) {
                        $plot->status = 'Booking';
                    } elseif ($last_payment->payment_status == config('constants.payment_status.full_payment')) {
                        $plot->status = 'Full Payment';
                    } elseif ($last_payment->payment_status == config('constants.payment_status.registered')) {
                        $plot->status = 'Registered';
                    } elseif ($last_payment->payment_status == config('constants.payment_status.cancelled')) {
                        $plot->status = 'Available';
                    }
                } else {
                    $plot->balance_amount = $plot->total_amount;
                    $plot->paid_amount = 0;
                    $plot->status = 'Available';
                }
                $plot->save();
                $response = [
                    'success' => 1,
                    'message' => 'Payment Deleted Successfully!',
                ];
            }
        } catch (Exception $e) {
            $response = [
                'success' => 0,
                'message' => 'Error deleting payment',
                'error' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function plots($project_id)
    {
        $plots = Plot::where('project_id', $project_id)->get();
        return response()->json($plots);
    }
    public function selectPlots($project_id)
    {
        $plots = Plot::where('project_id', $project_id)->where('status', '!=', 'Registered')->get();
        return response()->json($plots);
    }

    public function marketer($marketer_id)
    {
        $marketer = Marketer::where('id', $marketer_id)->first();
        return response()->json($marketer);
    }

    public function getPaymentsData($plot_id)
    {
        $plot_amount = Plot::where('id', $plot_id)->first();
        $paymentData = Payment::with('marketers', 'plots')->where('plot_id', $plot_id)->first();
        return response()->json([
            'plot_amount' => $plot_amount,
            'paymentData' => $paymentData
        ]);
    }

    public function download_receipt($id, $status)
    {
        $payment_receipt = Payment::where('id', $id)->where('payment_status', $status)->first();
        $payment_status = $payment_receipt->payment_status;
        if ($payment_status == 1) {
            $data = Payment::with('projects', 'plots', 'marketers')->find($id);
            $director_id = $data->marketers->director;
            $chief_director_id = $data->marketers->chief_director;
            $director = Marketer::where('id', $director_id)->first();
            $chief_director = Marketer::where('id', $chief_director_id)->first();
            $invoice_date = date('jS F Y', strtotime($data->created_at));
            $pdf = Pdf::loadView('includes.receipt.initialpay_receipt', compact('data', 'director', 'chief_director'));
            return $pdf->stream('Development_Charges_No # ' . $id . ' Date_' . $invoice_date . '.pdf');
        }
        if ($payment_status == 2) {
            $data = Payment::with('projects', 'plots', 'marketers')->find($id);
            $invoice_date = date('jS F Y', strtotime($data->created_at));
            $pdf = Pdf::loadView('includes.receipt.partpay_receipt', compact('data'));
            return $pdf->stream('Plot_Amount_No # ' . $id . ' Date_' . $invoice_date . '.pdf');
        }
        if ($payment_status == 3) {
            $data = Payment::with('projects', 'plots', 'marketers')->find($id);
            $invoice_date = date('jS F Y', strtotime($data->created_at));
            $pdf = Pdf::loadView('includes.receipt.fullpay_receipt', compact('data'));
            return $pdf->stream('Full_Payment # ' . $id . ' Date_' . $invoice_date . '.pdf');
        }
        if ($payment_status == 4) {
            $data = Payment::with('projects', 'plots', 'marketers')->find($id);
            $invoice_date = date('jS F Y', strtotime($data->created_at));
            $pdf = Pdf::loadView('includes.receipt.fullpay_receipt', compact('data'));
            return $pdf->stream('Full_Payment # ' . $id . ' Date_' . $invoice_date . '.pdf');
        }
    }

    private function convertToWords($amount)
    {
        $ones = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $output = '';
        if ($amount == 0) {
            return 'Zero only';
        }
        $amount = abs($amount);
        function convertNumberToWords($num, $ones, $tens, $teens)
        {
            $output = '';
            if ($num >= 20) {
                $tensDigit = floor($num / 10);
                $output .= $tens[$tensDigit] . ' ';
                $num %= 10;
            }
            if ($num >= 10 && $num < 20) {
                $output .= $teens[$num - 10] . ' ';
                return trim($output);
            }
            if ($num > 0) {
                $output .= $ones[$num] . ' ';
            }
            return trim($output);
        }
        if ($amount >= 1000000000) {
            $billions = floor($amount / 1000000000);
            $output .= convertNumberToWords($billions, $ones, $tens, $teens) . ' Billion ';
            $amount %= 1000000000;
        }
        if ($amount >= 10000000) {
            $crores = floor($amount / 10000000);
            $output .= convertNumberToWords($crores, $ones, $tens, $teens) . ' Crore ';
            $amount %= 10000000;
        }
        if ($amount >= 100000) {
            $lakhs = floor($amount / 100000);
            $output .= convertNumberToWords($lakhs, $ones, $tens, $teens) . ' Lakh ';
            $amount %= 100000;
        }
        if ($amount >= 1000) {
            $thousands = floor($amount / 1000);
            $output .= convertNumberToWords($thousands, $ones, $tens, $teens) . ' Thousand ';
            $amount %= 1000;
        }
        if ($amount >= 100) {
            $hundreds = floor($amount / 100);
            $output .= convertNumberToWords($hundreds, $ones, $tens, $teens) . ' Hundred ';
            $amount %= 100;
        }
        $output .= convertNumberToWords($amount, $ones, $tens, $teens);
        return trim($output) . ' only';
    }
}
