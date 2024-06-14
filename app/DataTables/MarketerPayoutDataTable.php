<?php

namespace App\DataTables;

use App\Models\MarketerPayout;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MarketerPayoutDataTable extends DataTable
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
            ->editColumn('project_id', function ($data) {
                return $data->project->project_name;
            })
            ->editColumn('plot_id', function ($data) {
                return $data->plot->plot_no;
            })
            ->editColumn('marketer_id', function ($data) {
                return $data->marketer->name;
            })
            ->editColumn('marketer_vcity_id', function ($data) {
                return $data->marketer->marketer_vcity_id;
            })
            ->filterColumn('marketer_id', function ($query, $keyword) {
                $query->whereHas('marketer', function ($subquery) use ($keyword) {
                    $subquery->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('marketer_vcity_id', function ($query, $keyword) {
                $query->whereHas('marketer', function ($subquery) use ($keyword) {
                    $subquery->where('marketer_vcity_id', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('plot_id', function ($query, $keyword) {
                $query->whereHas('plot', function ($subquery) use ($keyword) {
                    $subquery->where('plot_no', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('project_id', function ($query, $keyword) {
                $query->whereHas('project', function ($subquery) use ($keyword) {
                    $subquery->where('project_name', 'LIKE', "%{$keyword}%");
                });
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MarketerPayout $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('marketerpayout-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('marketer_id')->title('Marketer Name'),
            Column::make('marketer_vcity_id')->title('Marketer ID'),
            Column::make('project_id')->title('Project'),
            Column::make('plot_id')->title('Plot No'),
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
        return 'MarketerPayout_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        return "<div class='btn-group'>" . "<a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData($data->id)'><i title='view' class='fa fa-eye'></i></a>" .
            "<a href='javascript:void(0)'  class='text-warning btn-sm mb-1' onclick='editData($data->id)'><i title='Edit' class='fa fa-edit'></i></a>" .
            "<a href='javascript:void(0)' class='text-danger btn-sm' onclick='deleteData($data->id)'><i title='Delete' class='fa fa-trash'></i></a>" . 
            "<a href='" . url('/payouts/download_receipt/' . $data->id) . "' class='text-info btn-sm' target='_blank'><i title='PDF' class='fa fa-file-pdf-o'></i></a>" . "</div>";
    }
}
