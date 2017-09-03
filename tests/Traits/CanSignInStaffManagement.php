<?php

namespace Tests\Traits;

/**
 * Class CanSignInUsersManagement.
 *
 * @package Tests
 */
trait CanSignInStaffManagement
{
    use CreatesModels;

    /**
     * Sign in into application.
     *
     * @param null $user
     * @param null $driver
     * @return $this
     */
    protected function signIn($user = null, $driver = null)
    {
        $user = $user ?: $this->create('App\User');

        $this->actingAs($user, $driver);

        view()->share('signedIn',true);
        view()->share('user', $user);

        return $this;
    }

    /**
     * Sign in into application as staff manager.
     *
     * @param null $driver
     * @return $this
     */
    protected function signInAsStaffManager($driver = null)
    {
        return $this->signInWithRole('manage-staff', $driver);
    }

    /**
     * Sign in with role.
     *
     * @param $role
     * @param null $driver
     * @return $this
     */
    protected function signInWithRole($role, $driver = null)
    {
        $user = $this->create('App\User');
        $user->assignRole($role);
        $this->signIn($user,$driver);
        return $this;
    }

}