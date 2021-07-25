<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends PermissionsTableSeeder
{
    /**
     * The user name.
     *
     * @var array
     */
    protected $userNames = [
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
            
            collect($this->userNames)->each(function ($name) {
                $userName = lcfirst(str_replace(' ', '', $name));

                ${$userName} = User::create([
                    'name' => $name,
                    'email' => strtolower(join('-',explode(' ', $name))). "@gmail.com",
                    'password' => bcrypt('demo1234'),
                ]);

                $role = Role::where('name', $name)->first();

                ${$userName}->syncRoles($role);
                ${$userName}->syncPermissions($this->{$userName."Permissions"});
            });
        });
    }
}
