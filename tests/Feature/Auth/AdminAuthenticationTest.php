<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login/admin');

        $response->assertStatus(200);
    }

    public function test_old_admin_login_url_redirects(): void
    {
        $response = $this->get('/admin/login');

        $response->assertRedirect('/login/admin');
    }

    public function test_staff_can_authenticate_via_admin_login(): void
    {
        Role::findOrCreate('super-admin');
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $response = $this->post('/login/admin', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_customer_cannot_authenticate_via_admin_login(): void
    {
        Role::findOrCreate('customer');
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->post('/login/admin', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}
