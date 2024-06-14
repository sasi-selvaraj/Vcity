<?php

namespace App\Http\Controllers;

use App\DataTables\MarketerDataTable;
use App\DataTables\MarketerProgressDataTable;
use App\Exports\MarketerReportExport;
use App\Helpers\Helpers;
use App\Imports\MarketerImport;
use App\Imports\ProjectImport;
use App\Models\Director;
use App\Models\Marketer;
use App\Models\MarketerAttachment;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MarketerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MarketerDataTable $dataTable)
    {
        return $dataTable->render('pages.marketers.index');
    }
    public function marketerProgress()
    {
        $projects = Project::get();
        return view('pages.marketer-progress.index', compact('projects'));
    }

    public function datatable_ajax(Request $request)
    {
        $data = [];
        $date_range = $request->date_range;
        $project = $request->project;
        if ($date_range !== null) {
            $date_array = explode(" - ", $date_range);
            $from_date = date("Y-m-d", strtotime($date_array[0]));
            $to_date = date("Y-m-d", strtotime($date_array[1]));
        } else {
            $from_date = null;
            $to_date = null;
        }

        // $date_array = explode(" - ", $date_range);
        // $from_date = date("Y-m-d", strtotime($date_array[0]));
        // $to_date = date("Y-m-d", strtotime($date_array[1]));

        $marketer = Marketer::get();

        foreach ($marketer as $mar) {
            $query = Payment::with('plots')
                ->where('marketer_id', $mar->id)
                ->where('payment_status', 3);

            if ($from_date !== null && $to_date !== null) {
                $query->whereBetween('payment_date', [$from_date, $to_date]);
            }

            if ($project !== null) {
                $query->where('project_id', $project);
            }

            $payment = $query->get();
            // $payment = Payment::with('plots')->whereBetween('payment_date', [$from_date, $to_date])->where('project_id',$project)->where('marketer_id', $mar->id)->where('payment_status', 3)->get();
            $sum = 0;
            $sumsqft = 0;
            foreach ($payment as $p) {
                $sum = $sum + $p->plots->total_amount;
                $sumsqft = $sumsqft + $p->plots->plot_sqft;
            }
            if ($sum != 0) {
                $data[] = array('marketer_id' => $mar->marketer_vcity_id, "name" => $mar->name, "mobile_no" => $mar->mobile_no, "sold" => count($payment), "total_amount" => $sum, "total_sqft" => $sumsqft, "id" => $mar->id);
            }
        }

        array_multisort(array_column($data, "sold"), SORT_DESC, $data);
        if ($request->action == 'excel') {
            // dd($data);
            return Excel::download(new MarketerReportExport($data), 'marketer_progress.xlsx');
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return "<div class='btn-group'><a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData(" . $row["id"] . ")'><i title='view' class='fa fa-eye'></i></a></div>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $directors = Marketer::all();
        $maxNumber = Marketer::max('marketer_vcity_id');
        $nextNumber = $maxNumber ? ((int) substr($maxNumber, 2)) + 1 : 1;
        $marketer_vcity_id = "V" . str_pad($nextNumber, 5, "0", STR_PAD_LEFT);
        // $designations = [];
        // foreach ($directors as $director) {
        //     $designations[] = $director->designation;
        // }
        return view('pages.marketers.create', compact('directors', 'marketer_vcity_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'nullable|email',  //|unique:marketers,email
                'father_name' => 'nullable|min:3',
                'pincode' => 'nullable|numeric|digits:6',
                'mobile_no' => 'required|numeric|digits:10|unique:marketers,mobile_no',
                'aadhar_no' => 'nullable|numeric|digits:12|unique:marketers,aadhar_no',
                'pan_no' => 'nullable|unique:marketers,pan_no',
                'acc_no' => 'nullable|unique:marketers,acc_no',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $creationDate = Carbon::now();
                $renewalDate = $creationDate->addYear();
                $marketers = Marketer::create([
                    'name' => $request->name,
                    'designation' => $request->designation,
                    'email' => $request->email,
                    'father_name' => $request->father_name,
                    'qualification' => $request->qualification,
                    'date' => $request->date,
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'acc_no' => $request->acc_no,
                    'ifsc_code' => $request->ifsc_code,
                    'branch' => $request->branch,
                    'city' => $request->city,
                    'pincode' => $request->pincode,
                    'mobile_no' => $request->mobile_no,
                    'aadhar_no' => $request->aadhar_no,
                    'pan_no' => $request->pan_no,
                    'director_vcity_id' => $request->director_id,
                    'director' => $request->director,
                    'ad_vcity_id' => $request->ad_id,
                    'assistant_director' => $request->ad,
                    'crm_vcity_id' => $request->crm_id,
                    'crm' => $request->crm,
                    'chief_vcity_id' => $request->chief_director_id,
                    'chief_director' => $request->chief_director,
                    'senior_vcity_id' => $request->senior_director_id,
                    'senior_director' => $request->senior_director,
                    'renewal_date' => $renewalDate,
                ]);
                if ($marketers) {
                    $maxNumber = Marketer::max('marketer_vcity_id');
                    $nextNumber = $maxNumber ? ((int) substr($maxNumber, 2)) + 1 : 1;
                    $marketers->update(['marketer_vcity_id' => "V" . str_pad($nextNumber, 5, "0", STR_PAD_LEFT)]);
                    if ($request->designation != 'CC' && $request->designation != 'CRM') {
                        $director = new Director();
                        $director->name = $marketers->name;
                        $director->email = $marketers->email;
                        $director->director_id = $marketers->marketer_vcity_id;
                        $director->mobile_no = $marketers->mobile_no;
                        $director->designation = $marketers->designation;
                        $director->address = $marketers->address;
                        $director->marketer_id = $marketers->id;
                        $director->status = 1;
                        $director->save();
                    }
                }
                $response = [
                    'success' => 1,
                    'message' => 'Marketer Added Successfully!',
                ];
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $marketer = Marketer::with(['Director', 'assistantDirector', 'CRM', 'chiefDirector', 'seniorDirector'])->find($id);
        if ($marketer) {
            $payment = Payment::where('payment_status', config('constants.payment_status.full_payment'))->where('marketer_id', $marketer->id)->orderBy("payment_date", "DESC")->first();
            if (empty($payment)) {
                $marketer->days_ago = null;
            } else {
                $paymentDate = Carbon::parse($payment->payment_date);
                $currentDate = Carbon::now();
                $yearsDifference = $currentDate->diffInYears($paymentDate);
                $monthsDifference = $currentDate->diffInMonths($paymentDate);
                $daysDifference = $currentDate->diffInDays($paymentDate);
                if ($yearsDifference > 0) {
                    $marketer->days_ago = $yearsDifference == 1 ? $yearsDifference . ' year ago' : $yearsDifference . ' years ago';
                } elseif ($monthsDifference > 0) {
                    $marketer->days_ago = $monthsDifference == 1 ? $monthsDifference . ' month ago' : $monthsDifference . ' months ago';
                } elseif ($daysDifference > 0) {
                    $marketer->days_ago = $daysDifference == 1 ?  $daysDifference . ' day ago' : $daysDifference . ' days ago';
                } else {
                    $marketer->days_ago = null;
                }
            }
        }
        return view('pages.marketers.view', compact('marketer'));
    }

    public function marketerProgressShow(string $id, Request $request)
    {
        $date_range = $request->date_range;
        $project = $request->project;
        if ($date_range !== null) {
            $date_array = explode(" - ", $date_range);
            $from_date = date("Y-m-d", strtotime($date_array[0]));
            $to_date = date("Y-m-d", strtotime($date_array[1]));
        } else {
            $from_date = null;
            $to_date = null;
        }

        $query = Payment::where('marketer_id', $id)->where('payment_status', 3);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $marketer = Marketer::find($id);
        $marketers = Marketer::all();
        if ($marketer) {
            $payment = Payment::where('payment_status', config('constants.payment_status.full_payment'))->where('marketer_id', $marketer->id)->orderBy("payment_date", "DESC")->first();
            if (empty($payment)) {
                $marketer->days_ago = null;
            } else {
                $paymentDate = Carbon::parse($payment->payment_date);
                $currentDate = Carbon::now();
                $yearsDifference = $currentDate->diffInYears($paymentDate);
                $monthsDifference = $currentDate->diffInMonths($paymentDate);
                $daysDifference = $currentDate->diffInDays($paymentDate);
                if ($yearsDifference > 0) {
                    $marketer->days_ago = $yearsDifference == 1 ? $yearsDifference . ' year ago' : $yearsDifference . ' years ago';
                } elseif ($monthsDifference > 0) {
                    $marketer->days_ago = $monthsDifference == 1 ? $monthsDifference . ' month ago' : $monthsDifference . ' months ago';
                } elseif ($daysDifference > 0) {
                    $marketer->days_ago = $daysDifference == 1 ?  $daysDifference . ' day ago' : $daysDifference . ' days ago';
                } else {
                    $marketer->days_ago = null;
                }
            }
        }
        return view('pages.marketers.edit', compact('marketer', 'marketers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'nullable|email', //|unique:marketers,email,' . $id
                'father_name' => 'nullable|min:3',
                'pincode' => 'nullable|numeric|digits:6',
                'mobile_no' => 'required|numeric|digits:10|unique:marketers,mobile_no,' . $id,
                'aadhar_no' => 'nullable|numeric|digits:12|unique:marketers,aadhar_no,' . $id,
                'pan_no' => 'nullable|unique:marketers,pan_no,' . $id,
                'acc_no' => 'nullable|unique:marketers,acc_no,' . $id,
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $marketer = Marketer::find($id);
                $marketer->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'father_name' => $request->father_name,
                    'qualification' => $request->qualification,
                    'date' => $request->date,
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'acc_no' => $request->acc_no,
                    'ifsc_code' => $request->ifsc_code,
                    'branch' => $request->branch,
                    'city' => $request->city,
                    'pincode' => $request->pincode,
                    'mobile_no' => $request->mobile_no,
                    'aadhar_no' => $request->aadhar_no,
                    'pan_no' => $request->pan_no,
                    'director_vcity_id' => $request->director_id,
                    'director' => $request->director,
                    'ad_vcity_id' => $request->ad_id,
                    'assistant_director' => $request->ad,
                    'crm_vcity_id' => $request->crm_id,
                    'crm' => $request->crm,
                    'chief_vcity_id' => $request->chief_director_id,
                    'chief_director' => $request->chief_director,
                    'senior_vcity_id' => $request->senior_director_id,
                    'senior_director' => $request->senior_director,
                    'designation' => $request->designation,
                    'renewal_date' => $request->renewal_date,
                ]);
                if ($marketer) {
                    $director = Director::where('marketer_id', $id)->first();
                    if ($marketer->designation != 'CC' && $marketer->designation != 'CRM') {
                        if ($director) {
                            $director->name = $marketer->name;
                            $director->email = $marketer->email;
                            $director->director_id = $marketer->marketer_vcity_id;
                            $director->mobile_no = $marketer->mobile_no;
                            $director->designation = $marketer->designation;
                            $director->address = $marketer->address;
                            $director->marketer_id = $marketer->id;
                            $director->update();
                        }
                    }
                }
                $response = [
                    'success' => 1,
                    'message' => 'Marketer Updated Successfully!',
                ];
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $director = Director::where('marketer_id', $id)->first();
            $marketer = Marketer::where('id', $id)->first();
            $paymentExists = Payment::where('marketer_id', $id)->exists();
            if ($paymentExists) {
                $response = [
                    'success' => 0,
                    'message' => 'This Marketer cannot be deleted because, Payment exists for this marketer'
                ];
            } else {
                if ($director) {
                    $director->delete();
                }
                if ($marketer) {
                    $marketer->delete();
                }
                $response = [
                    'success' => 1,
                    'message' => 'Marketer deleted successfully.'
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
                $marketerImport = new MarketerImport;
                $marketerImport->import($request->file('file'));
                $error_message = [];
                $errors = $marketerImport->failures();

                if (count($errors) > 0) {
                    DB::rollback();
                    foreach ($errors as $key => $error) {
                        $error_message[] = $error->toArray();
                    }
                    $error_message = array_values(Arr::dot($error_message));
                    $response_data = ['message' => $error_message, 'success' => 0, 'type' => 1];
                } else {
                    $result = $marketerImport->getRowCount();
                    if ($result['success'] == 1) {
                        DB::commit();
                        $response_data = ['message' => 'Marketers Imported Successfully', 'success' => 1];
                    } else {
                        DB::rollback();
                        $response_data = ['message' => $result['messages'], 'success' => 0, 'type' => 1];
                    }
                }
            } catch (exception $e) {
                DB::rollback();
                // $error_message = [];
                // $errors = $e->failures();
                // if(count($errors) > 0)
                // {
                //     foreach ($errors as $key => $error) {
                //         $error_message[] = $error->toArray();
                //     }
                //     $error_message = array_values(Arr::dot($error_message));
                // }
                $response_data = ['message' => $e->getMessage(), 'success' => 0];
            }
        } else {
            $errors = array_values(Arr::dot($validator->errors()->toArray()));
            $response_data = ["success" => 0, "message" => $errors[0], "error" => $errors];
        }
        return response()->json($response_data);
    }


    public function fetchDirectorDetails(Request $request)
    {
        $directorDetails = Marketer::find($request->directorId);
        if (!$directorDetails) {
            return response()->json(['error' => 'Director not found'], 404);
        }
        $assistantDirector = $directorDetails->assistant_director != null ? $directorDetails->assistant_director : null;
        $director = $directorDetails->director != null ? $directorDetails->director : null;
        $seniorDirector = $directorDetails->senior_director != null ? $directorDetails->senior_director : null;
        $chiefDirector = $directorDetails->chief_director != null ? $directorDetails->chief_director : null;

        return response()->json([
            'assistantDirector' => $assistantDirector,
            'director' => $director,
            'seniorDirector' => $seniorDirector,
            'chiefDirector' => $chiefDirector,
        ]);
    }

    public function changeStatus(Request $request)
    {
        $marketer = Marketer::find($request->director_id);
        $director = Director::where('marketer_id', $request->director_id)->first();
        $name = $marketer->name;
        if ($marketer) {
            $marketer->status = $request->status;
            $marketer->save();
        }
        if ($director) {
            $director->status = $request->status;
            $director->save();
        }
        return response()->json(['success' => 1, 'message' => 'Director Status Updated Successfully.']);
    }

    public function hierarchy()
    {
        $directors = Marketer::all();
        return view('pages.marketers.hierarchy', compact('directors'));
    }

    public function fetchHierarchy(Request $request)
    {
        $directorDetails = Marketer::find($request->directorId);
        if (!$directorDetails) {
            return response()->json(['error' => 'Director not found'], 404);
        }
        $crmName = Marketer::where('id', $directorDetails->crm)->first();
        $adName = Marketer::where('id', $directorDetails->assistant_director)->first();
        $dirName = Marketer::where('id', $directorDetails->director)->first();
        $sdName = Marketer::where('id', $directorDetails->senior_director)->first();
        $cdName = Marketer::where('id', $directorDetails->chief_director)->first();
        $assignedTo = [];
        switch ($directorDetails->designation) {
            case 'CRM':
                $assignedTo = Marketer::where('crm', $directorDetails->id)->get();
                break;
            case 'AD':
                $assignedTo = Marketer::where('assistant_director', $directorDetails->id)->get();
                break;
            case 'Dir':
                $assignedTo = Marketer::where('director', $directorDetails->id)->get();
                break;
            case 'SD':
                $assignedTo = Marketer::where('senior_director', $directorDetails->id)->get();
                break;
            case 'CD':
                $assignedTo = Marketer::where('chief_director', $directorDetails->id)->get();
                break;
        }
        return response()->json([
            'crmName' => $crmName,
            'adName' => $adName,
            'dirName' => $dirName,
            'sdName' => $sdName,
            'cdName' => $cdName,
            'assignedTo' => $assignedTo,
        ]);
    }

}
