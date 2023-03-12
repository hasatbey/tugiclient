<?php namespace Hasatbey\Tugiclient\Models;


use Illuminate\Database\Eloquent\Model;

class Page extends Model{
    
    protected $table = 'cms_pages';
	protected $guarded  = ['id']; // create kullanmak için

    public function translates(){
        $languages = config('tugicms.company.languages');
		return $this->hasMany('Hasatbey\Tugiclient\Models\Translate','page_id')
            ->orderByRaw("FIELD(language ,'".$languages[0]."') DESC");
	}
    public function translate(){
		return $this->hasOne('Hasatbey\Tugiclient\Models\Translate','page_id')
            ->where('status',1)
            ->where('visible',1)
            ->where("language",config('app.locale'));
	}
	
	
    public function contents(){
		return $this->hasMany('Hasatbey\Tugiclient\Models\Contents','page_id');
	}
	public function menu(){
        return $this->belongsTo('Hasatbey\Tugiclient\Models\Menu', 'menu_id');
    }
}
