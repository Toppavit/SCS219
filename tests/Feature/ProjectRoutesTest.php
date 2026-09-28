<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_shows_hero(): void
    {
        $this->get('/gallery')->assertOk()->assertSee('แกลเลอรีผลงาน');
    }

    public function test_active_bootstrap_pages_render_with_active_menu(): void
    {
        foreach (['index', 'about', 'services', 'portfolio', 'team', 'blog', 'contact'] as $page) {
            $this->get("/active/{$page}")->assertOk();
        }

        $this->get('/active/about')->assertSee('class="nav-link active"', false);
    }

    public function test_about_me_links_to_previous_work(): void
    {
        $this->get('/about-me')
            ->assertSee(route('gallery'))
            ->assertSee(route('index'))
            ->assertSee(route('login'));
    }

    public function test_guests_are_sent_to_login_from_weights(): void
    {
        $this->get('/weights')->assertRedirect(route('login'));
    }

    public function test_logged_in_users_can_see_weights_and_logout_button(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/weights')
            ->assertOk()
            ->assertSee('Logout');
    }

    public function test_login_goes_to_weights(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertRedirect(route('weights.index'));
    }
}
