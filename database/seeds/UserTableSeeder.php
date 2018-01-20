<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get role
        $role_1 = Role::where("name","技術研發組")->first();
        $role_2 = Role::where("name","資源管理組")->first();
        $role_admin = Role::where("name","Admin")->first();
        
        // create user and asign role to them
        $user_1 = new User();
        $user_1->name = "John";
        $user_1->email = "John@seed.com";
        $user_1->password = bcrypt('John');
        $user_1->title = "John title";
        $user_1->duty = "do something1";
        $user_1->phone = "0123456789";
        $user_1->avatar = "john_image.png";
        $user_1->save();
        $user_1->roles()->attach($role_1);
        
        $user_2 = new User();
        $user_2->name = "Ryan";
        $user_2->email = "Ryan@seed.com";
        $user_2->password = bcrypt('Ryan');
        $user_2->title = "Ryan title";
        $user_2->duty = "do something2";
        $user_2->phone = "0123456788";
        $user_2->avatar = "ryan_image.png";
        $user_2->save();
        $user_2->roles()->attach($role_2);

        $admin = new User();
        $admin->name = "Jarvis";
        $admin->email = "Jarvis@seed.com";
        $admin->password = bcrypt('Jarvis');
        $admin->title = "Super";
        $admin->duty = "as a admin";
        $admin->phone = "0932111111";
        $admin->avatar = "handsome.png";
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}
