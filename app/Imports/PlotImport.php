<?php

namespace App\Imports;

use App\Models\Plot;
use App\Models\Project;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PlotImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
{
    /**
     * @param Collection $collection
     */
    use  Importable, SkipsFailures;

    private $errors = [];
    public function __construct()
    {
        $this->row['row'] = 1;
        $this->row['messages'] = [];
        $this->row['success'] = 1;
    }

    public function model(array  $row)
    {
        $this->row['row'] = $this->row['row'] + 1;
        $project = Project::where('project_name', $row['project_name'])->first();
        if ($project) {
            $existingPlot = Plot::where('project_id', $project->id)
                ->where('plot_no', $row['plot_no'])
                ->first();
            if ($existingPlot) {
                $this->row['success'] = 0;
                $this->row['messages'][] = 'Plot "' . $row['plot_no'] . '" with project"' . $row['project_name'] . '" already exists.';
                return;
            }
            if ($row['plot_status'] == 'Hold') {
                $statusUpdatedAt = now()->timezone('Asia/Kolkata');
            }
            $glvTotal = $row['plot_sqft'] * $row['gv_rate'];
            $devTotal = $row['plot_sqft'] * $row['dev_rate'];
            $totalAmount = $glvTotal + $devTotal;
            $plots = new Plot();
            $plots->project_id = $project->id;
            $plots->plot_no = isset($row['plot_no']) ? $row['plot_no'] : '';
            $plots->block = isset($row['block']) ? $row['block'] : '';
            $plots->facing = isset($row['facing']) ? $row['facing'] : '';
            $plots->plot_sqft = isset($row['plot_sqft']) ? $row['plot_sqft'] : '';
            $plots->location = isset($row['location']) ? $row['location'] : '';
            $plots->glv_rate = isset($row['gv_rate']) ? $row['gv_rate'] : '';
            $plots->glv = isset($row['guide_line_value']) && is_numeric($row['guide_line_value']) ? $row['guide_line_value'] : $glvTotal;
            $plots->dev_rate = isset($row['dev_rate']) ? $row['dev_rate'] : '';
            $plots->development_charges = isset($row['development_charges']) && is_numeric($row['development_charges']) ? $row['development_charges'] : $devTotal;
            $plots->gst = isset($row['gst']) ? $row['gst'] : '';
            $plots->total_amount = (isset($row['total_amount']) && is_numeric([$row['total_amount']])) ? $row['total_amount'] : $totalAmount;
            $plots->balance_amount = isset($row['total_amount']) && is_numeric([$row['total_amount']]) ? $row['total_amount'] : $totalAmount;
            $plots->status = (isset($row['plot_status']) &&  !empty($row['plot_status'])) ?  $row['plot_status'] : 'Available';
            $plots->status_updated_at = $statusUpdatedAt ?? null;
            $plots->description = isset($row['description']) ? $row['description'] : '';
            // dump($plots);
            $plots->save();
        }
        else{
            $this->row['success'] = 0;
            $this->row['messages'][] = 'Project ' .$row['project_name']. ' not found';
            return;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            'project_name' => 'required',
            'plot_no' => 'required',
            'block' => 'required|string',
            // 'plot_sqft' => 'nullable|numeric|max:999999999999',
            // 'sqft_rate' => 'nullable|numeric|max:999999999999',
            // 'total_amount' => 'nullable|numeric|max:999999999999',
            // 'status' => 'required',
        ];
    }

    public function getRowCount(): array
    {
        return $this->row;
    }

    function isArrayOfIntegers($arr)
    {
        // Create a Laravel Collection from the array
        $collection = collect($arr);

        // Use the every() method to check if every element is an integer
        return $collection->every(function ($item) {
            return is_int($item);
        });
    }
}
