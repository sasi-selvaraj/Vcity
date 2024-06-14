<?php

use App\Helpers\Helpers;
use App\Mail\OTPVerification;
use App\Models\CustomerBooking;
use App\Models\Director;
use App\Models\Visitor;
use App\Models\Marketer;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Login
Route::post('login', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        $response = [
            'success' => 0,
            'msg' => $validator->errors(),
        ];
    } else {
        $director = Director::where('email', $request->email)->first();
        if (!$director) {
            $response = [
                'success' => 0,
                'msg' => 'The provided email does not match our records.'
            ];
        } else {
            $directorStatus = Director::where('email', $request->email)->where('status', 1)->first();
            if (!$directorStatus) {
                $response = [
                    'success' => 0,
                    'msg' => 'Your account has been deactivated by Admin.',
                    'statusCode' => 401,
                ];
            } else {
                $director->otp = rand(123456, 999999);
                $director->save();
                // Mail::to($request->email)->send(new OTPVerification($director));
                $response = [
                    'success' => 1,
                    'msg' => 'The OTP sent to your email',
                ];
            }
        }
    }
    return response()->json($response);
});

//OTP Verification and token generation
Route::post('/tokens/create', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'otp' => 'required|numeric',
    ]);

    if ($validator->fails()) {
        $response = [
            'success' => 0,
            'msg' => $validator->errors(),
        ];
    } else {
        $directorStatus = Director::where('email', $request->email)->where('status', 1)->first();
        if (!$directorStatus) {
            $response = [
                'success' => 0,
                'msg' => 'Your account has been deactivated by Admin.',
                'statusCode' => 401,
            ];
        } else {
            $director = Director::where('email', $request->email)->where('otp', $request->otp)->first();
            if (!$director) {
                $response = [
                    'success' => 0,
                    'msg' => 'The OTP is incorrect.',
                ];
            } else {
                $director->otp_verified = now();
                $director->tokens()->where('tokenable_id', $director->id)->delete();
                $token = $director->createToken($director->name)->plainTextToken;
                $response = [
                    'success' => 1,
                    'msg' => 'Signed in successful',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'data' => $director,
                ];
            }
        }
    }
    return response()->json($response);
});

