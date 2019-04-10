<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table.
     *
     * @return void
     */
    public function run(): void
    {
        Role::create([
            'name'  => Role::ADMINISTRATOR,
            'title' => 'Administrator',
        ]);

        Role::create([
            'name'  => Role::MODERATOR,
            'title' => 'Data Moderator',
        ]);

        Role::create([
            'name'  => Role::CONTRIBUTOR,
            'title' => 'Data Contributor',
        ]);
    }
}
