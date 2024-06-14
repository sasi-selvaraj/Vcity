<?php

namespace App\Http\Controllers;

use App\DataTables\MarketerPayoutDataTable;
use App\Models\Marketer;
use App\Models\MarketerPayout;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketerPayoutController extends Controller
{
    public function index(MarketerPayoutDataTable $dataTable)
    {
        $marketers = Marketer::all();
        return $dataTable->render('pages.marketer-payout.index', compact('marketers'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project' => 'required',
                'plot_no' => 'required',
                'marketer_name' => 'required|string',
                'commission' => 'nullable|numeric|max:99999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                if ($request->commission >= $request->plot_amount) {
                    $response = [
                        'success' => 0,
                        'message' => 'Commission should be lesser than Plot Total Amount',
                    ];
                } else {
                    $marketerPayouts = MarketerPayout::create([
                        'project_id' => $request->project,
                        'plot_id' => $request->plot_no,
                        'marketer_id' => $request->marketer_name,
                        'marketer_vcity_id' => $request->marketer_id,
                        'commission' => $request->commission,
                        'director' => $request->director,
                        'director_commission' => $request->director_commission,
                        'assistant_director' => $request->assist_director,
                        'assistant_director_commission' => $request->assist_director_commission,
                        'crm' => $request->crm,
                        'crm_commission' => $request->crm_commission,
                        'senior_director' => $request->senior_director,
                        'senior_director_commission' => $request->senior_director_commission,
                        'chief_director' => $request->chief_director,
                        'chief_director_commission' => $request->chief_director_commission,
                    ]);
                    $response = [
                        'success' => 1,
                        'message' => 'Marketer Payout Added Successfully!',
                    ];
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
        $marketerPayout = MarketerPayout::with('project', 'plot', 'marketer')->findOrFail($id);
        return response()->json(['marketerPayout' => $marketerPayout]);
    }

    public function edit($id)
    {
        $marketerPayout = MarketerPayout::with('project', 'plot', 'marketer')->findOrFail($id);
        return response()->json(['marketerPayout' => $marketerPayout]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project' => 'required',
                'plot_no' => 'required',
                'marketer_name' => 'required|string',
                'marketer_id' => 'nullable',
                'commission' => 'nullable|numeric|max:99999999999',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                if ($request->commission >= $request->plot_amount) {
                    $response = [
                        'success' => 0,
                        'message' => 'Commission should be lesser than Plot Total Amount',
                    ];
                } else {
                    $marketerPayout = MarketerPayout::where('id', $id)->first();
                    $marketerPayout->update([
                        'project_id' => $request->project,
                        'plot_id' => $request->plot_no,
                        'marketer_id' => $request->marketer_name,
                        'marketer_vcity_id' => $request->marketer_id,
                        'commission' => $request->commission,
                        'director' => $request->director,
                        'director_commission' => $request->director_commission,
                        'assistant_director' => $request->assist_director,
                        'assistant_director_commission' => $request->assist_director_commission,
                        'crm' => $request->crm,
                        'crm_commission' => $request->crm_commission,
                        'senior_director' => $request->senior_director,
                        'senior_director_commission' => $request->senior_director_commission,
                        'chief_director' => $request->chief_director,
                        'chief_director_commission' => $request->chief_director_commission,
                    ]);
                    $response = [
                        'success' => 1,
                        'message' => 'Marketer Payout Updated Successfully!',
                    ];
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

    public function destroy(string $id)
    {
        try {
            $marketerPayout = MarketerPayout::where('id', $id)->first();
            $marketerPayout->delete();
            $response = [
                'success' => 1,
                'message' => 'Marketer Payout deleted successfully.'
            ];
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Unknown error',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function fetchProjects(Request $request)
    {
        $marketerId = $request->marketer_id;
        $marketers = Marketer::with(['Director', 'assistantDirector', 'crm', 'chiefDirector', 'seniorDirector'])->where('id', $marketerId)->first();
        $projects = Payment::where('marketer_id', $marketerId)
            ->where('payment_status', 3)
            ->distinct('project_id')
            ->pluck('project_id');
        $projectsData = Project::whereIn('id', $projects)->get();
        // if ($projectsData->isEmpty()) {
        //     return response()->json(['error' => 'No projects found for this marketer'], 404);
        // }
        return response()->json(['marketers' => $marketers, 'projectsData' => $projectsData]);
    }

    public function fetchPlots(Request $request)
    {
        $projectId = $request->project_id;
        $plots = Payment::where('project_id', $projectId)
            ->where('payment_status', 3)
            ->distinct('plot_id')
            ->pluck('plot_id');
        $plotsData = Plot::whereIn('id', $plots)->get();
        return response()->json($plotsData);
    }

    public function download_receipt($id)
    {
        $data = MarketerPayout::with('project', 'plot', 'marketer', 'Director', 'assistantDirector', 'crm', 'chiefDirector', 'seniorDirector')->find($id);
        $invoice_date = date('jS F Y', strtotime($data->created_at));
        $pdf = Pdf::loadView('includes.marketer_payout_receipt', compact('data'));
        return $pdf->stream('Payout_No # ' . $id . ' Date_' . $invoice_date . '.pdf');
    }
}
