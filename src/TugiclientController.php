<?php namespace Hasatbey\Tugiclient;


use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Hasatbey\Tugiclient\Models\Translate;

//use Illuminate\Support\Facades\Request;

class TugiclientController extends Controller {
    protected $package = 'tugiclient';

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function index($slugs = null) {
        $slugArr = $slugs ? explode('/', $slugs) : [];
        $parent_id = null;
        foreach ($slugArr as $key => $slug) {
            $translate = Translate::select('*')
                ->whereHas('page.menu', function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                })
                ->where('slug', $slug)
                ->where('status', 1)
                ->with('page.menu');

            if (isset($parent_language)) $translate->where('language', $parent_language);
             
            $translate = $translate->firstOrFail()->toArray();

            $breadcrumb[] = $translate;
            $parent_id = $translate['page']['menu']['id'];
            $parent_language = $translate['language'];
        }

        print_r($breadcrumb);
        exit();


        return $this->app['view']
            ->make($this->package . '::index')
            ->with(['dir' => 'dir']);
    }

    public function test() {
        return $this->app['view']
            ->make($this->package . '::test')
            ->with($this->getViewVars());
    }

}
