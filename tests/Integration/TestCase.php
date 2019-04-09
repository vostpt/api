<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

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
