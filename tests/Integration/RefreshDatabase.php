<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

trait RefreshDatabase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', [
            '--class' => 'RoleSeeder',
        ]);
    }
}
