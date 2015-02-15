<?php
namespace TypiCMS\Modules\Projects\Http\Controllers;

use App;
use Illuminate\Support\Str;
use TypiCMS;
use TypiCMS\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Projects\Repositories\ProjectInterface;
use View;

class PublicController extends BasePublicController
{

    public function __construct(ProjectInterface $project)
    {
        parent::__construct($project);
        $this->title['parent'] = Str::title(trans_choice('projects::global.projects', 2));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($category = null)
    {
        TypiCMS::setModel($this->repository->getModel());

        $this->title['child'] = '';

        $relatedModels = array('translations', 'category', 'category.translations');

        if ($category) {
            $models = $this->repository->getAllBy('category_id', $category->id, $relatedModels, false);
            TypiCMS::setModel($category); // Needed for building lang switcher
        } else {
            $models = $this->repository->getAll($relatedModels, false);
        }

        return view('projects::public.index')
            ->with(compact('models', 'category'));
    }

    /**
     * Show resource.
     *
     * @return Response
     */
    public function show($category = null, $slug = null)
    {
        $model = $this->repository->bySlug($slug);
        if ($category->id != $model->category_id) {
            App::abort(404);
        }

        TypiCMS::setModel($model);

        $this->title['parent'] = $model->title;

        return view('projects::public.show')
            ->with(compact('model'));
    }
}