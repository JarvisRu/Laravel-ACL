<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // setting fillable data
    protected $fillable = [
      'name', 'slug', 'description',
    ];

    // create relation with Role model
    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }
}
