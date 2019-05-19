<?php

namespace App\Admin\Controllers;

use App\Article;
use App\Author;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ArticleController extends Controller
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

            $content->header('Articles');
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

            $content->header('Article');
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

            $content->header('Article');
            $content->description('Add Article');

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
        return Admin::grid(Article::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->title('Title');
            $grid->author()->author('Author');
            $grid->created_at()->sortable();
            $grid->updated_at()->sortable();
            $grid->filter(function ($filter) {
                // Sets the range query for the created_at field
                // $filter->between('created_at', 'Created')->datetime();
                $filter->like('title','Title');
            });
            // $grid->display('s.no',)->sortable();
            // $grid->description('description');
            // $grid->author()->status('Author Status');
            // $grid->image('Image');
            // $grid->deleted_at()->sortable();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Article::class, function (Form $form) {
            $authors = Author::where('status','1')->get();
            $authors = $authors->toArray();
            $authorArray = array('0','Please Select An Author');//
            foreach ($authors as $key => $value) {
                array_push($authorArray,$value['author']);    
            }
            $form->text('title','Title')->attribute(['id' => 'title', 'name' => 'title', 'class' => 'form-control title test'])->rules('required|min:3');
            // $form->textarea('description','Description');
            $form->ckeditor('description','Description')->rules('required');
            $form->select('author_id','Author')->options($authorArray)->rules('required');
            $form->image('image','Image')->uniqueName()->rules('required|mimes:jpg,jpeg,png');
        });
    }
}
