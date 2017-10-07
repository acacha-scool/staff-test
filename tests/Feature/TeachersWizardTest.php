<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class TeachersWizardTest.
 *
 * @package Tests\Feature
 */
class TeachersWizardTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Set up tests.
     */
    public function setUp()
    {
        parent::setUp();
        App::setLocale('en');
        initialize_staff_management_permissions();
//        $this->withoutExceptionHandling();
    }

    /**
     * Teacher wizard is public.
     *
     * @test
     * @return void
     */
    public function teacher_wizard_is_public()
    {
        $response = $this->get('/teacher');
        $response->assertStatus(200);
    }

}

