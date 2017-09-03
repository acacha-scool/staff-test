<?php

namespace Tests\Feature;

use Auth;
use Illuminate\Support\Facades\App;
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
    use DatabaseMigrations, CanSignInStaffManagement;

    /**
     * Set up tests.
     */
    public function setUp()
    {
        parent::setUp();
        App::setLocale('en');
        initialize_staff_management_permissions();
    }

    /**
     * Unauthorized user cannot browse assign_user_to_teacher.
     *
     * @test
     * @return void
     */
    public function unauthorized_user_cannot_browse_assign_user_to_teacher()
    {
        $response = $this->get('/teachers/{id}/user');

        $response->assertStatus(302);
    }

    /**
     * A user cannot browse TODO.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_browse_assign_user_to_teacher()
    {
        $this->signIn();
        $response = $this->get('/teachers/{id}/user');
        $response->assertStatus(403);
    }

    /**
     * Authorized user can browse TODO.
     *
     * @test
     * @return void
     */
    public function authorized_user_can_browse_assign_user_to_teacher()
    {
        $response = $this->signInAsStaffManager()
            ->get('/teachers/{id}/user');
        $response->assertStatus(200)->assertViewIs('acacha_scool_staff::assign-user-to-teacher');
//        ->assertViewHas('sda');
    }

    /**
     * Api show a 302 error listing all users if request is not done by XHR.
     *
     * @test
     */
    public function api_show_302_listing_all_teachers_if_no_xhr_request()
    {
        $this->get('/api/v1/teachers')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /**
     * Api show 401 listing all users for unauthorized users.
     *
     * @test
     */
    public function api_show_401_listing_all_users_for_unauthorized_users()
    {
        $this->json('GET','/api/v1/teachers')
            ->assertStatus(401);
    }

    /**
     * Api show an user for authorized users correctly.
     *
     * @test
     */
    public function api_show_an_user_for_authorized_users_correctly()
    {
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers')
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 1,
                'data' => [],
                'from' => null,
                'last_page' => 1,
                'next_page_url' => null,
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => null,
                'total' => 0
            ]);
    }

}
