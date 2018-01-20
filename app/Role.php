<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // setting fillable data
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    // create relation with User model
    public function users()
    {
      return $this->belongsToMany(User::class);
    }

    // create relation with Permission model
    public function permissions()
    {
      return $this->belongsToMany(Permission::class);
    }

    /**
    * Authorize permission
    * @param string $permission
    */
    public function authorizePermission($permissions){
        if(is_array($permissions)){
            foreach($permissions as $permission){
                if($this->hasPermission($permission))   
                    return true;
            }
        }
        else{
            if($this->hasPermission($permissions))
                return true;
        }
        return false;
    }

    /**
    * check role has this permission
    * @param string $permission
    */
    public function hasPermission($permission){
        return null !== $this->permissions()->where("name", $permission)->first();
    }

}
