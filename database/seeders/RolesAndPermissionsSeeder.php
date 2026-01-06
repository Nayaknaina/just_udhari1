<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
         // Create Roles
         $adminRole = Role::create(['name' => 'Shop Owner']);
         $editorRole = Role::create(['name' => 'Manager']);

         // Create Permissions
         $manageUsers = Permission::create(['name' => 'Manage Users']);
         $createUsers = Permission::create(['name' => 'Create Users', 'parent_id' => $manageUsers->id]);
         $editUsers = Permission::create(['name' => 'Edit Users', 'parent_id' => $manageUsers->id]);
         $deleteUsers = Permission::create(['name' => 'Delete Users', 'parent_id' => $manageUsers->id]);

         $managePosts = Permission::create(['name' => 'Manage Posts']);
         $createPosts = Permission::create(['name' => 'Create Posts', 'parent_id' => $managePosts->id]);
         $editPosts = Permission::create(['name' => 'Edit Posts', 'parent_id' => $managePosts->id]);
         $deletePosts = Permission::create(['name' => 'Delete Posts', 'parent_id' => $managePosts->id]);

         // Assign Permissions to Roles
         $adminRole->givePermissionTo(Permission::all());
         $editorRole->givePermissionTo([$createPosts, $editPosts, $deletePosts]);

    }
}
