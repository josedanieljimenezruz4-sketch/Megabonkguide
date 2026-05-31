<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Item;
use App\Models\Build;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Crear Usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'username' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Crear 4 Ítems básicos (sin campo rarity)
        $itemsData = [
            [
                'id' => 'arma-01',
                'name' => 'Espada Aniquiladora',
                'description' => 'Un arma letal para corto alcance.',
                'type' => 'arma',
                'rank' => 'S',
                'image_path' => 'placeholder.png',
                'votes' => 0,
            ],
            [
                'id' => 'arma-02',
                'name' => 'Arco Celestial',
                'description' => 'Perfecto para atacar desde lejos.',
                'type' => 'arma',
                'rank' => 'A',
                'image_path' => 'placeholder.png',
                'votes' => 0,
            ],
            [
                'id' => 'pj-01',
                'name' => 'Guerrero Valeroso',
                'description' => 'Personaje con alta defensa y ataque.',
                'type' => 'personaje',
                'rank' => 'S',
                'image_path' => 'placeholder.png',
                'votes' => 0,
            ],
            [
                'id' => 'pj-02',
                'name' => 'Maga Suprema',
                'description' => 'Personaje experto en hechizos.',
                'type' => 'personaje',
                'rank' => 'A',
                'image_path' => 'placeholder.png',
                'votes' => 0,
            ]
        ];

        foreach ($itemsData as $item) {
            Item::updateOrCreate(['id' => $item['id']], $item);
        }

        // 3. Crear 2 Builds asociadas al Admin y a los ítems
        Build::firstOrCreate(
            ['name' => 'Build Ofensiva Total'],
            [
                'user_id' => $admin->id,
                'character_id' => 'pj-01',
                'description' => 'Build enfocada en maximizar el daño.',
                'rating' => 5,
                'type' => 'DPS'
            ]
        );

        Build::firstOrCreate(
            ['name' => 'Build Mágica Equilibrada'],
            [
                'user_id' => $admin->id,
                'character_id' => 'pj-02',
                'description' => 'Balance perfecto entre ataque mágico y defensa.',
                'rating' => 4,
                'type' => 'Healer'
            ]
        );
    }
}
