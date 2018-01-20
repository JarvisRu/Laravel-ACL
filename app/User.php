<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Authorize role
    * @param string $role
    */
    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    /**
    * Authorize role
    * @param string $role
    */
    public function authorizeRole($roles){
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($role))   
                    return true;
            }
        }
        else{
            if($this->hasRole($roles))
                return true;
        }
        return false;
    }

    /**
    * check user has this role
    * @param string $role
    */
    public function hasRole($role){
        return null !== $this->roles()->where("name", $role)->first();
    }

    /**
    * check user has this permission
    * @param string $permission
    */
    public function canDo($permission){
        $roles = $this->roles()->get();
        foreach($roles as $role){
            if($role->authorizePermission($permission))
                return true;
        }
        return false;
    }
}
