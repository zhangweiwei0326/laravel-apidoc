<?php

namespace Weiwei\LaravelApiDoc;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ApiDocServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $defer = false;
    public function boot()
    {
        $viewPath = __DIR__.'/../views';
        $this->loadViewsFrom($viewPath, 'apidoc');
        $this->setupRoutes($this->app->router);
        //发布配置文件
        $this->publishes([
            __DIR__.'/config/doc.php' => config_path('doc.php'),
        ]);
        //发布静态资源
        $this->publishes([
            __DIR__.'/../assets' => public_path('apidoc'),
        ], 'public');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Weiwei\LaravelApiDoc\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('apidoc',function($app){
            return new Contact($app);
        });
        config([
            'config/doc.php',
        ]);
    }
}
