<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    
	use SoftDeletes;

	protected $fillable = ['name', 'display', 'url', 'parent_id', 'icon', 'status'];

	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at', 'updated_at'
    ];
    
	protected $dates = ['deleted_at'];	
   
   public function children() {
        return $this->hasMany('App\Module','id');
   }
    
   public function parent() {
        return $this->belongsTo('App\Module','parent_id');
   }     
}
