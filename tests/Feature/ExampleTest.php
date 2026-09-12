<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_the_landing_page_introduces_doqueue(): void
    {
        $this->get('/')->assertOk()->assertSee('DoQueue')->assertSee('Know what to do next.')->assertSee('Get Started');
    }
}
