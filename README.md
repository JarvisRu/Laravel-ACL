# Laravel-ACL
Using Laravel to implement User, Role and Permission control

## Target
- Many to Many to Many
    - User can has many role
    - Role can has many permission
- Admin can assign role & permission to users
- Using middleware to block some action in route 


## 1. User and Role (Model & DB setup)

#### Do artisan make auth to create Auth resource
```shell
$ php artisan make:auth
```

#### Create ***Role*** model & Migration
```shell
$ php artisan make:model Role -m
```

#### Edit ***createRolesTable*** to what u want
```php
Schema::create('roles', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

#### Providing many to many relation
In ***Role*** model, add
```php
public function users()
{
  return $this->belongsToMany(User::class);
}
```

In ***User*** model, add
```php
public function roles()
{
  return $this->belongsToMany(Role::class);
}
```

create ***create_user_role_table*** and edit it
```shell
$ php artisan make:migration create_role_user_table
```

```php
Schema::create('role_user', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('role_id');
        $table->integer('user_id');
        $table->timestamps();
    });
```

#### Create seed to do testing 
```shell
$ php artisan make:seeder UserTableSeeder
$ php artisan make:seeder RoleTableSeeder
```

#### First, edit ***UserTabeSeeder***
```php
use App\User;

public function run()
{
    $member = new User();
    $member->name = "John";
    $member->email = "John@seed.com";
    $member->password = bcrypt('John');
    $member->save();

    $leader = new User();
    $leader->name = "Ryan";
    $leader->email = "Ryan@seed.com";
    $leader->password = bcrypt('Ryan');
    $leader->save();

    $admin = new User();
    $admin->name = "Jarvis";
    $admin->email = "Jarvis@seed.com";
    $admin->password = bcrypt('Jarvis');
    $admin->save();
}
```

#### and adding this to ***DataBaseSeeder***
```php
public function run()
{
    $this->call(UserTableSeeder::class);
}
```

#### Run the migrate with seed, check if ur setting is successful and the data is in ur DB
```shell
$ php artisan migrate --seed
```

#### Then start to edit ***RoleTabeSeeder***
```php
use App\Role;

public function run()
{
    $role_member = new Role();
    $role_member->name = "技術研發組";
    $role_member->slug = "技研組";
    $role_member->description = "研發研發";
    $role_member->save()

    $role_leader = new Role();
    $role_leader->name = "資源管理組";
    $role_leader->slug = "資管組";
    $role_leader->description = "資管資管";
    $role_leader->save()

    $role_admin = new Role();
    $role_admin->name = "Admin";
    $role_admin->slug = "admin";
    $role_admin->description = "super admin";
    $role_admin->save()
}
```

#### Asigning ***Role*** to ***User***, edit ***UserTabeSeeder*** to
```php
use App\User;
use App\Role;

public function run()
{
    // get role
    $role_member = Role::where("name","Member")->first();
    $role_leader = Role::where("name","Leader")->first();
    $role_admin = Role::where("name","Admin")->first();

    // create user and asign role to them
    $member = new User();
    $member->name = "John";
    $member->email = "John@seed.com";
    $member->password = bcrypt('John');
    $member->save();
    $member->roles()->attach($role_member);

    $leader = new User();
    $leader->name = "Ryan";
    $leader->email = "Ryan@seed.com";
    $leader->password = bcrypt('Ryan');
    $leader->save();
    $leader->roles()->attach($role_leader);

    $admin = new User();
    $admin->name = "Jarvis";
    $admin->email = "Jarvis@seed.com";
    $admin->password = bcrypt('Jarvis');
    $admin->save();
    $admin->roles()->attach($role_admin);
}
```

#### Edit ***DataBaseSeeder*** again, mind that ***Role*** need to create before ***User***
```php
public function run()
{
    $this->call(RoleTableSeeder::class);
    $this->call(UserTableSeeder::class);
}
```

#### Do the migration
```shell
$ php artisan migrate:fresh --seed
```

#### User&Role relation set up should be successful



---


## 2. Middleware - checkRole 

#### In model ***User***, add following function to authouize role
```php
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

public function hasRole($role){
    return null !== $this->roles()->where("name", $role)->first();
}
```

#### Create Middleware ***UserHasRole*** and add following code
```shell
$ php artisan make:middleware UserHasRole
```
```php
public function handle($request, Closure $next, $role)
{
    if($request->user()===NULL)
        return response('Insufficient permission', 401);
    if($request->user()->authorizeRole($role))
        return $next($request);
    else
        return response('Unauthorized', 401);
}
```

#### Register middleware in ***app\Http\Kernel.php***
Add this line at protected $routeMiddleware
```php
'checkRole' => \App\Http\Middleware\UserHasRole::class,
```

#### Implement in ***web.php***
##### ex: only Admin can see the page
```php
Route::get('/home/admin', 'HomeController@getAdminPage')
        ->name('adminPage')
        ->middleware('checkRole:Admin');
