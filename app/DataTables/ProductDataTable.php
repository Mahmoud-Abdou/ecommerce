<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class ProductDataTable extends DataTable
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
            ->editColumn('image', function ($product) {
                return getMediaColumn($product, 'image');
            })
            ->editColumn('price', function ($product) {
                return getPriceColumn($product);
            })
            ->editColumn('discount_price', function ($product) {
                return getPriceColumn($product, 'discount_price');
            })
            ->editColumn('capacity', function ($product) {
                return $product['capacity']." ".$product['unit'];
            })
            ->editColumn('updated_at', function ($product) {
                return getDateColumn($product, 'updated_at');
            })
            ->editColumn('featured', function ($product) {
                return getBooleanColumn($product, 'featured');
            })
            ->addColumn('action', 'products.datatables_actions')
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
        if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("market")
                        ->select(
                            'products.*',
                            // 'product_translates.name',
                            // 'product_translates.description',
                            'category_translates.name as category.name'
                        )
                        // ->join('product_translates', 'product_translates.product_id', 'products.id')
                        ->join('categories', 'categories.id', 'products.category_id')
                        ->join('category_translates', 'categories.id', 'category_translates.category_id')
                        ->where('category_translates.language_id', $this->language_id)
                        // ->where('product_translates.language_id', $this->language_id)
                        ->orderBy('products.updated_at', 'desc');
        } else {
            return $model->newQuery()->with("market")
                ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                // ->join('product_translates', 'product_translates.product_id', 'products.id')
                ->join('categories', 'categories.id', 'products.category_id')
                ->join('category_translates', 'categories.id', 'category_translates.category_id')
                ->where('category_translates.language_id', $this->language_id)
                // ->where('product_translates.language_id', $this->language_id)
                ->where('user_markets.user_id', auth()->id())
                ->select(
                    'products.*',
                    // 'product_translates.name',
                    // 'product_translates.description',
                    'category_translates.name as category.name'
                )->orderBy('products.updated_at', 'desc');
        }
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
                config('datatables-buttons.parameters'),
                [
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
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
                'data' => 'name',
                'title' => trans('lang.product_name'),
                'searchable' => false,
            ],
            [
                'data' => 'image',
                'title' => trans('lang.product_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],
            [
                'data' => 'price',
                'title' => trans('lang.product_price'),

            ],
            [
                'data' => 'discount_price',
                'title' => trans('lang.product_discount_price'),

            ],
            [
                'data' => 'capacity',
                'title' => trans('lang.product_capacity'),

            ],
            [
                'data' => 'featured',
                'title' => trans('lang.product_featured'),

            ],
            [
                'data' => 'market.name',
                'title' => trans('lang.product_market_id'),

            ],
            [
                'data' => 'category.name',
                'title' => trans('lang.product_category_id'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.product_updated_at'),
                'searchable' => false,
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
        return 'productsdatatable_' . time();
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
