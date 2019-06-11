<?php

namespace App\Admin\Controllers;

use App\Category;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CheckRow;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\Editable;


class CategoryController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Category');
            $content->description('Listing');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Category');
            $content->description('Edit');
            
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Category');
            $content->description('Add Category');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Category::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');
            $grid->id('ID')->sortable();
            $grid->name('name')->limit(20);
            $grid->url('url')->limit(20);
            $grid->status('status')->editable();
            $grid->created_at();
            $grid->updated_at();
            $grid->filter(function ($filter) {
                $filter->like('name');
                $filter->between('created_at')->datetime();
                $filter->useModal();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Category::class, function (Form $form) {
            $form->text('name','Name');
            $form->url('url','Url');
            $form->image('icon','Icon')->uniqueName()->rules('mimes:jpg,jpeg,png');
            $category = Category::where('status','1')->get();
            $category = $category->toArray();
            $categoryArray = array('0' => 'Please Select A Category');
            foreach ($category as $key => $value) {
                 array_push($categoryArray,$value['name']);    
            }   
            $form->select('parent_id','Category')->options($categoryArray)->rules('required');
            $form->select('status','Status')->options(array('0'=>'Off', '1' => 'On'))->rules('required');
       });
    }
}
