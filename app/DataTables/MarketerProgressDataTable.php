<?php

namespace App\DataTables;

use App\Models\Marketer;
use App\Models\Payment;
use App\Models\Plot;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MarketerProgressDataTable extends DataTable
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
            ->addColumn('plots_sold', function ($data) {
                $payments = Payment::where('marketer_id', $data->id)->pluck('plot_id')->unique();
                $totalSqft = Plot::whereIn('id', $payments)->sum('plot_sqft');
                if ($totalSqft >= 33000) {
                    return '<span class="text-gradient text-red">' . $payments->count() . '</span>';
                }
                return $payments->count();
            })
            ->addColumn('total_sqft_sold', function ($data) {
                $payments = Payment::where('marketer_id', $data->id)->pluck('plot_id')->unique();
                $totalSqft = Plot::whereIn('id', $payments)->sum('plot_sqft');
                if ($totalSqft >= 33000) {
                    return '<span class="text-gradient text-red">' . $totalSqft . ' sqft </span>';
                }
                return $totalSqft . ' sqft';
            })
            ->editColumn('name', function ($data) {
                $payments = Payment::where('marketer_id', $data->id)->pluck('plot_id')->unique();
                $totalSqft = Plot::whereIn('id', $payments)->sum('plot_sqft');
                if ($totalSqft >= 33000) {
                    return '<span class="text-gradient text-red">' . $data->name . '</span>';
                }
                return $data->name;
            })
            ->editColumn('marketer_vcity_id', function ($data) {
                $payments = Payment::where('marketer_id', $data->id)->pluck('plot_id')->unique();
                $totalSqft = Plot::whereIn('id', $payments)->sum('plot_sqft');
                if ($totalSqft >= 33000) {
                    return '<span class="text-gradient text-red">' . $data->marketer_vcity_id . '</span>';
                }
                return $data->marketer_vcity_id;
            })
            ->editColumn('mobile_no', function ($data) {
                $payments = Payment::where('marketer_id', $data->id)->pluck('plot_id')->unique();
                $totalSqft = Plot::whereIn('id', $payments)->sum('plot_sqft');
                if ($totalSqft >= 33000) {
                    return '<span class="text-gradient text-red">' . $data->mobile_no . '</span>';
                }
                return $data->mobile_no;
            })
            ->setRowId('id')
            ->escapeColumns([]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Marketer $model): Builder
    {
        return $model->newQuery()
            ->whereHas('payments', function (Builder $query) {
                $query->select('marketer_id')
                    ->where('payment_status', config('constants.payment_status.full_payment'))
                    ->groupBy('marketer_id')
                    ->havingRaw('COUNT(DISTINCT plot_id) > 0');
            });
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('marketerprogress-table')
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
            Column::make('marketer_vcity_id'),
            Column::make('name'),
            Column::make('mobile_no'),
            Column::make('plots_sold'),
            Column::make('total_sqft_sold'),
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
        return 'MarketerProgress_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        return "<div class='btn-group'>" . "<a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData($data->id)'><i title='view' class='fa fa-eye'></i></a>" .
            "</div>";
    }
}
