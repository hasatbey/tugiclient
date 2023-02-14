<?php namespace Hasatbey\Tugiclient\Models;


use Illuminate\Database\Eloquent\Model;

class Page extends Model{
    
    protected $table = 'cms_pages';
	protected $guarded  = ['id']; // create kullanmak için

    public function translates(){
        $languages = config('tugicms.company.languages');
		return $this->hasMany('Tugicms\Models\Site\Translate','page_id')
            ->orderByRaw("FIELD(language ,'".$languages[0]."') DESC");
	}
    public function contents(){
		return $this->hasMany('Tugicms\Models\Site\Contents','page_id');
	}
	public function menu(){
        return $this->belongsTo('Tugicms\Models\Site\Menu', 'menu_id');
    }
}
