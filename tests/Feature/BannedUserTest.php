<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class BannedUserTest extends TestCase
{
    // Esta línea mágica hace que la base de datos se reinicie en cada test
    use RefreshDatabase; 

    /** @test */
    public function un_usuario_baneado_es_redirigido_a_la_prision()
    {
        // 1. PREPARACIÓN: Creamos un usuario falso baneado hasta mañana
        $usuarioBaneado = User::factory()->create([
            'banned_until' => now()->addDay(),
        ]);

        // 2. ACCIÓN: Simulamos que ese usuario intenta entrar a crear una build
        $response = $this->actingAs($usuarioBaneado)->get('/builds/create');

        // 3. AFIRMACIÓN (ASSERT): Comprobamos que Laravel lo redirige a /banned
        $response->assertRedirect('/banned');
    }

    /** @test */
    public function un_usuario_normal_puede_acceder_correctamente()
    {
        // 1. PREPARACIÓN: Creamos un usuario normal (sin ban)
        $usuarioNormal = User::factory()->create([
            'banned_until' => null,
        ]);

        // 2. ACCIÓN: Simulamos que entra a la misma ruta
        $response = $this->actingAs($usuarioNormal)->get('/builds/create');

        // 3. AFIRMACIÓN: Comprobamos que le carga la página bien (Status 200 OK)
        $response->assertStatus(200);
    }
}
