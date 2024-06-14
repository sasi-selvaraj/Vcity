<?php

namespace App\Http\Controllers;

use App\DataTables\VisitorsDataTable;
use App\Helpers\Helpers;
use App\Models\Director;
use App\Models\Marketer;
use App\Models\Plot;
use App\Models\Project;
use App\Models\Visitor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function index(VisitorsDataTable $dataTable)
    {
        $projects = Project::get();
        $plots = Plot::get();
        $directors = Director::get();
        $marketers = Marketer::get();
        return $dataTable->render('pages.visitors.index', compact('projects', 'plots', 'directors', 'marketers'));
    }

    public function show($id)
    {
        $visitor = Visitor::with('project', 'plot', 'director', 'marketer')->find($id);
        return response()->json(['visitor' => $visitor]);
    }

    public function destroy($id)
    {
        try {
            $visitor = Visitor::where('id', $id)->first();
            $visitor->delete();
            $response = [
                'success' => 1,
                'message' => 'Visitor has been deleted successfully.'
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
}
