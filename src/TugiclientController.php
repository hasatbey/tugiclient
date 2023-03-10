<?php namespace Hasatbey\Tugiclient;


use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Hasatbey\Tugiclient\Models\Translate;
use Hasatbey\Tugiclient\Models\Menu;

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

        if ($slugArr) {
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

                $parent_language = $translate['language'];
                $parent_id = $translate['page']['menu']['id'];
                unset($translate['page']);
                $breadcrumb[] = $translate;
            }
  
        } else {
            //ana sayfa
            $breadcrumb[] = Translate::select('*')
                ->whereHas('page.menu', function ($q) {
                    $q->where('parent_id', null);
                    $q->where('rank', 0);
                })
                ->where('language', config('app.locale'))
                ->where('status', 1)->firstOrFail()->toArray();
        }

        $data['breadcrumb'] = $breadcrumb;
        $data['navigation'] = $this->navigation(Menu::select('*')
            ->where('parent_id', null)
            ->with('cover_page.translate')
            ->with('children')
            ->orderBy('rank')
            ->get()
            ->toArray());

        
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        exit();


        return $this->app['view']
            ->make($this->package . '::index')
            ->with(['dir' => 'dir']);
    }

    public function navigation($menus) {
        $return = false;
        foreach ($menus as $key => $menu) {
            $return[$key] = $menu['cover_page']['translate'];
            $return[$key]['children'] = $this->navigation($menu['children']);
        }
        return $return;
    }

    public function test() {
        return $this->app['view']
            ->make($this->package . '::test')
            ->with($this->getViewVars());
    }

}
