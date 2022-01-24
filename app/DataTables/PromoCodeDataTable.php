<?php

namespace App\DataTables;

use App\Models\PromoCode;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

use DB;

class PromoCodeDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];
    
    
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable =
        $dataTable
        ->editColumn('updated_at', function ($promoCode) {
            return getDateColumn($promoCode, 'updated_at');
        })
        ->addColumn('action', 'promo_codes.datatables_actions')
        ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PromoCode $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false ,'responsivePriority'=>'100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'),
                [
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),
                        true
                    )
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'code',
                'title' => trans('lang.promoCode_code'),
                
                ],
                            [
                'data' => 'value',
                'title' => trans('lang.promoCode_value'),
                'searchable'=>false,'orderable'=>false,'exportable'=>false,'printable'=>false,
                ],
                            [
                'data' => 'updated_at',
                'title' => trans('lang.promoCode_updated_at'),
                'searchable'=>false,
                ]
            ];

        $hasCustomField = in_array(PromoCode::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', PromoCode::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.promoCode_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'promoCodesdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename().'.pdf');
    }
}
