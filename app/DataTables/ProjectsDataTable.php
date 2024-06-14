<?php

namespace App\DataTables;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Livewire\Attributes\Title;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProjectsDataTable extends DataTable
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
            // ->order(function ($query) {
            //     $query->orderBy('id', 'desc');
            // })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Project $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('projects-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('<"row"<"col-12"B><"col-6"l><"col-6"f>>rt<"row"<"col-4"i><"col-8 justify-content-end d-flex"p>>')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('project_name')->width(60),
            Column::make('project_location')->visible(false),
            Column::make('total_no_of_sqft')->width(60),
            Column::make('total_plots')->width(60),
            Column::make('total_blocks')->title('Total No. of Blocks')->visible(false),
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
        return 'Projects_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        return "<div class='btn-group'>" . "<a href='" . url('projects/' . $data->id) . "' class='text-success btn-sm mb-1'><i title='view' class='fa fa-eye'></i></a>" .
            "<a href='javascript:void(0)'  class='text-warning btn-sm mb-1' onclick='editData($data->id)'><i title='Edit' class='fa fa-edit'></i></a>" .
            "<a href='javascript:void(0)' class='text-danger btn-sm' onclick='deleteData(" . $data->id . ", \"" . $data->project_name . "\")'><i title='Delete' class='fa fa-trash'></i></a>" . "</div>";
    }
}
