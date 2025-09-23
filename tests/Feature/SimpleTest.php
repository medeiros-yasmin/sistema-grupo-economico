<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SimpleTest extends TestCase
{
    #[Test]
    public function pagina_login_carrega_corretamente()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Entrar'); // Ou texto que aparece na sua página de login
    }

    #[Test]
    public function pagina_registro_carrega_corretamente()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Registrar'); // Ou texto que aparece na sua página de registro
    }

    #[Test]
    public function rota_raiz_redireciona_para_login()
    {
        $response = $this->get('/');
        $response->assertStatus(302); // Redireciona
        $response->assertRedirect('/login'); // Ou para /home se já estiver logado
    }
}