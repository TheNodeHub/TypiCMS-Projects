<?php
namespace TypiCMS\Modules\Projects\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use TypiCMS\Facades\TypiCMS;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Projects\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        $router->model('projects', 'TypiCMS\Modules\Projects\Models\Project');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function($router) {

            /**
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('projects')) {
                foreach (config('translatable.locales') as $lang) {
                    $options = $page->private ? ['middleware' => 'auth'] : [] ;
                    if ($uri = $page->uri($lang)) {
                        $router->get($uri, $options + ['as' => $lang.'.projects', 'uses' => 'PublicController@index']);
                        $router->get($uri.'/{categories}', $options + ['as' => $lang.'.projects.categories', 'uses' => 'PublicController@index']);
                        $router->get($uri.'/{categories}/{slug}', $options + ['as' => $lang.'.projects.categories.slug', 'uses' => 'PublicController@show']);
                    }
                }
            }

            /**
             * Admin routes
             */
            $router->resource('admin/projects', 'AdminController');
            $router->post('admin/projects/sort', array('as' => 'admin.projects.sort', 'uses' => 'AdminController@projects'));

            /**
             * API routes
             */
            $router->resource('api/projects', 'ApiController');
        });
    }

}