Route::middleware(['auth:sanctum'])->group(function () {
    //Marketers List
    Route::get('/marketers', function () {
        $directorStatus = auth()->user()->status;
        if ($directorStatus != 1) {
            $response = [
                'success' => 0,
                'msg' => 'Your account has been deactivated by Admin.',
                'statusCode' => 401,
            ];
        } else {
            $marketers = Marketer::all();
            if (isset ($marketers) && count($marketers)) {
                $response = [
                    'success' => 1,
                    'msg' => 'Marketers retrieved successfully',
                    'data' => $marketers,
                ];
            } else {
                $response = [
                    'success' => 0,
                    'msg' => 'No record found',
                    'data' => $marketers,
                ];
            }
        }
        return response()->json($response);
    });
    //Projects List
    Route::get('/projects', function () {
        $directorStatus = auth()->user()->status;
        if ($directorStatus != 1) {
            $response = [
                'success' => 0,
                'msg' => 'Your account has been deactivated by Admin.',
                'statusCode' => 401,
            ];
        } else {
            // $projects = Project::with('projectImage')->get();
            $projects = DB::select('SELECT projects.*, project_images.path FROM projects LEFT JOIN project_images ON projects.id = project_images.project_id');
            if (isset ($projects) && count($projects)) {
                $response = [
                    'success' => 1,
                    'msg' => 'Projects retrieved successfully',
                    'data' => $projects,
                ];
            } else {
                $response = [
                    'success' => 0,
                    'msg' => 'No record found',
                    'data' => $projects,
                ];
            }
        }
        return response()->json($response);
    });
    //Plots List
    Route::post('/plots', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 0,
                'msg' => $validator->errors(),
            ];
        } else {
            $directorStatus = auth()->user()->status;
            if ($directorStatus != 1) {
                $response = [
                    'success' => 0,
                    'msg' => 'Your account has been deactivated by Admin.',
                    'statusCode' => 401,
                ];
            } else {
                $plots = Plot::where('project_id', $request->project_id)->get();
                $IST = new \DateTimeZone('Asia/Kolkata');
                foreach ($plots as $p) {
                    $payment = Payment::where('payment_status', 1)->where('plot_id', $p->id)->first();
                    $payment2 = Payment::where('payment_status', 2)->where('plot_id', $p->id)->first();
                    // dd($p->id,$payment2);
                    if (empty ($payment) && empty ($payment2)) {
                        $p->days_left = null;
                    } else {
                        if ($payment) {
                            $checkOtherPayments = Payment::where('plot_id', $payment->plot_id)
                                ->where('payment_status', '!=', 1)
                                ->first();

                            if (empty ($checkOtherPayments) || !isset ($checkOtherPayments)) {
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
                            if (empty ($checkOtherPayments) || !isset ($checkOtherPayments)) {
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
                                } elseif ($daysDifference > 30) {
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
                if (!$plots) {
                    $response = [
                        'success' => 0,
                        'msg' => 'No record found',
                    ];
                } else {
                    $response = [
                        'success' => 1,
                        'data' => $plots,
                        'msg' => 'Plots retrieved successfully',
                    ];
                }
            }
        }
        return response()->json($response);
    });
    //Plot Details
    Route::post('/plot-details', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'plot_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 0,
                'msg' => $validator->errors(),
            ];
        } else {
            $directorStatus = auth()->user()->status;
            if ($directorStatus != 1) {
                $response = [
                    'success' => 0,
                    'msg' => 'Your account has been deactivated by Admin.',
                    'statusCode' => 401,
                ];
            } else {
                $plots = Plot::find($request->plot_id);
                if (!$plots) {
                    $response = [
                        'success' => 0,
                        'msg' => 'No record found',
                    ];
                } else {
                    $response = [
                        'success' => 1,
                        'data' => $plots,
                        'msg' => 'Plot details retrieved successfully',
                    ];
                }
            }
        }
        return response()->json($response);
    });
    //Add Gate pass
    Route::post('/gate-pass', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'customer_name' => 'required',
            'marketer_id' => 'required',
            'mobile_no' => 'required',
            'date' => 'required',
            'file' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => 0,
                'msg' => $validator->errors(),
            ];
        } else {
            $directorStatus = auth()->user()->status;
            if ($directorStatus != 1) {
                $response = [
                    'success' => 0,
                    'msg' => 'Your account has been deactivated by Admin.',
                    'statusCode' => 401,
                ];
            } else {
                $request['director_id'] = Auth::user()->id;
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $param = (object) [
                        'file' => $file,
                        'folder_location' => 'gatePass',
                    ];

                    $fileDetails = Helpers::fileUpload($param);
                    if ($fileDetails) {
                        $filePath = $fileDetails['path'];
                        $request['image'] = $filePath;
                    }
                }

                $booking = Visitor::create($request->all());
                if ($booking) {
                    $response = [
                        'success' => 1,
                        'msg' => 'Plot booked successfully',
                        'data' => $booking,
                    ];
                }
            }
        }
        return response()->json($response);
    });


    Route::get('/gate-pass/list', function () {
        $directorStatus = auth()->user()->status;
        if ($directorStatus != 1) {
            $response = [
                'success' => 0,
                'msg' => 'Your account has been deactivated by Admin.',
                'statusCode' => 401,
            ];
        } else {
            $gatePass = Visitor::select('gate_pass.*', 'projects.project_name', 'plots.plot_no', 'marketers.name')->leftJoin('projects', 'gate_pass.project_id', '=', 'projects.id')->leftJoin('plots', 'gate_pass.plot_id', '=', 'plots.id')->leftJoin('marketers', 'gate_pass.marketer_id', '=', 'marketers.id')->where('gate_pass.director_id', auth()->user()->id)->get();
            if (!$gatePass) {
                $response = [
                    'success' => 0,
                    'msg' => 'No data found',
                ];
            } else {
                $response = [
                    'success' => 1,
                    'data' => $gatePass,
                    'msg' => 'GatePass retrieved successfully',
                ];
            }
        }
        return response()->json($response);
    });

    Route::post('/plots/pdf', function (Request $request) {
        $directorStatus = auth()->user()->status;
        if ($directorStatus != 1) {
            $response = [
                'success' => 0,
                'msg' => 'Your account has been deactivated by Admin.',
                'statusCode' => 401,
            ];
        } else {
            $query = Plot::with('project')->where('project_id', $request->project_id);
            // dd($data);
            if ($request->has('status') && !empty ($request->status)) {
                $query->where('status', $request->status);
            }
            $data = $query->get();
            $pdf = Pdf::loadView('includes.plots_template', compact ('data'));
            $filename = 'plots_view.pdf';
            if (Storage::disk('public')->exists('pdf/' . $filename)) {
                Storage::disk('public')->delete('pdf/' . $filename);
            }
            Storage::disk('public')->put('pdf/' . $filename, $pdf->output());
            $filePath = url('storage/pdf/' . $filename);
            return response()->json([
                'success' => 1,
                'path' => $filePath,
                'msg' => 'PDF for plot view retrieved successfully',
            ]);
        }
    });

    Route::post('/plot-book', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'plot_id' => 'required',
            'customer_name' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => 0,
                'msg' => $validator->errors(),
            ];
        } else {
            $directorStatus = auth()->user()->status;
            if ($directorStatus != 1) {
                $response = [
                    'success' => 0,
                    'msg' => 'Your account has been deactivated by Admin.',
                    'statusCode' => 401,
                ];
            } else {
                if (CustomerBooking::where('project_id', $request['project_id'])->where('plot_id', $request['plot_id'])->first()) {
                    $response = [
                        'success' => 1,
                        'msg' => 'Already Plot booked',
                    ];
                } else {
                    $request['director_id'] = Auth::user()->id;
                    if ($booking = CustomerBooking::create($request->all())) {
                        $response = [
                            'success' => 1,
                            'msg' => 'Plot booked successfully',
                            'data' => $booking,
                        ];
                    }
                }
            }
        }
        return response()->json($response);
    });
});
