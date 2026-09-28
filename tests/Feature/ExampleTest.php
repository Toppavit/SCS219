<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_homepage_redirects_to_the_weight_tracker(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('weights.index'));
    }

    public function test_the_about_me_page_displays_the_student_profile(): void
    {
        $this->get('/about-me')
            ->assertOk()
            ->assertSee('Pawich Rodstain')
            ->assertSee('68222420015');
    }
}
