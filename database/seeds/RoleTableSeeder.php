<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
        $role_2->slug = "資管";
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
}
