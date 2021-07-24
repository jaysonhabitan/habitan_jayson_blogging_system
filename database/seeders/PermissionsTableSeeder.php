<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * The super admin permissions.
     *
     * @var array
     */
    public $superAdminPermissions = [
        'manage-user',
        'create-user',
        'update-user',
        'delete-user',
        
        'manage-role',
        'manage-permission',

        'manage-post',
        'create-post',
        'update-post',
        'delete-post',

        'manage-category',
        'create-category',
        'update-category',
        'delete-category',

        'create-comment',
        'update-comment',
        'delete-comment',
    ];

    /**
     * The admin permissions.
     *
     * @var array
     */
    public $adminPermissions = [
        'manage-user',
        'create-user',
        'update-user',
        
        'manage-role',
        'manage-permission',

        'manage-post',
        'create-post',
        'update-post',
        'delete-post',

        'manage-category',
        'create-category',
        'update-category',
        'delete-category',

        'create-comment',
        'update-comment',
        'delete-comment',
    ];

    /**
     * The editor permissions.
     *
     * @var array
     */
    public $editorPermissions = [
        'manage-post',
        'create-post',
        'update-post',
        'delete-post',

        'manage-category',
        'create-category',
        'update-category',
        'delete-category',

        'create-comment',
        'update-comment',
        'delete-comment',
    ];

    /**
     * The author permissions.
     *
     * @var array
     */
    public $authorPermissions = [
        'manage-post',
        'create-post',
        'update-post',
        'delete-post',
    ];

    /**
     * The contributor permissions.
     *
     * @var array
     */
    public $contributorPermissions = [
        'create-post',
        'update-post',
        'delete-post',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            collect($this->superAdminPermissions)->each(function ($permission) {
                Permission::create([
                    'name' => $permission
                ]);
            });
        });
    }
}
