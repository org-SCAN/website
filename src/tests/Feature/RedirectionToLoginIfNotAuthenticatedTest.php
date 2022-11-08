<?php

namespace Tests\Feature;


use Tests\TestCase;

class RedirectionToLoginIfNotAuthenticatedTest extends TestCase
{
    /**
     * Test redirection to login if not authenticated from /
     *
     * @return void
     */
    public function test_redirection_from_basic()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /**
     * Test redirection to login if not authenticated from /person
     *
     * @return void
     */
    public function test_redirection_from_person()
    {
        $response = $this->get('/person');
        $response->assertRedirect('/login');
    }

    /**
     * Test redirection to login if not authenticated from /user
     *
     * @return void
     */
    public function test_Redirection_from_user()
    {
        $response = $this->get('/user');
        $response->assertRedirect('/login');
    }
}
