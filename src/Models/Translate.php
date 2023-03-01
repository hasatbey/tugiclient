<?php namespace Hasatbey\Tugiclient\Models;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model{
    protected $table = 'cms_translates';
	protected $guarded = false;
	public $timestamps = false;
	
    public function page(){
		return $this->belongsTo('Hasatbey\Tugiclient\Models\Page','page_id');
	}
	
}
