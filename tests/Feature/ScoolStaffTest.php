<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\Feature\Traits\ChecksURIsAuthorization;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\CanSignInStaffManagement;

/**
 * Class ScoolStaffTest.
 *
 * @package Tests\Feature
 */
class ScoolStaffTest extends TestCase
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
     * Assert view for laravel passport tokens.
     *
     * @test
     * @return void
     */
    public function authorized_user_can_browse_laravel_passport_tokens()
    {
        $response = $this->signInAsStaffManager()
            ->get('/tokens');
        $response->assertStatus(200)->assertViewIs('tokens');
    }

    /**
     * Api show an user for authorized users correctly without pagination.
     *
     * @group caca
     * @test
     */
    public function api1()
    {
        seed_teachers();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers?paginate=false')
            ->assertStatus(200);
    }


}

