<?php

namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProjectImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
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
        $existingProject = Project::where('project_name', $row['project_name'])->first();

        if ($existingProject) {
            $this->row['success'] = 0;
            $this->row['messages'][] = 'Project with name "' . $row['project_name'] . '" already exists.';
            return;
        }
        $projects = new Project();
        $projects->project_name = isset($row['project_name']) ? $row['project_name'] : '';
        $projects->project_location = isset($row['project_location']) ? $row['project_location'] : '';
        $projects->total_blocks = isset($row['total_no_of_blocks']) ? $row['total_no_of_blocks'] : '';
        $projects->total_no_of_sqft = isset($row['total_no_of_sqft']) ? $row['total_no_of_sqft'] : '';
        $projects->total_plots = isset($row['total_plots']) ?  $row['total_plots'] : '';
        $projects->project_description = isset($row['project_description']) ? $row['project_description'] : '';
        $projects->save();
        if ($projects->id) {
            $projects->project_number = "PROJ" . $projects->id;
            $projects->update();
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            'project_name' => 'required|string|min:3',
            'total_no_of_blocks' => 'nullable|numeric|max_digits:2',
            'total_no_of_sqft' => 'required|numeric|max:999999999999',
            'total_plots' => 'required|numeric|max_digits:6',
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
