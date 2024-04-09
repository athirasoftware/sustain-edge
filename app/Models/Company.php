<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';
    /**
     * user has once
     */    
    public function user() {
        // return $this->belongsToMany('App\Models\User'); // links this->id to users.company_id
        return $this->hasMany('App\Models\User'); // links this->id to users.company_id
    }
}
