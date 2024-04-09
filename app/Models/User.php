<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'name_of_org',
        'size_of_org',
        'industry',
        'sub_industry',
        'head_quarters',
        'country',
        'org_url',
        'role',
        'img_path',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function company() {
        return $this->belongsTo('App\Models\Company');
            // ->withTimestamps();   // links this->comapany_id to company.id
    }

    public function userCompany($companyId) {
        if ($this->company()->where('id', $companyId)->first()) {
            return true;
        }
        return false;
    }

    /**
     * User roles
     */
    public function roles() {
        return $this
            ->belongsToMany('App\Models\Role')
            ->withTimestamps();
    }

    public function authorizeRoles($roles) {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }
    public function hasAnyRole($roles) {
        if (is_array($roles)) {
            foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
            }
        } else {
            if ($this->hasRole($roles)) {
            return true;
            }
        }
        return false;
    }
    public function hasRole($role) {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }
}
