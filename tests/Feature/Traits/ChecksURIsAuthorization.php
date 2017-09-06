<?php

namespace Tests\Feature\Traits;

/**
 * Class ChecksURIsAuthorization
 */
trait ChecksURIsAuthorization
{
    /**
     * Test authorization and authentication for an URI.
     *
     * @param $uri
     */
    protected function check_authorization_uri($uri) {
        $this->unauthorized_user_cannot_browse_uri($uri);
        $this->an_user_cannot_browse_uri($uri);
        $this->authorized_user_can_browse_uri($uri);
    }

    /**
     * Test authorization and authentication for an URI api.
     *
     * @param $uri
     */
    protected function check_authorization_uri_api($uri) {
        $this->unauthorized_user_cannot_browse_uri($uri);
        $this->an_user_cannot_browse_uri_api($uri);
        $this->authorized_user_can_browse_uri_api($uri);
    }

    /**
     * Test unauthorized user cannot browse URL.
     *
     * @param $uri
     */
    protected function unauthorized_user_cannot_browse_uri($uri)
    {
        $response = $this->get($uri);
        $response->assertStatus(302);
    }

    /**
     * Test and user cannot browser URI.
     *
     * @param $uri
     */
    protected function an_user_cannot_browse_uri($uri)
    {
        $this->signIn();
        $response = $this->get($uri);
        $response->assertStatus(403);
    }

    /**
     * Test and user cannot browser URI.
     *
     * @param $uri
     */
    protected function an_user_cannot_browse_uri_api($uri)
    {
        $this->signIn();
        $response = $this->get($uri);
        $response->assertStatus(302);
    }

    /**
     *
     * Test an authorized user can browse URI.
     *
     * @param $uri
     */
    protected function authorized_user_can_browse_uri( $uri)
    {
        $this->signInAsStaffManager()
            ->get($uri)->assertStatus(200);
    }

    /**
     *
     * Test an authorized user can browse URI.
     *
     * @param $uri
     */
    protected function authorized_user_can_browse_uri_api( $uri)
    {
        $this->signInAsStaffManager('api')
            ->get($uri)->assertStatus(200);
    }

}