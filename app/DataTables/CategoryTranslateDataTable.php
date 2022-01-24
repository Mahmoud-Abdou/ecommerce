<?php

namespace App\DataTables;

use App\Models\CategoryTranslate;
use App\Models\Category;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

use DB;

class CategoryTranslateDataTable extends DataTable
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
        ->addColumn('action', 'category_translates.datatables_actions')
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
     * Get untranslated languages
     * quary:
     * SELECT * FROM `categories` CROSS join languages
     * where languages.id in ((SELECT id from languages lang where lang.id
     * not in (select language_id from category_translates cat_trans_t where categories.id = cat_trans_t.category_id)))
     * 
     * @param CategoryTranslate category_translates, $language_id
     * @return CategoryTranslate category_translates
     */
    public function get_untranslated_languages($categories, $language_id)
    {
        $all_languages = DB::table('languages')->select(DB::raw('count(*)'))->first();
        
        $untranslated_categories = [];
        $all_categories = $categories->get();
        foreach($all_categories as $category){
            $count = DB::table('category_translates')->select(DB::raw('count(*)'))->where('category_id', $category->id)->first();
            if($count < $all_languages){
                $untranslated_categories[] = $category->id; 
            }
        }
        
        $categories = $categories->select('categories.id', 'category_translates.name', 'category_translates.description')
                                ->join('category_translates', 'category_translates.category_id', 'categories.id')
                                ->where('category_translates.language_id', $language_id)
                                ->whereIn('categories.id', $untranslated_categories);
        
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
        $untranslated_languages = self::get_untranslated_languages($model, $this->language_id);
        return $untranslated_languages->newQuery();
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

        $hasCustomField = in_array(CategoryTranslate::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', CategoryTranslate::class)->where('in_table', '=', true)->get();
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
