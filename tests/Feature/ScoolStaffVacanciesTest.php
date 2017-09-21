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
     * @test
     * @return void
     */
    public function check_authorization_uri_api_vacancies()
    {
        $this->check_authorization_uri_api('/api/v1/vacancies');
    }

    /**
     * Api show vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_show_no_vacancies_for_authorized_users_correctly()
    {
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/vacancies')
            ->assertStatus(200)
            ->assertJson([
                'current_page' => 1,
                'data' => [],
                'first_page_url' => 'http://localhost/api/v1/vacancies?page=1',
                'from' => null,
                'last_page' => 1,
                'last_page_url' => 'http://localhost/api/v1/vacancies?page=1',
                'next_page_url' => null,
                'path' => 'http://localhost/api/v1/vacancies',
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => null,
                'total' => 0
            ]);
    }

    /**
     * Api show vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_show_vacancies_for_authorized_users_correctly()
    {
        seed_vacancies();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/vacancies')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id',
                    'id',
                    'speciality_id',
                    'department_id',
                    'order',
                    'owner',
                    'state',
                    'created_at',
                    'updated_at'
                ]
                ]])
            ->assertJsonFragment([
                'current_page' => 1,
                'first_page_url' => 'http://localhost/api/v1/vacancies?page=1',
                'from' => 1,
                'last_page' => 8,
                'last_page_url' => 'http://localhost/api/v1/vacancies?page=8',
                'next_page_url' => 'http://localhost/api/v1/vacancies?page=2',
                'path' => 'http://localhost/api/v1/vacancies',
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => 15,
                'total' => count_vacancies()
            ]);
    }

    /**
     * Api show vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_show_no_vacancies_without_pagination_for_authorized_users_correctly()
    {
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/vacancies?paginate=false')
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => 0
                ]
            ]);
    }

    /**
     * Api show vacancies without pagination for authorized users correctly.
     *
     * @test
     */
    public function api_show_vacancies_without_pagination_for_authorized_users_correctly()
    {
        seed_vacancies();
        $this->signInAsStaffManager('api')
            ->json('GET', '/api/v1/vacancies?paginate=false')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id',
                    'id',
                    'code',
                    'created_at',
                    'updated_at'
                ]
            ]])
            ->assertJsonFragment([
                'meta' => [
                    'total' => count_vacancies()
                ]
            ]);
    }

    /**
     * Api creates vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_creates_vacancies_for_authorized_users_correctly()
    {
        seed_teachers();
        seed_departments();

        $vacancy = [
            'speciality_id' => obtainSpecialityIdByCode('CAS'),
            'department_id' => $deparment = obtainDepartmentIdByEspecialityCode('CAS'),
            'order' => 1,
            'owner' => $teacher = obtainTeacherIdByCode('02'),
            'state' =>  'pending'
        ];

        $this->signInAsStaffManager('api')
            ->json('POST', '/api/v1/vacancies/', $vacancy)
            ->assertStatus(201)
            ->assertJson( [
                'message' => 'Vacancy successfully created.'
            ]);

        $this->assertDatabaseHas('vacancies', $vacancy);
    }

    /**
     * Api validates vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_validates_vacancies_for_authorized_users_correctly()
    {
        $vacancy = [
            'code' => '',
            'state' => '',
            'speciality_id' => ''
        ];
        $this->signInAsStaffManager('api')
            ->json('POST', '/api/v1/vacancies/', $vacancy)
            ->assertStatus(422)
            ->assertJson( [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'speciality_id' => [
                        'The speciality id field is required.'
                    ],
                    "department_id" => [
                        "The department id field is required."
                    ],
                    "order" => [
                        "The order field is required."
                    ],
                    "owner" => [
                        "The owner field is required."
                    ]

                ]
            ]);

        $this->assertDatabaseMissing('vacancies', $vacancy);
    }

    /**
     * Api creates vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_updates_vacancies_for_authorized_users_correctly()
    {
        seed_teachers();
        seed_departments();
        first_or_create_speciality('CAS' , 'Curs Accés Grau Superior', '','');
        $newSpeciality = first_or_create_speciality('AN' ,  'Anglès', '','');
        $vacancy = vacancy_first_or_create(
            obtainSpecialityIdByCode('CAS'),
            $deparment = obtainDepartmentIdByEspecialityCode('CAS'),
            1,
            $teacher = obtainTeacherIdByCode('02'),
            "assigned"
        );

        $this->assertDatabaseHas('vacancies', [ 'id' => $vacancy->id ]);
        $newVacancy = [
            'speciality_id' => $newSpeciality->id,
            'department_id' => $deparment,
            'order' => '1',
            'owner' => $teacher,
            'state' => 'active',
        ];
        $this->signInAsStaffManager('api')
            ->json('PUT', '/api/v1/vacancies/' . $vacancy->id, $newVacancy)
            ->assertStatus(200)
            ->assertJson( [
                'message' => 'Vacancy successfully updated.'
            ]);

        $this->assertDatabaseHas('vacancies', $newVacancy);
    }

    /**
     * Api validates on update vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_validates_on_update_vacancies_for_authorized_users_correctly()
    {
        seed_teachers();
        seed_departments();
        first_or_create_speciality('CAS' , 'Curs Accés Grau Superior', '','');
        $vacancy = vacancy_first_or_create(
            obtainSpecialityIdByCode('CAS'),
            $deparment = obtainDepartmentIdByEspecialityCode('CAS'),
            1,
            $teacher = obtainTeacherIdByCode('02'),
            "assigned"
        );
        $this->assertDatabaseHas('vacancies', [ 'id' => $vacancy->id ]);
        $newVacancy = [

        ];
        $this->signInAsStaffManager('api')
            ->json('PUT', '/api/v1/vacancies/' . $vacancy->id, $newVacancy)
            ->assertStatus(422)

            ->assertJson( [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'speciality_id' => [
                        'The speciality id field is required.'
                    ],
                    'department_id' => [
                        "The department id field is required."
                    ],
                    'owner' => [
                        "The owner field is required."
                    ]
                ]
            ]);

    }

    /**
     * Api deletes vacancies for authorized users correctly.
     *
     * @test
     */
    public function api_deletes_vacancies_for_authorized_users_correctly()
    {
        seed_departments();
        seed_teachers();
        $vacancy = vacancy_first_or_create(
            obtainSpecialityIdByCode('CAS'),
            $deparment = obtainDepartmentIdByEspecialityCode('CAS'),
            1,
            $teacher = obtainTeacherIdByCode('02'),
            "assigned"
        );
        $this->assertDatabaseHas('vacancies', [ 'id' => $vacancy->id ]);
        $this->signInAsStaffManager('api')
            ->json('DELETE', '/api/v1/vacancies/' . $vacancy->id)
            ->assertStatus(200)
            ->assertJson( [
                'message' => 'Vacancy successfully deleted.'
            ]);
        $this->assertDatabaseMissing('vacancies', [ 'id' => $vacancy->id ]);
    }

}

