<?php

namespace App\Admin\Controllers;

use App\Contenttype;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CheckRow;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\Editable;

class ContentController extends Controller
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
            $content->header('Content');
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

            $content->header('Content');
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

            $content->header('Content');
            $content->description('Add');

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
        return Admin::grid(Contenttype::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');
            $grid->id('ID')->sortable();
            $grid->content_type_name('Content');
            $grid->url('Url');
            $grid->status('Status')->editable();
            $grid->created_at()->sortable();
            $grid->updated_at()->sortable();
            $grid->filter(function ($filter) {
                $filter->like('content_type_name');
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
        return Admin::form(Contenttype::class, function (Form $form) {
            $form->text('content_type_name','Content')->attribute(['id' => 'content_type_name', 'name' => 'content_type_name'])->rules('required|min:3');
            $form->text('url','Url')->rules('required|min:3');
            $form->select('status','Status')->options(array('0'=>'Off', '1' => 'On'))->rules('required');
        });
    }
}
