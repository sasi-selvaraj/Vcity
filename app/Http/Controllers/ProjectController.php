<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectsDataTable;
use App\Helpers\Helpers;
use App\Imports\ProjectImport;
use App\Models\Payment;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(ProjectsDataTable $dataTable)
    {
        return $dataTable->render('pages.projects.index');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_name' => 'required|string|min:3',
                'total_blocks' => 'nullable|numeric|max_digits:2',
                'total_no_of_sqft' => 'required|numeric|max:999999999999',
                'total_plots' => 'required|numeric|max_digits:6',
                'project_image' => 'nullable|image|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $existingProject = Project::where('project_name', $request->project_name)->first();
                if ($existingProject) {
                    $response = [
                        'success' => 0,
                        'message' => 'Project with name "' . $request->project_name . '" already exists.',
                    ];
                } else {
                    $projects = Project::create([
                        'project_name' => $request->project_name,
                        'project_location' => $request->project_location,
                        'total_blocks' => $request->total_blocks,
                        'total_no_of_sqft' => $request->total_no_of_sqft,
                        'total_plots' => $request->total_plots,
                        'project_description' => $request->project_description,
                    ]);
                    if ($projects) {
                        $projects->update(['project_number' => "PROJ" . $projects->id]);
                    }
                    if ($request->hasFile('project_image')) {
                        $param = (object) [
                            'file' => $request->file('project_image'),
                            'folder_location' => 'project_image',
                        ];

                        $fileDetails = Helpers::fileUpload($param);
                        if ($fileDetails) {
                            $filePath = $fileDetails['path'];
                            $projectImage = $projects->projectImage()->create([
                                'project_id' => $projects->id,
                                'path' => $filePath,
                                'name' => $fileDetails['orginal_name'],
                                'type' => $fileDetails['extension'],
                                'size' => $fileDetails['size'],
                            ]);
                        }
                    }
                    $response = [
                        'success' => 1,
                        'message' => 'Project Added Successfully!',
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
        $project = Project::with('plots', 'projectImage')->find($id);
        return view('pages.projects.view', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('projectImage')->find($id);
        return response()->json(['project' => $project]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_name' => 'required|string|min:3',
                'total_blocks' => 'nullable|numeric|max_digits:2',
                'total_no_of_sqft' => 'required|numeric|max:9999999999',
                'total_plots' => 'required|numeric|max_digits:6',
                'project_image' => 'nullable|image|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => 0,
                    'message' => $validator->errors(),
                    'errors' => $validator->errors()
                ];
            } else {
                $project = Project::where('id', $id)->first();
                $existingProject = Project::where('project_name', $request->project_name)->where('id', '!=', $id)->first();
                if ($existingProject) {
                    $response = [
                        'success' => 0,
                        'message' => 'Project with name "' . $request->project_name . '" already exists.',
                    ];
                } else {
                    $project->update([
                        'project_name' => $request->project_name,
                        'project_location' => $request->project_location,
                        'total_blocks' => $request->total_blocks,
                        'total_no_of_sqft' => $request->total_no_of_sqft,
                        'total_plots' => $request->total_plots,
                        'project_description' => $request->project_description,
                    ]);
                    if ($request->hasFile('project_image')) {
                        $existingFile = $project->projectImage()->pluck('path');
                        if ($existingFile) {
                            $filePath = storage_path('app/public/' . $existingFile);
                            if (file_exists($filePath)) {
                                Storage::disk('public')->delete($existingFile);
                            }
                        }

                        $project->projectImage()->delete();
                        $param = (object) [
                            'file' => $request->file('project_image'),
                            'folder_location' => 'project_image',
                        ];

                        $fileDetails = Helpers::fileUpload($param);
                        if ($fileDetails) {
                            $filePath = $fileDetails['path'];
                            $projectImage = $project->projectImage()->create([
                                'project_id' => $project->id,
                                'path' => $filePath,
                                'name' => $fileDetails['orginal_name'],
                                'type' => $fileDetails['extension'],
                                'size' => $fileDetails['size'],
                            ]);
                        }
                    }
                    $response = [
                        'success' => 1,
                        'message' => 'Project updated successfully.'
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

    public function destroy($id)
    {
        try {
            $project = Project::where('id', $id)->first();
            $paymentExists = Payment::where('project_id', $id)->exists();
            if ($paymentExists) {
                $response = [
                    'success' => 0,
                    'message' => 'This Project cannot be deleted because, Payment exists for this project'
                ];
            } else {
                $project->delete();
                $response = [
                    'success' => 1,
                    'message' => 'Project "' . $project->project_name . '" deleted successfully.'
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
            // dd(1);
            DB::beginTransaction();
            try {
                $projectImport = new ProjectImport;
                $projectImport->import($request->file('file'));
                $error_message = [];
                $errors = $projectImport->failures();

                if (count($errors) > 0) {
                    DB::rollback();
                    foreach ($errors as $key => $error) {
                        $error_message[] = $error->toArray();
                    }
                    $error_message = array_values(Arr::dot($error_message));
                    $response_data = ['message' => $error_message, 'success' => 0, 'type' => 1];
                } else {
                    $result = $projectImport->getRowCount();
                    if ($result['success'] == 1) {
                        DB::commit();
                        $response_data = ['message' => 'Projects Imported Successfully', 'success' => 1];
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
                $response_data = ['message' => 'Something went wrong', 'success' => 0];
            }
        } else {
            // dd(2);
            $errors = array_values(Arr::dot($validator->errors()->toArray()));
            $response_data = ["success" => 0, "message" => $errors[0], "error" => $errors];
        }
        return response()->json($response_data);
    }
}