```


---


## 3. Role and Permission (Model & DB setup)
> almost the same as above
> 
#### Create ***Permission*** model & Migration
```shell
$ php artisan make:model Permission -m
```

#### Edit ***createPermissionsTable*** to what u want
```php
Schema::create('permissions', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

#### Providing many to many relation
In ***Permission*** model, add
```php
public function roles()
{
  return $this->belongsToMany(Role::class);
}
```

In ***Role*** model, add
```php
public function permissions()
{
  return $this->belongsToMany(Permission::class);
}
```

create ***create_permission_role_table*** and edit it
```shell
$ php artisan make:migration create_permission_role_table
```

```php
Schema::create('permission_role', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('permission_id');
        $table->integer('role_id');
        $table->timestamps();
    });
```

#### Create seed to test permission
```shell
$ php artisan make:seeder PermissionTableSeeder
```

#### Edit ***PermissionTableSeeder*** as
```php
use App\Permission;

public function run()
{
    $post_article = new Permission();
    $post_article->name = "發布文章";
    $post_article->slug = "發文";
    $post_article->description = "可以發布文章";
    $post_article->save();

    $assign_permission = new Permission();
    $assign_permission->name = "賦予權限";
    $assign_permission->slug = "給權限";
    $assign_permission->description = "可以賦予權限";
    $assign_permission->save();

    $assign_role = new Permission();
    $assign_role->name = "賦予角色";
    $assign_role->slug = "給角色";
    $assign_role->description = "可以賦予角色";
    $assign_role->save();
}
```


#### Assign permission to role, edit ***RoleTableSeeder*** as
```php
use App\Role;
use App\Permission;

public function run()
{
    // get permission
    $post_article = Permission::where("name","發布文章")->first();
    $assign_permission = Permission::where("name","賦予權限")->first();
    $assign_role = Permission::where("name","賦予角色")->first();

    $role_1 = new Role();
    $role_1->name = "技術研發組";
    $role_1->slug = "技研組";
    $role_1->description = "研發研發";
    $role_1->save();
    $role_1->permissions()->attach($post_article);

    $role_2 = new Role();
    $role_2->name = "資源管理組";
    $role_2->slug = "資管組";
    $role_2->description = "資管資管";
    $role_2->save();
    $role_2->permissions()->attach($assign_permission);
    $role_2->permissions()->attach($assign_role);

    $role_admin = new Role();
    $role_admin->name = "Admin";
    $role_admin->slug = "admin";
    $role_admin->description = "super admin";
    $role_admin->save();
    $role_admin->permissions()->attach($post_article);
    $role_admin->permissions()->attach($assign_permission);
    $role_admin->permissions()->attach($assign_role);
}
```

#### Edit ***DatabaseTableSeeder*** as 
```php
public function run()
{
    $this->call(PermissionTableSeeder::class);
    $this->call(RoleTableSeeder::class);
    $this->call(UserTableSeeder::class);
}
```

#### run migration
```shell
$ php artisan migrate:fresh --seed
```

#### Role&Permission relation set up should be successful

---

## 4. Now

- User has Role
    - Role has permission
        - **Target : User has Permission**

#### In ***Role*** model, add following code to authorize  permission
```php
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

public function hasPermission($permission){
    return null !== $this->permissions()->where("name", $permission)->first();
}
```

#### In ***User*** model, check if user can do -> has permission
```php
public function canDo($permission){
    $roles = $this->roles()->get();
    foreach($roles as $role){
        if($role->authorizePermission($permission))
            return true;
    }
    return false;
}
```
#### Now, we can check
- If user has this role by : 
    ```php
    $user->authorizeRole($role_name)
    ```
- If role has this permission by :
    ```php
    $role->authorizePermission($permission_name)
    ```
- If user has this permission by :
    ```php
    $user->canDo($permission_name)
    ```
We can move on to Middleware again

---

## 5. Middleware - checkPermission
#### Create Middleware ***UserHasPermission*** and add following code
```shell
$ php artisan make:middleware UserHasPermission
```
```php
public function handle($request, Closure $next, $permission)
{
    if($request->user()===NULL)
        return response('Insufficient permission', 401);
    if($request->user()->canDo($permission))
        return $next($request);
    else
        return response('Unauthorized', 401);
}
```

#### Register middleware in ***app\Http\Kernel.php***
Add this line at protected $routeMiddleware
```php
'checkPermission' => \App\Http\Middleware\UserHasPermission::class,
```

#### Implement in ***web.php***
ex: check permission before assign
```php
Route::post('/home/admin/assign_role', 'HomeController@assignRole')->middleware('checkPermission:賦予角色');
Route::post('/home/admin/assign_permission', 'HomeController@assignPermission')->middleware('checkPermission:賦予權限');
```
