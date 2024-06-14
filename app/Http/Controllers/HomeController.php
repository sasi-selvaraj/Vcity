<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Marketer;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::get();
        $plots = Plot::where('status', 'Available')->count();
        $todaySales = Payment::whereDate('created_at', now())
            ->distinct('plot_id')
            ->count();
        $totalSales = Payment::distinct('plot_id')->count();
        $directors = Director::count();
        $marketers = Marketer::count();
        $salesOverview = Payment::whereBetween(
            'created_at',
            [Carbon::now()->subYear(), Carbon::now()]
        )->pluck('created_at', 'id');

        // Chart
        $currentYear = date('Y');

        $plotData = Payment::whereYear('created_at', $currentYear)
            ->where('payment_status', 3)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as plot_count'))
            ->groupBy('month')
            ->get();

        $chartData = [];
        foreach ($plotData as $data) {
            $month = date('M', mktime(0, 0, 0, $data->month, 1));
            $chartData[$month] = $data->plot_count;
        }

        return view('pages.dashboard', compact('projects', 'plots', 'todaySales', 'totalSales', 'directors', 'marketers', 'chartData'));
    }

    public function plots(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'project_id' => 'required',
                ]
            );
            if (!$validator->fails()) {
                if ($request->project_id == 0) {
                    $plots = Plot::with('project')->orderByRaw('CONVERT(plot_no, SIGNED) asc')->get();
                } else {
                    $plots = Plot::with('project')->where('project_id', $request->project_id)->orderByRaw('CONVERT(plot_no, SIGNED) asc')->get();
                }
                $IST = new \DateTimeZone('Asia/Kolkata');
                foreach ($plots as $p) {
                    $payment = Payment::where('payment_status', 1)->where('plot_id', $p->id)->first();
                    $payment2 = Payment::where('payment_status', 2)->where('plot_id', $p->id)->first();
                    // dd($p->id,$payment2);
                    if (empty($payment) && empty($payment2)) {
                        $p->days_left = null;
                    } else {
                        if ($payment) {
                            $checkOtherPayments = Payment::where('plot_id', $payment->plot_id)
                                ->where('payment_status', '!=', 1)
                                ->first();

                            if (empty($checkOtherPayments) || !isset($checkOtherPayments)) {
                                $initialUpdatedAt = Carbon::parse($payment->initial_updated_at, $IST);
                                $currentDate = Carbon::now($IST);
                                $daysDifference = $currentDate->diffInDays($initialUpdatedAt);

                                if ($daysDifference == 3) {
                                    $p->days_left = '2 days left';
                                } elseif ($daysDifference == 4) {
                                    $p->days_left = '1 day left';
                                } else {
                                    $p->days_left = null;
                                }
                            } else {
                                $p->days_left = null;
                            }
                        }
                        if ($payment2) {
                            $checkOtherPayments = Payment::where('plot_id', $payment2->plot_id)->where('payment_status', 3)->first();
                            if (empty($checkOtherPayments) || !isset($checkOtherPayments)) {
                                $partialUpdatedAt = Carbon::parse($payment2->partial_updated_at, $IST);
                                $currentDate = Carbon::now($IST);
                                $daysDifference = $currentDate->diffInDays($partialUpdatedAt);
                                if ($daysDifference == 21) {
                                    $p->days_left = '9 days left';
                                } elseif ($daysDifference == 22) {
                                    $p->days_left = '8 days left';
                                } elseif ($daysDifference == 23) {
                                    $p->days_left = '7 days left';
                                } elseif ($daysDifference == 24) {
                                    $p->days_left = '6 days left';
                                } elseif ($daysDifference == 25) {
                                    $p->days_left = '5 days left';
                                } elseif ($daysDifference == 26) {
                                    $p->days_left = '4 days left';
                                } elseif ($daysDifference == 27) {
                                    $p->days_left = '3 days left';
                                } elseif ($daysDifference == 28) {
                                    $p->days_left = '2 days left';
                                } elseif ($daysDifference == 29) {
                                    $p->days_left = '1 day left';
                                } elseif ($daysDifference > 29) {
                                    $p->days_left = $daysDifference . ' days before';
                                } else {
                                    $p->days_left = null;
                                }
                            } else {
                                $p->days_left = null;
                            }
                        }
                    }
                }
                if ($plots) {
                    $response = [
                        'success' => 1,
                        'data' => $plots
                    ];
                } else {
                    $response = [
                        'success' => 0,
                        'message' => 'No record found.'
                    ];
                }
            }
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Unknown error',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function plotsStatus(Request $request)
    {
        try {
            $response = [];
            $validator = Validator::make(
                $request->all(),
                [
                    // 'project_id' => 'required',
                    'status' => 'required',
                ]
            );
            if (!$validator->fails()) {
                if ($request->project_id == 0) {
                    if ($request->status == "All")
                        $plots = Plot::orderBy('plot_no', 'ASC')->get();
                    else
                        $plots = Plot::where('status', $request->status)->orderByRaw('CONVERT(plot_no, SIGNED) asc')->get();
                } else {
                    if ($request->status == "All")
                        $plots = Plot::where('project_id', $request->project_id)->get();
                    else
                        $plots = Plot::where('project_id', $request->project_id)->where('status', $request->status)->get();
                }
                $IST = new \DateTimeZone('Asia/Kolkata');
                foreach ($plots as $p) {
                    $payment = Payment::where('payment_status', 1)->where('plot_id', $p->id)->first();
                    $payment2 = Payment::where('payment_status', 2)->where('plot_id', $p->id)->first();
                    if (empty($payment) && empty($payment2)) {
                        $p->days_left = null;
                    } else {
                        if ($payment) {
                            $checkOtherPayments = Payment::where('plot_id', $payment->plot_id)->where('payment_status', '!=', 1)->first();
                            if (empty($checkOtherPayments) || !isset($checkOtherPayments)) {
                                $initialUpdatedAt = Carbon::parse($payment->initial_updated_at, $IST);
                                $currentDate = Carbon::now($IST);
                                $daysDifference = $currentDate->diffInDays($initialUpdatedAt);
                                if ($daysDifference == 3) {
                                    $p->days_left = 2;
                                } elseif ($daysDifference == 4) {
                                    $p->days_left = 1;
                                } else {
                                    $p->days_left = null;
                                }
                            } else {
                                $p->days_left = null;
                            }
                        } elseif ($payment2) {
                            $checkOtherPayments = Payment::where('plot_id', $payment2->plot_id)->where('payment_status', 3)->first();
                            if (empty($checkOtherPayments) || !isset($checkOtherPayments)) {
                                $partialUpdatedAt = Carbon::parse($payment2->partial_updated_at, $IST);
                                $currentDate = Carbon::now($IST);
                                $daysDifference = $currentDate->diffInDays($partialUpdatedAt);
                                if ($daysDifference == 21) {
                                    $p->days_left = 9;
                                } elseif ($daysDifference == 22) {
                                    $p->days_left = 8;
                                } elseif ($daysDifference == 23) {
                                    $p->days_left = 7;
                                } elseif ($daysDifference == 24) {
                                    $p->days_left = 6;
                                } elseif ($daysDifference == 25) {
                                    $p->days_left = 5;
                                } elseif ($daysDifference == 26) {
                                    $p->days_left = 4;
                                } elseif ($daysDifference == 27) {
                                    $p->days_left = 3;
                                } elseif ($daysDifference == 28) {
                                    $p->days_left = 2;
                                } elseif ($daysDifference == 29) {
                                    $p->days_left = 1;
                                } else {
                                    $p->days_left = null;
                                }
                            } else {
                                $p->days_left = null;
                            }
                        } else {
                            $p->days_left = null;
                        }
                    }
                }
                if ($plots) {
                    $response = [
                        'success' => 1,
                        'data' => $plots
                    ];
                } else {
                    $response = [
                        'success' => 0,
                        'message' => 'No record found.'
                    ];
                }
            }
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Unknown error',
                'error' => $e->getMessage()
            ]);
        }
    }
}
