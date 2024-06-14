<?php

namespace App\DataTables;

use App\Models\Director;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DirectorsDataTable extends DataTable
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
            ->editColumn('created_at', function ($query) {
                return DATE_FORMAT($query->created_at, 'Y-m-d');
            })
            ->editColumn('updated_at', function ($query) {
                return DATE_FORMAT($query->updated_at, 'Y-m-d');
            })
            ->editColumn('status', function ($data) {
                $ActiveStatus=$data->status?'Active':'Inactive';
                $ActiveClass=$data->status?'success':'danger';
                 return "<a href='javascript:void(0);'><button class='btn btn-sm btn-$ActiveClass verify_status w-100' data-status='$data->status' data-director_id='$data->id'>$ActiveStatus</button></a>";
            })
            ->escapeColumns([])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Director $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('directors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
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
            Column::make('name')->title('name'),
            Column::make('director_id')->title('Director ID'),
            Column::make('email')->title('email'),
            Column::make('designation')->title('designation'),
            Column::make('status'),
            Column::make('created_at')->visible(false),
            // Column::make('updated_at'),
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
        return 'Directors_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        return "<div class='btn-group'>" . "<a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData($data->id)'><i title='view' class='fa fa-eye'></i></a>" .
            "<a href='javascript:void(0)'  class='text-warning btn-sm mb-1' onclick='editData($data->id)'><i title='Edit' class='fa fa-edit'></i></a>" .
            "<a href='javascript:void(0)' class='text-danger btn-sm' onclick='deleteData($data->id)'><i title='Delete' class='fa fa-trash'></i></a>" . "</div>";
    }
}
