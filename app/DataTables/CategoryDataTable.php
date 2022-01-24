<?php

namespace App\DataTables;

use App\Models\Category;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

use DB;

class CategoryDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];
    private $id = 0;
    private $language_id = 1;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function set_id($id)
    {
        $this->id=$id;
    }
    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function set_language($language_id)
    {
        $this->language_id = $language_id;
    }
    
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable =
        $dataTable->editColumn('image', function ($category) {
            return getMediaColumn($category, 'image');
        })
        ->editColumn('updated_at', function ($category) {
            return getDateColumn($category, 'updated_at');
        })
        ->addColumn('action', 'categories.datatables_actions')

        ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    

    /**
     * Get model and return the join o flanguages with language condition
     *
     * @param $this->categoryRepository catigories, $language_id
     * @return $this->categoryRepository catigories
     */
    public function language_join($categories, $language_id)
    {
        $categories = $categories->join('category_translates', 'category_translates.category_id', 'categories.id')
                                ->where('category_translates.language_id', $language_id);
        return $categories;
    }

    /**
    * select the data with the main id not translate id
    *
    *
    * @param $this->categoryRepository catigories
    * @return $this->categoryRepository catigories
    */

    public function select_main_id($categories)
    {
        $categories = $categories->select(
            'categories.*',
            'category_translates.name',
            'category_translates.description'
        );
        return $categories;
    }
    


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Category $model)
    {
        $model = self::language_join($model, $this->language_id);
        $model = self::select_main_id($model);
        return $model->where("parent_id", $this->id)->newQuery();
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
                'data' => 'name',
                'title' => trans('lang.category_name'),
                
                ],
                            [
                'data' => 'image',
                'title' => trans('lang.category_image'),
                'searchable'=>false,'orderable'=>false,'exportable'=>false,'printable'=>false,
                ],
                            [
                'data' => 'updated_at',
                'title' => trans('lang.category_updated_at'),
                'searchable'=>false,
                ]
            ];

        $hasCustomField = in_array(Category::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Category::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.category_' . $field->name),
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
        return 'categoriesdatatable_' . time();
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
