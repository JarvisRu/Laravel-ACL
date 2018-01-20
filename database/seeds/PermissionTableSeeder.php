<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
}
