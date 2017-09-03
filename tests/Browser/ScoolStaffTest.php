<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class ScoolStaffTest.
 *
 * @package Tests\Browser
 */
class ScoolStaffTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Unauthorized users see users management menu entry.
     *
     * @group TODO
     * @test
     * @return void
     */
    public function unauthorized_users_dont_see_users_management_menu_entry()
    {
        dump(__FUNCTION__ );

        $user = $this->createUsers();
        $this->browse(function (Browser $browser) use ($user){
            $this->login($browser,$user)
                ->visit('/home')
                ->assertDontSeeLink('Users');
        });

        $this->logout();
    }

    /**
     * Authorized users see users management menu entry.
     *
     * @test
     * @return void
     */
    public function authorized_users_see_users_managment_menu_entry()
    {
        dump(__FUNCTION__ );
//        $manager = $this->createUserManagerUser();
//
//        $this->browse(function (Browser $browser) use ($manager) {
//            $this->login($browser,$manager)
//                ->visit('/management/users')
//                ->assertTitleContains('Users Management')
//                ->assertSeeLink('Users');
//        });
//
//        $this->logout();
    }

    /**
     * Create users.
     *
     * @param null $number
     * @return mixed
     */
    private function createUsers($number = null)
    {
        return $this->createModels(User::class,$number);
    }

    /**
     * Create models.
     *
     * @param $model
     * @param null $number
     * @return mixed
     */
    private function createModels($model, $number = null) {
        $model = factory($model , $number)->create();
        return $model;
    }


}
