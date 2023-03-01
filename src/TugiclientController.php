<?php namespace Hasatbey\Tugiclient;


use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Hasatbey\Tugiclient\Models\Translate;
//use Illuminate\Support\Facades\Request;

class TugiclientController extends Controller
{
    protected $package = 'tugiclient';

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function index($slugs = null)
    {
        $slugArr = $slugs ? explode('/', $slugs) : [];
        $current = Translate::select('*')->with('page')->where('slug', end($slugArr))->where('status', 1)->first()->toArray();
    
        print_r($current);
        exit();
        
        
        return $this->app['view']
            ->make($this->package . '::index')
            ->with(['dir'=>'dir']);
    }

    public function test()
    {
        return $this->app['view']
            ->make($this->package . '::test')
            ->with($this->getViewVars());
    }
 
}
