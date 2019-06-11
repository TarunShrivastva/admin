<?php

namespace App\Admin\Controllers;

use App\Module;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CheckRow;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\Editable;


class ModuleController extends Controller
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

            $content->header('Module');
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

            $content->header('Module');
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

            $content->header('Module');
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
        return Admin::grid(Module::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');
            $grid->id('ID')->sortable();
            $grid->name('Name');
            $grid->display();
            $grid->url('Url');
            $grid->parent()->id('Parent Id');
            $grid->created_at()->sortable();
            $grid->updated_at()->sortable();
            $grid->filter(function ($filter) {
                $filter->like('name');
                $filter->like('url');
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
        return Admin::form(Module::class, function (Form $form) {
            $modules = Module::where('status','1')->get()->toArray();
            $moduleArray = array('0' => 'Please Select An Author');//
            foreach ($modules as $key => $value) {
                $moduleArray[$value['id']] = $value['name'];    
            }
            $form->text('name','Name')->rules('required|min:3');
            $form->text('display','Display')->rules('required|min:3');
            $form->text('url','Url')->rules('required|min:3');
            $form->select('parent_id','Category')->options($moduleArray)->rules('required');
            $form->image('icon','Icon')->uniqueName()->rules('required|mimes:jpg,jpeg,png');
            $form->select('status','Status')->options(array('0'=>'Off', '1' => 'On'))->rules('required');
        });
    }
    
}
