<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\Feature\Traits\ChecksURIsAuthorization;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\CanSignInStaffManagement;

/**
 * Class ScoolStaffVacanciesTest.
 *
 * @package Tests\Feature
 */
class ScoolStaffVacanciesTest extends TestCase
{
    use DatabaseMigrations, CanSignInStaffManagement, ChecksURIsAuthorization;

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
     * Test authorization for URI /teachers/{id}/user.
     *
     * @group prova
     * @test
     * @return void
     */
    public function check_authorization_uri_api_vacancies()
    {
        $this->check_authorization_uri_api('/api/v1/vacancies');
    }

}

