<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckRootTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRootAccess()
    {
        $this->visit('/')
            ->see('Laravel');
    }
}
