<?php

namespace Tests\Unit;

use Acacha\Scool\Staff\Models\Teacher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    /**
     * Prova.
     *
     * @group prova
     * @return void
     */
    public function testProva()
    {
        $teacher = Teacher::create([
            'code' => '1165',
            'user_id' => 1112,
            'state' => 'pending'
        ]);
        $teacher->specialities;
        $this->assertTrue(true);
    }
}
