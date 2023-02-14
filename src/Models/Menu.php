<?php namespace Hasatbey\Tugiclient\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{
    
    protected $table = 'cms_menus';
	protected $guarded  = ['id']; // create kullanmak için
    public $timestamps = false;
	public function children(){
		return $this->hasMany('Tugicms\Models\Site\Menu','parent_id');
	}
	public function children_tree(){
		return $this->hasMany('Tugicms\Models\Site\Menu','parent_id')->with('cover_page.translates')->with('children_tree');
	}
    public function cover_page(){
		return $this->hasOne('Tugicms\Models\Site\Page','menu_id');
	}
	public function parent(){
        return $this->belongsTo('Tugicms\Models\Site\Menu', 'parent_id')->with('parent.cover_page.translates');
    }
	public function pages(){
        return $this->hasMany('Tugicms\Models\Site\Page','menu_id'); //@todo page_relation olacak
    }
	
}
