<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends PermissionsTableSeeder
{

    /**
     * The permissions.
     *
     * @var array
     */
    public $roles = [
        'Super Admin',
        'Admin',
        'Editor',
        'Author',
        'Contributor',
    ];
    

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            collect($this->roles)->each(function ($roleName) {
                $variableRoleName = lcfirst(str_replace(' ', '', $roleName));
                
                ${$variableRoleName} = Role::create([
                    'name' => $roleName,
                ]);
            });
        });
    }
}
