<?php namespace Hasatbey\Tugiclient;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;


class ElfinderServiceProvider extends ServiceProvider {
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
	    $this->loadViewsFrom(__DIR__ . '/resources/views', 'tugiclient');
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
        $router->group($config, function($router)
        {
            $router->get('/',  ['as' => 'tugiclient.index', 'uses' =>'ElfinderController@showIndex']);
            $router->any('connector', ['as' => 'tugiclient.connector', 'uses' => 'ElfinderController@showConnector']);
            $router->get('popup/{input_id}', ['as' => 'tugiclient.popup', 'uses' => 'ElfinderController@showPopup']);
            $router->get('filepicker/{input_id}', ['as' => 'tugiclient.filepicker', 'uses' => 'ElfinderController@showFilePicker']);
            $router->get('tinymce', ['as' => 'tugiclient.tinymce', 'uses' => 'ElfinderController@showTinyMCE']);
            $router->get('tinymce4', ['as' => 'tugiclient.tinymce4', 'uses' => 'ElfinderController@showTinyMCE4']);
            $router->get('tinymce5', ['as' => 'tugiclient.tinymce5', 'uses' => 'ElfinderController@showTinyMCE5']);
            $router->get('ckeditor', ['as' => 'tugiclient.ckeditor', 'uses' => 'ElfinderController@showCKeditor4']);
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
