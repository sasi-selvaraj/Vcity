<?php

namespace App\DataTables;

use App\Models\Plot;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PlotsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                return $this->getActionColumn($data);
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d-m-Y');
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at->format('d-m-Y');
            })
            ->editColumn('project_id', function ($data) {
                return $data->project->project_name;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == "Available") {
                    return "<span class='text-gradient text-success text-bold'>Available</span>";
                } elseif ($data->status == "Hold") {
                    return "<span class='text-gradient text-danger text-bold'>Hold</span>";
                } elseif ($data->status == "Permanent Hold") {
                    return "<span class='text-gradient text-purple text-bold'>Permanent Hold</span>";
                } elseif ($data->status == "Temporary Booking") {
                    return "<span class='text-gradient text-green text-bold'>Temporary Booking</span>";
                } elseif ($data->status == "Booking") {
                    return "<span class='text-gradient text-primary text-bold'>Booking</span>";
                } elseif ($data->status == "Full Payment") {
                    return "<span class='text-gradient text-warning text-bold'>Full Payment</span>";
                } elseif ($data->status == "Registered") {
                    return "<span class='text-gradient text-info text-bold'>Registered</span>";
                }
            })
            ->escapeColumns([])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Plot $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->leftJoin('projects', 'plots.project_id', '=', 'projects.id')
            ->select('plots.*', 'projects.project_name as project_name')
            ->orderBy('projects.project_name', 'ASC');

        // Filter by project if project id is provided in the request
        if ($projectId = request()->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('plots-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"row"<"col-12"B><"col-6"l><"col-6"f>>rt<"row"<"col-4"i><"col-8 justify-content-end d-flex"p>>')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
            ])
            ->parameters([
                'initComplete' => 'function () {
                    this.api().columns([0]).every(function () {
                        var column = this;
                        var select = $("<select style=\"color: #758395;font-weight:700; font-size: 17px; border:2px solid #c3cce0; border-radius:5px; width: 300px;\"><option value=\"\">Filter by Project</option></select>")
                            .appendTo($(column.header()).empty())
                            .on(\'change\', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? \'\\\\b\' + val + \'\\\\b\' : \'\', true, false).draw();
                            });
                        ' . $this->getProjectFilterOptions() . '
                    });
                }',
            ]);
    }

    /**
     * Get options for the project filter dropdown.
     */
    protected function getProjectFilterOptions(): string
    {
        $options = '';

        foreach ($this->getProjects() as $id => $name) {
            $options .= "select.append('<option value=\"$id\">$name</option>');";
        }

        return $options;
    }

    /**
     * Get projects for the filter dropdown.
     */
    protected function getProjects(): array
    {
        return Project::orderBy('project_name', 'ASC')->pluck('project_name', 'id')->toArray();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('project_id')->title('Project Name'),
            Column::make('plot_no'),
            Column::make('block'),
            Column::make('facing')->visible(false),
            Column::make('plot_sqft'),
            Column::make('location')->visible(false),
            Column::make('status')->title('Plot Status'),
            Column::make('status_updated_at')->visible(false),
            Column::make('development_charges')->visible(false),
            Column::make('dev_rate')->visible(false),
            Column::make('glv')->title('Guide Line Value')->visible(false),
            Column::make('glv_rate')->title('GV Rate')->visible(false),
            Column::make('gst')->title('GST %')->visible(false),
            Column::make('total_amount')->visible(false),
            Column::make('description')->visible(false),
            Column::make('created_at')->visible(false),
            Column::make('updated_at')->visible(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Plots_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        $editIcon = $data->status == "Registered" ? "<a href='javascript:void(0)' class='text-mute btn-sm mb-1 pe-none'><i title='Registered' class='fa fa-check'></i></a>" : "<a href='javascript:void(0)' class='text-warning btn-sm mb-1' onclick='editData($data->id)'><i title='Edit' class='fa fa-edit'></i></a>";
        return "<div class='btn-group'>" .
            "<a href='" . url('plots/' . $data->id) . "' class='text-success btn-sm mb-1'><i title='View' class='fa fa-eye'></i></a>" .
            $editIcon .
            "<a href='javascript:void(0)' class='text-danger btn-sm' onclick='deleteData(" . $data->id . ", \"" . $data->plot_no . "\", \"" . $data->project->project_name . "\")'><i title='Delete' class='fa fa-trash'></i></a>" .
            "</div>";
    }
}
