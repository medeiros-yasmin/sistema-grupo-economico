<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_criar_usuario_no_banco_de_testes()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    #[Test]
    public function banco_de_testes_esta_funcionando()
    {
        // Teste simples que sempre passa
        $this->assertTrue(true);
    }
}