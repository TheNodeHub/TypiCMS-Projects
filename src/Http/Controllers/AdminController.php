<?php

namespace TypiCMS\Modules\Projects\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Projects\Http\Requests\FormRequest;
use TypiCMS\Modules\Projects\Models\Project;
use TypiCMS\Modules\Projects\Repositories\ProjectInterface;

class AdminController extends BaseAdminController
{
    public function __construct(ProjectInterface $project)
    {
        parent::__construct($project);
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();

        return view('core::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Projects\Models\Project $project
     *
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        return view('core::admin.edit')
            ->with([
                'model' => $project,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Projects\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $project = $this->repository->create($request->all());

        return $this->redirect($request, $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Projects\Models\Project            $model
     * @param \TypiCMS\Modules\Projects\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Project $project, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $project);
    }
}
