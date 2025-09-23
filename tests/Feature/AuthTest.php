<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test; // ← Adicione esta linha

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // ← Use attribute em vez de /** @test */
    public function usuario_nao_autenticado_e_redirecionado_para_login()
    {
        $response = $this->get('/home');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function usuario_autenticado_pode_ver_pagina_home()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/home');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    #[Test]
    public function usuario_pode_fazer_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }
}