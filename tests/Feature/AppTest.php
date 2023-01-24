<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * A basic test to make sure that app returns a successful response
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Get the root index page
        $response = $this->get('/');

        // Make sure it was a successful request
        $response->assertStatus(200);
    }
}
