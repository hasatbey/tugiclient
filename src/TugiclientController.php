<?php namespace Hasatbey\Tugiclient;


use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Hasatbey\Tugiclient\Models\Translate;
use Hasatbey\Tugiclient\Models\Contents;
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

        $data['breadcrumb'] = $this->getBreadcrumb($slugs); //must be first
        $data['navigation'] = $this->getNavigation(); //2
        $data['page'] = end($data['breadcrumb']); //3


        // sayfaya ait modüllerin içeriğini getirir spotlar hariç
        $data['contents'] = Contents::select('cms_contents.*', 'cms_modules.title as module_title', 'cms_modules.key as module_key')
            ->where('cms_contents.translate_id', end($data['breadcrumb'])['id'])
            ->where('cms_contents.location', '!=', '')
            //->whereNotIn('cms_contents.id', $blockContentsIDs)
            ->where('cms_contents.status', 1)
            ->join('cms_modules', 'cms_modules.id', '=', 'cms_contents.modules_id')
            ->orderBy('cms_contents.location')
            ->orderBy('rank')
            ->get()->toArray();

/*
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        exit();
*/

        return $this->app['view']
            ->make($this->package.'.index')
            ->with(['data' => $data]);
    }

    public function getNavigation($menus = false, $permalink = '') {
        if (!$menus) {
            $menus = Menu::select('*')
                ->where('parent_id', null)
                ->has('cover_page.translate')
                ->with('cover_page.translate')
                ->with('children')
                ->orderBy('rank')
                ->get()
                ->toArray();
            $menus[0]['cover_page']['translate']['slug'] = ''; //ana sayfa için
        }
        $return = false;
        foreach ($menus as $key => $menu) {
            $return[$key] = $menu['cover_page']['translate'];
            $return[$key]['permalink'] = $permalink . '/' . $return[$key]['slug'];
            $return[$key]['children'] = $menu['children'] ? $this->getNavigation($menu['children'], $return[$key]['permalink']) : false;
        }

        return $return;
    }

    public function getBreadcrumb($slugs = false) {
        if ($slugs) {
            $slugArr = explode('/', $slugs);
            $parent_id = null;
            $permalink = '';
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
                $translate['permalink'] = $permalink = $permalink . '/' . $translate['slug'];


                $parent_language = $translate['language'];
                $parent_id = $translate['page']['menu']['id'];
                unset($translate['page']);
                $breadcrumb[] = $translate;
            }
            app()->setLocale($breadcrumb[0]['language']); //url'e göre dil set edilir

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


        return $breadcrumb;
    }


}
