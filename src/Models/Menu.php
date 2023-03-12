<?php namespace Hasatbey\Tugiclient\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{
    
    protected $table = 'cms_menus';
	protected $guarded  = ['id']; // create kullanmak için
    public $timestamps = false;

	public function children(){
		return $this->hasMany('Hasatbey\Tugiclient\Models\Menu','parent_id')
            ->has('cover_page.translate')
            ->with('cover_page.translate')
            ->with('children');
	}
    public function cover_page(){
		return $this->hasOne('Hasatbey\Tugiclient\Models\Page','menu_id');
	}
	public function parent(){
        return $this->belongsTo('Hasatbey\Tugiclient\Models\Menu', 'parent_id')->with('parent.cover_page.translates');
    }
	public function pages(){
        return $this->hasMany('Hasatbey\Tugiclient\Models\Page','menu_id'); //@todo page_relation olacak
    }
	
}
