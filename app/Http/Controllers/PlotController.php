<?php

namespace App\Http\Controllers;

use App\DataTables\PlotsDataTable;
use App\Imports\PlotImport;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlotController extends Controller
{
    public function index(PlotsDataTable $dataTable)
    {
        $projects = Project::all();
        return $dataTable->render('pages.plots.index', compact('projects'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_name' => 'required',
                'plot_no' => 'required',
                'block' => 'required|string',
                'plot_sqft' => 'nullable|numeric|max:999999999999',
                'sqft_rate' => 'nullable|numeric|max:999999999999',
                'total_amount' => 'nullable|numeric|max:999999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $project = Project::where('id', $request->project_name)->first();
                if ($project) {
                    $existingPlot = Plot::where('project_id', $project->id)->where('plot_no', $request->plot_no)->first();
                    if ($existingPlot) {
                        $response = [
                            'success' => 0,
                            'message' => 'Plot "' . $request->plot_no . '" with Project "' . $request->project_name . '" already exists.',
                        ];
                    } else {
                        $plot_sqft = Plot::where('project_id', $project->id)->sum('plot_sqft');
                        if ($plot_sqft) {
                            $plot_total_sqft = $plot_sqft + $request->plot_sqft;
                            $available_sqft = $project->total_no_of_sqft - $plot_sqft;
                        } else {
                            $plot_total_sqft = $request->plot_sqft;
                            $available_sqft = $project->total_no_of_sqft;
                        }
                        if ($plot_total_sqft > $project->total_no_of_sqft) {
                            $response = [
                                'success' => 0,
                                'message' => 'The available Sq.ft for this Project is ' . $available_sqft,
                            ];
                        } else {
                            $plot_total = Plot::where('project_id', $project->id)->count();
                            if ($plot_total >= $project->total_plots) {
                                $response = [
                                    'success' => 0,
                                    'message' => "You can't add another plot for this project as its maximum limit '" . $project->total_plots . " Plots' got reached",
                                ];
                            } else {
                                if ($request->status == 'Hold') {
                                    $statusUpdatedAt = now()->timezone('Asia/Kolkata');
                                }
                                $plots = Plot::create([
                                    'project_id' => $request->project_name,
                                    'plot_no' => $request->plot_no,
                                    'block' => $request->block,
                                    'facing' => $request->facing,
                                    'plot_sqft' => $request->plot_sqft,
                                    // 'sqft_rate' =>  $request->sqft_rate,
                                    'location' => $request->location,
                                    'glv_rate' => $request->glv_rate,
                                    'glv' => $request->glv,
                                    'dev_rate' => $request->dev_rate,
                                    'development_charges' => $request->development_charges,
                                    'gst' => $request->gst,
                                    'total_amount' => $request->total_amount,
                                    'balance_amount' => $request->total_amount,
                                    'status' => $request->status ?? 'Available',
                                    'status_updated_at' => $statusUpdatedAt ?? null,
                                    'description' => $request->description,
                                ]);
                                $response = [
                                    'success' => 1,
                                    'message' => 'Plot Added Successfully!',
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

    public function show($id)
    {
        $plot = Plot::with('project', 'payments')->find($id);
        return view('pages.plots.view', compact('plot'));
    }

    public function edit($id)
    {
        $plot = Plot::with('project')->find($id);
        return response()->json(['plot' => $plot]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_name' => 'required',
                'plot_no' => 'required',
                'block' => 'required|string',
                'plot_sqft' => 'nullable|numeric|max:999999999999',
                'sqft_rate' => 'nullable|numeric||max:999999999999',
                'total_amount' => 'nullable|numeric||max:999999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $plot = Plot::where('id', $id)->first();
                $project = Project::where('id', $request->project_name)->first();
                if ($project) {
                    $existingPlot = Plot::where('project_id', $project->id)->where('plot_no', $request->plot_no)
                        ->where('id','!=', $id)
                        ->first();
                    if ($existingPlot) {
                        $response = [
                            'success' => 0,
                            'message' => 'Plot "' . $request->plot_no . '" with Project "' . $request->project_name . '" already exists.',
                        ];
                    } else {
                        $plot_sqft = Plot::where('project_id', $project->id)->where('id','!=', $id)->sum('plot_sqft');
                        if ($plot_sqft) {
                            $plot_total_sqft = $plot_sqft + $request->plot_sqft;
                            $available_sqft = $project->total_no_of_sqft - $plot_sqft;
                        } else {
                            $plot_total_sqft = $request->plot_sqft;
                            $available_sqft = $project->total_no_of_sqft;
                        }
                        if ($plot_total_sqft > $project->total_no_of_sqft) {
                            $response = [
                                'success' => 0,
                                'message' => 'The available Sq.ft for this Project is ' . $available_sqft,
                            ];
                        } else {
                            $projectPlots = Project::where('id', $request->project_name)->first();
                            $plot_total = Plot::where('project_id', $projectPlots->id)->where('id', '!=', $id)->count();
                            if ($plot_total >= $projectPlots->total_plots) {
                                $response = [
                                    'success' => 0,
                                    'message' => "You can't add another plot for this project as its maximum limit '" . $projectPlots->total_plots ." Plots' got reached",
                                ];
                            } else {
                                if ($request->status == 'Hold' && $plot->status != 'Hold') {
                                    $statusUpdatedAt = now()->timezone('Asia/Kolkata');
                                }
                                $payment = Payment::where('plot_id', $id)->latest()->first();
                                if ($payment) {
                                    switch ($payment->payment_status) {
                                        case 1:
                                            $plot_status = 'Temporary Booking';
                                            break;
                                        case 2:
                                            $plot_status = 'Booking';
                                            break;
                                        case 3:
                                            $plot_status = 'Full Payment';
                                            break;
                                        case 4:
                                            $plot_status = 'Registered';
                                            break;
                                        case 5:
                                            $plot_status = 'Available';
                                            break;
                                        default:
                                            $plot_status = 'Unknown';
                                            break;
                                    }
                                } else {
                                    $plot_status = 'Available';
                                }
                                if ($payment && $payment->plot_id == $id && $payment->payment_status != config('constants.payment_status.cancelled') &&  ($request->status == 'Available' || $request->status == 'Hold')) {
                                    $response = [
                                        'success' => 0,
                                        'message' => 'This Plot has already been booked.'
                                    ];
                                } else {
                                    $plot->update([
                                        'project_id' => $request->project_name,
                                        'plot_no' => $request->plot_no,
                                        'block' => $request->block,
                                        'facing' => $request->facing,
                                        'plot_sqft' => $request->plot_sqft,
                                        'sqft_rate' => $request->sqft_rate,
                                        'location' => $request->location,
                                        'glv_rate' => $request->glv_rate,
                                        'glv' => $request->glv,
                                        'dev_rate' => $request->dev_rate,
                                        'development_charges' => $request->development_charges,
                                        'gst' => $request->gst,
                                        'total_amount' =>  $request->total_amount,
                                        'balance_amount' => $request->total_amount - $plot->paid_amount,
                                        'status' => $request->status == '' ? $plot_status : $request->status,
                                        'status_updated_at' => $statusUpdatedAt ?? $plot->status_updated_at,
                                        'description' => $request->description,
                                    ]);
                                    $response = [
                                        'success' => 1,
                                        'message' => 'Plot updated successfully.'
                                    ];
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

    public function destroy($id)
    {
        try {
            $plot = Plot::with('project')->where('id', $id)->first();
            $paymentExists = Payment::where('plot_id', $id)->exists();
            if ($paymentExists) {
                $response = [
                    'success' => 0,
                    'message' => 'This Plot cannot be deleted because, Payment exists for this plot'
                ];
            } else {
                $plot->delete();
                $response = [
                    'success' => 1,
                    'message' => 'Plot "' . $plot->plot_no . '" from the Project "' . $plot->project->project_name . '" has been deleted successfully.'
                ];
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

    public function import(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:xlsx,xls',
            ]
        );
        if (!$validator->fails()) {
            DB::beginTransaction();
            try {
                $plotImport = new PlotImport;
                $plotImport->import($request->file('file'));
                $error_message = [];
                $errors = $plotImport->failures();

                if (count($errors) > 0) {
                    DB::rollback();
                    foreach ($errors as $key => $error) {
                        $error_message[] = $error->toArray();
                    }
                    $error_message = array_values(Arr::dot($error_message));
                    $response_data = ['message' => $error_message, 'success' => 0, 'type' => 1];
                } else {
                    $result = $plotImport->getRowCount();
                    if ($result['success'] == 1) {
                        DB::commit();
                        $response_data = ['message' => 'Plots Imported Successfully', 'success' => 1];
                    } else {
                        DB::rollback();
                        $response_data = ['message' => $result['messages'], 'success' => 0, 'type' => 1];
                    }
                }
            } catch (exception $e) {
                DB::rollback();
                $response_data = ['message' => $e->getMessage(), 'success' => 0];
            }
        } else {
            $errors = array_values(Arr::dot($validator->errors()->toArray()));
            $response_data = ["success" => 0, "message" => $errors[0], "error" => $errors];
        }
        return response()->json($response_data);
    }
}
