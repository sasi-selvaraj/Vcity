<?php

namespace App\Http\Controllers;

use App\DataTables\DirectorsDataTable;
use App\Mail\DirectorsMail;
use App\Mail\DirectorsStatusMail;
use App\Models\Director;
use App\Models\Marketer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DirectorController extends Controller
{
    public function index(DirectorsDataTable $dataTable)
    {
        return $dataTable->render('pages.directors.index');
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',
    //             'email' => 'required|email|unique:directors,email',
    //         ]);
    //         if ($validator->fails()) {
    //             $response = [
    //                 'success' => 0,
    //                 'message' => $validator->errors(),
    //                 'errors' => $validator->errors()
    //             ];
    //         } else {
    //             $password = Str::password(8);
    //             $directors = Director::create([
    //                 'name' => $request->name,
    //                 'director_id' => $request->director_vcity_id,
    //                 'mobile_no' => $request->mobile_no,
    //                 'designation' => $request->designation ?? 'Director',
    //                 'email' => $request->email,
    //                 'password' => $password,
    //                 'address' => $request->address,
    //                 'status' => 1,
    //             ]);
    //             $mailData = [
    //                 'name' => $request->name,
    //                 'password' => $password,
    //                 'email' => $request->email
    //             ];

    //             $response = [
    //                 'success' => 1,
    //                 'message' => 'Director Added Successfully!',
    //             ];
    //         }
    //     } catch (Exception $e) {
    //         $response = [
    //             'success' => 0,
    //             'message' => 'Unknown error',
    //             'error' => $e->getMessage()
    //         ];
    //     }
    //     return response()->json($response);
    // }


    public function show($id)
    {
        $director = Director::find($id);
        return response()->json(['director' => $director]);
    }

    public function edit($id)
    {
        $director = Director::find($id);
        return response()->json(['director' => $director]);
    }

    public function profile()
    {
        return view('pages.directors.profile');
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:directors,email,' . $id,
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $directors = Director::where('id', $id)->first();
                $directors->update([
                    'name' => $request->name,
                    'director_id' => $request->director_vcity_id,
                    'mobile_no' => $request->mobile_no,
                    'designation' => $request->designation ?? 'Director',
                    'email' => $request->email,
                    'address' => $request->address,
                ]);
                if ($directors) {
                    $marketer = Marketer::where('id', $directors->marketer_id)->first();
                    $marketer->update([
                        'name' => $request->name,
                        'marketer_vcity_id' => $request->director_vcity_id,
                        'mobile_no' => $request->mobile_no,
                        'designation' => $request->designation ?? 'Director',
                        'email' => $request->email,
                        'address' => $request->address,
                    ]);
                }
                $response = [
                    'success' => 1,
                    'message' => 'Director updated successfully.'
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

    public function destroy($id)
    {
        try {
            $director = Director::with('marketer')->where('id', $id)->first();
            if ($director) {
                $director->delete();
            }
            // $marketer = Marketer::where('id', $director->marketer_id);
            // if ($marketer) {
            //     $marketer->delete();
            // }
            $response = [
                'success' => 1,
                'message' => 'Director has been deleted successfully.'
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

    // Change status
    public function changeStatus(Request $request)
    {
        $director = Director::with('marketer')->find($request->director_id);
        if ($director) {
            $director->status = $request->status;
            $director->save();
            $marketer = Marketer::find($director->marketer_id);
            if ($marketer) {
                $marketer->status = $request->status;
                $marketer->save();
            }
        }
        $name = $director->name;
        return response()->json(['success' => 1, 'message' => 'Director Status Updated Successfully.']);
    }
}
