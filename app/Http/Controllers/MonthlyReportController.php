<?php

namespace App\Http\Controllers;

use App\Exports\MarketerReportExport;
use App\Exports\MonthlyReportExport;
use App\Models\Marketer;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MonthlyReportController extends Controller
{
    public function monthlyReport()
    {
        $projects = Project::get();
        return view('pages.monthly-report.index', compact('projects'));
    }

    public function datatable_monthly(Request $request)
    {
        $data = [];
        $monthYearInput = $request->monthYearInput;
        $project = $request->project;
        $status = $request->status;
        if ($monthYearInput !== null) {
            $date_array = explode("-", $monthYearInput);
            $year = $date_array[0];
            $month = $date_array[1];
            $from_date = $year . '-' . $month . '-01';
            $to_date = date('Y-m-t', strtotime($from_date));
        } else {
            $from_date = null;
            $to_date = null;
        }
        $query = Payment::with('projects', 'plots', 'marketers');

        if ($from_date !== null && $to_date !== null) {
            $query->whereBetween('payment_date', [$from_date, $to_date]);
        }
        if ($project !== null) {
            $query->where('project_id', $project);
        }
        if ($status !== null) {
            $query->where('payment_status', $status);
        }

        $payment = $query->get();
        $data = [];

        foreach ($payment as $paymentItem) {
            $data[] = [
                "project" => $paymentItem->projects->project_name,
                "plot_no" => $paymentItem->plots->plot_no,
                "status" => $paymentItem->payment_status,
                "name" => $paymentItem->marketers->name,
                "total_amount" => $paymentItem->plots->total_amount,
                "total_sqft" => $paymentItem->plots->plot_sqft,
                "amount_paid" => $paymentItem->amount_paid
            ];
        }

        if ($request->action == 'excel') {
            return Excel::download(new MonthlyReportExport($data), 'monthly_report.xlsx');
        }

        return DataTables::of($data)
            // ->addIndexColumn()
            // ->addColumn('action', function ($row) {
            //     return "<div class='btn-group'><a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData(" . $row["id"] . ")'><i title='view' class='fa fa-eye'></i></a></div>";
            // })
            // ->rawColumns(['action'])
            ->make(true);
    }

    public function monthlyReportShow(string $id, Request $request)
    {
        $monthYearInput = $request->monthYearInput;
        $project = $request->project;
        if ($monthYearInput !== null) {
            $date_array = explode(" - ", $monthYearInput);
            $from_date = date("Y-m-d", strtotime($date_array[0]));
            $to_date = date("Y-m-d", strtotime($date_array[1]));
        } else {
            $from_date = null;
            $to_date = null;
        }

        $query = Payment::where('marketer_id', $id);

        if ($from_date !== null && $to_date !== null) {
            $query->whereBetween('payment_date', [$from_date, $to_date]);
        }

        if ($project !== null) {
            $query->where('project_id', $project);
        }

        $payments = $query->pluck('plot_id')->unique();
        $totalPlots = Plot::with('project')->whereIn('id', $payments)->get();
        $marketerProgress = Marketer::find($id);
        return response()->json([
            'marketerProgress' => $marketerProgress,
            'totalPlots' => $totalPlots,
        ]);
    }
}
