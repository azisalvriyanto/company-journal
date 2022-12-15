<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */
        if (config('roles.models.defaultUser')::where('email', '=', 'admin@sintas.space')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'group'             => 'User',
                'name'              => 'Admin',
                'email'             => 'admin@sintas.space',
                'password'          => bcrypt('password'),
                'parent_company_id' => NULL,
                'transaction_code'  => NULL,
                'is_enable'         => 1,
            ]);

            $newUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }

        $mainCompany = config('roles.models.defaultUser')::create([
            'group'     => 'Company',
            'name'      => 'Sintas Space',
            'email'     => NULL,
            'password'  => NULL,
        ]);

        $company = config('roles.models.defaultUser')::create([
            'group'             => 'Company',
            'name'              => 'Sintas',
            'email'             => NULL,
            'password'          => NULL,
            'parent_company_id' => $mainCompany->id,
            'transaction_code'  => 'SNTS',
            'is_enable'         => 1,
        ]);

        if (config('roles.models.defaultUser')::where('email', '=', 'user@sintas.space')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'group'             => 'User',
                'name'              => 'User',
                'email'             => 'user@sintas.space',
                'password'          => bcrypt('password'),
                'parent_company_id' => $company->id,
                'transaction_code'  => NULL,
                'is_enable'         => 1,
            ]);

            $newUser->attachRole($userRole);
        }
    }
}
