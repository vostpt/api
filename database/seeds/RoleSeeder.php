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
            'name'  => Role::ADMIN,
            'title' => 'Administrator',
        ]);

        Role::create([
            'name'  => Role::WRITER,
            'title' => 'Active User',
        ]);

        Role::create([
            'name'  => Role::READER,
            'title' => 'Passive User',
        ]);
    }
}
