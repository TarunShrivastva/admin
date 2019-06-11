<?php

namespace App\Admin\Controllers;

use App\Author;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\CheckRow;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers\Editable;


class AuthorController extends Controller
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

            $content->header('Author');
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

            $content->header('Author');
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

            $content->header('Authir');
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
        return Admin::grid(Author::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'DESC');
            $grid->id('ID')->sortable();
            $grid->author('author')->limit(20);
            $grid->author_email('author_email')->limit(20);
            $grid->image()->image('http://localhost:8000/upload/', 100, 100);
            $grid->status('status')->editable();
            $grid->created_at();
            $grid->updated_at();
            $grid->filter(function ($filter) {
                $filter->like('author');
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
        return Admin::form(Author::class, function (Form $form) {
            $form->text('author','Author')->rules('required|min:3');
            $form->email('author_email','Email')->rules('required|min:3');
            $form->textarea('address','Address')->rules('required|min:3');
            $form->image('image','Image')->uniqueName()->rules('required|mimes:jpg,jpeg,png');
            $form->select('status','Status')->options(array('0'=>'Off', '1' => 'On'))->rules('required');
       });
    }
}
