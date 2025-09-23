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
        // A rota raiz '/' redireciona, então testamos o redirecionamento
        $response = $this->get('/');
        
        // Como redireciona, esperamos status 302 (redirect)
        $response->assertStatus(302);
        
        // E verifica se redireciona para a página correta
        $response->assertRedirect('/login'); // ou /home, dependendo da sua configuração
    }
    
    /**
     * Testa se a página de login carrega corretamente
     */
    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    /**
     * Testa se a página home carrega para usuário autenticado
     */
    public function test_home_page_loads_for_authenticated_user(): void
    {
        // Cria um usuário e faz login
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->get('/home');
        $response->assertStatus(200);
    }
}