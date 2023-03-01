<?php namespace Hasatbey\Tugiclient;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;


class TugiclientServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	
	
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	    $this->loadViewsFrom(__DIR__.'/../publishes/views', 'tugiclient');
        $this->publishes([
		    __DIR__.'/../publishes/assets/' => public_path('tugiclient'),
            __DIR__.'/../publishes/views' => base_path('resources/views/tugiclient'),
            __DIR__.'/../publishes/config.php' => config_path('tugiclient.php'),
        ],'tugiclient');
        
        $this->mergeConfigFrom(__DIR__ . '/../publishes/config.php', 'tugiclient');
	}

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
	    
        $config = $this->app['config']->get('tugiclient.route', []);
        $config['namespace'] = 'Hasatbey\Tugiclient';
        $router->group($config, function($router) {
            $router->get('/',  ['as' => 'tugiclient.index', 'uses' =>'TugiclientController@index']);
            $router->any('/test', ['as' => 'tugiclient.connector', 'uses' => 'TugiclientController@test']);
        
            // Glide process url for images in (filesystem) folder
            // EX: http://yoursite.com/file/1.jpg?w=1200&h=800&fit=crop
            $router->get('/file/{path}', function(\Illuminate\Contracts\Filesystem\Filesystem $filesystem , $path){
                    $server = \League\Glide\ServerFactory::create([
                        'response' => new \League\Glide\Responses\LaravelResponseFactory(app('request')),
                        'source' => app('filesystem')->disk('public')->getDriver(),
                        'cache' => app('filesystem')->disk('public')->getDriver(),
                        'cache_path_prefix' => '.cache',
                        'base_url' => 'img',
                    ]);
                    return $server->getImageResponse($path, request()->all());
            })->where('path', '.+');

            
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('command.tugiclient.publish');
	}

}
