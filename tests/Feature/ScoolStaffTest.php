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
     * Test authorization for URI /teachers/{id}/user.
     *
     * @test
     * @return void
     */
    public function test_authorization_for_uri_assign_user_to_teacher()
    {
        $this->check_authorization_uri('/teachers/{id}/user');
    }

    /**
     * Assert view for assign user to teacher
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
     * Test authorization for teachers wizard.
     *
     * @test
     * @return void
     */
    public function test_authorization_for_uri_teachers_wizard()
    {
        $this->check_authorization_uri('/management/teachers/wizard');
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
     * @group prova
     * @test
     */
    public function api_show_an_user_for_authorized_users_correctly()
    {
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers')
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'from' => null,
                    'last_page' => 1,
                    'per_page' => 15,
                    'to' => null,
                ],
                'links' => [
                    'prev' => null,
                    'next' => null,
                ]
            ]);
    }

    /**
     * Api show an user for authorized users correctly after seeding.
     *
     * @test
     */
    public function api_show_an_user_for_authorized_users_correctly_after_seeding()
    {
        seed_teachers();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers')
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'to' => 8,
                ],
                'links' => [
                    'prev' => null,
                    'next' => null,
                ]
            ]);
    }

    /**
     * Api show an user for authorized users correctly after seeding with structure.
     *
     * @test
     */
    public function api_show_an_user_for_authorized_users_correctly_after_seeding_with_structure()
    {
        seed_teachers();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'code',
                    'state',
                    'user',
                    'vacancy',
                    'specialities',
                    'administrative_status_id',
                    'administrative_start_year',
                    'opossitions_pass_year',
                    'start_date',
                    'created_at',
                    'updated_at'
                ]]
            ]);
    }

    /**
     * Api show an user for authorized users correctly without pagination.
     *
     * @test
     */
    public function api_show_an_user_for_authorized_users_correctly_without_pagination()
    {
        seed_teachers();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/teachers?paginate=false')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id',
                'code',
                'state',
                'specialities',
                'vacancy',
                'specialities',
                'administrative_status_id',
                'administrative_start_year',
                'opossitions_pass_year',
                'start_date',
                'created_at',
                'updated_at'
                    ]
            ]])->assertJsonMissing([
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
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

}

