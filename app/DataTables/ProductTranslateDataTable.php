<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;
use DB;

class ProductTranslateDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    private $language_id = 1;

    public function set_language($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
     * Get untranslated languages
     * 
     * @param product products, $language_id
     * @return product products
     */
    public function get_untranslated_languages($products, $language_id)
    {
        $all_languages = DB::table('languages')->select(DB::raw('count(*)'))->first();
        
        $untranslated_products = [];
        $all_products = $products->get();
        foreach($all_products as $product){
            $count = DB::table('product_translates')->select(DB::raw('count(*)'))->where('product_id', $product->id)->first();
            if($count < $all_languages){
                $untranslated_products[] = $product->id; 
            }
        }
        
        $products = $products->select('products.id', 'product_translates.name', 'product_translates.description')
                                ->join('product_translates', 'product_translates.product_id', 'products.id')
                                ->where('product_translates.language_id', $language_id)
                                ->whereIn('products.id', $untranslated_products);
        
        return $products;
    }


    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->addColumn('action', 'product_translates.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {

        $model = self::get_untranslated_languages($model, $this->language_id);
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
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
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
                'data' => 'name',
                'title' => trans('lang.product_name'),

            ],
            [
                'data' => 'description',
                'title' => trans('lang.product_description'),

            ]
        ];

        $hasCustomField = in_array(Product::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Product::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.product_' . $field->name),
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
        return 'productstranlatedatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }
}