<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pagina_auditoria_requer_autenticacao()
    {
        $response = $this->get('/auditoria');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function usuario_autenticado_pode_ver_auditoria()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Cria um log de auditoria
        AuditLog::factory()->create();

        $response = $this->get('/auditoria');
        $response->assertStatus(200);
        $response->assertSee('Log de Auditoria');
    }
}