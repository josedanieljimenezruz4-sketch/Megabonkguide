<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Item;
use App\Models\Build;
use App\Models\MetaStrategy;
use App\Models\GameInfo;
use App\Models\Faq;
use App\Models\CommunityPost;
use App\Models\Score;
use App\Models\TierList;
use App\Models\TierListRow;

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

        // 2. Definir los ítems: 2 Personajes, 4 Armas, 4 Tomos, 6 Ítems
        $itemsData = [
            // Personajes (2)
            [
                'id' => 'pj-01',
                'name' => 'Kael el Superviviente',
                'description' => 'Un veterano curtido en mil batallas, experto en armas cuerpo a cuerpo.',
                'requirement' => 'Desbloqueado por defecto.',
                'type' => 'personaje',
                'rank' => 'S',
                'image_path' => 'items/default-pj.webp',
                'votes' => 0,
            ],
            [
                'id' => 'pj-02',
                'name' => 'Lyra la Maga Sombría',
                'description' => 'Especialista en magia oscura y control de masas.',
                'requirement' => 'Sobrevive 30 minutos en el mapa del Bosque Maldito.',
                'type' => 'personaje',
                'rank' => 'A',
                'image_path' => 'items/default-pj.webp',
                'votes' => 0,
            ],

            // Armas (4)
            [
                'id' => 'arma-01',
                'name' => 'Hacha de Hierro',
                'description' => 'Lenta pero letal. Golpea en un arco amplio frente a ti.',
                'requirement' => 'Alcanza el nivel 5 con cualquier personaje.',
                'type' => 'arma',
                'rank' => 'C',
                'image_path' => 'items/default-arma.webp',
                'votes' => 0,
            ],
            [
                'id' => 'arma-02',
                'name' => 'Lanza Perforante',
                'description' => 'Atraviesa múltiples enemigos en línea recta.',
                'requirement' => 'Derrota a 500 enemigos de tipo insecto.',
                'type' => 'arma',
                'rank' => 'B',
                'image_path' => 'items/default-arma.webp',
                'votes' => 0,
            ],
            [
                'id' => 'arma-03',
                'name' => 'Dagas Gemelas',
                'description' => 'Ataques extremadamente rápidos, ideal para daño de un solo objetivo.',
                'requirement' => 'Encuentra el altar del ladrón.',
                'type' => 'arma',
                'rank' => 'A',
                'image_path' => 'items/default-arma.webp',
                'votes' => 0,
            ],
            [
                'id' => 'arma-04',
                'name' => 'Martillo Meteoro',
                'description' => 'Provoca temblores que ralentizan a los enemigos cercanos.',
                'requirement' => 'Mejora un arma al nivel máximo.',
                'type' => 'arma',
                'rank' => 'S',
                'image_path' => 'items/default-arma.webp',
                'votes' => 0,
            ],

            // Tomos (4)
            [
                'id' => 'tomo-01',
                'name' => 'Libro de Fuego',
                'description' => 'Dispara bolas de fuego aleatorias a los enemigos.',
                'requirement' => 'Quema a 100 enemigos.',
                'type' => 'tomo',
                'rank' => 'B',
                'image_path' => 'items/default-tomo.webp',
                'votes' => 0,
            ],
            [
                'id' => 'tomo-02',
                'name' => 'Grimorio Helado',
                'description' => 'Crea un aura congelante que daña y congela a los enemigos.',
                'requirement' => 'Sobrevive 10 minutos sin recibir daño.',
                'type' => 'tomo',
                'rank' => 'A',
                'image_path' => 'items/default-tomo.webp',
                'votes' => 0,
            ],
            [
                'id' => 'tomo-03',
                'name' => 'Códice del Vacío',
                'description' => 'Invoca agujeros negros temporales.',
                'requirement' => 'Derrota al jefe del Abismo.',
                'type' => 'tomo',
                'rank' => 'S',
                'image_path' => 'items/default-tomo.webp',
                'votes' => 0,
            ],
            [
                'id' => 'tomo-04',
                'name' => 'Papiro Eléctrico',
                'description' => 'Lanza rayos encadenados entre grupos de enemigos.',
                'requirement' => 'Recolecta 50 orbes de energía en una sola partida.',
                'type' => 'tomo',
                'rank' => 'C',
                'image_path' => 'items/default-tomo.webp',
                'votes' => 0,
            ],

            // Ítems (6)
            [
                'id' => 'item-01',
                'name' => 'Collar de Ajo',
                'description' => 'Reduce el daño recibido de monstruos no-muertos.',
                'requirement' => 'Encuentra 10 ajos en cofres.',
                'type' => 'item',
                'rank' => 'C',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ],
            [
                'id' => 'item-02',
                'name' => 'Botas Aladas',
                'description' => 'Aumenta significativamente la velocidad de movimiento.',
                'requirement' => 'Camina 10,000 pasos.',
                'type' => 'item',
                'rank' => 'B',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ],
            [
                'id' => 'item-03',
                'name' => 'Anillo Sanguijuela',
                'description' => 'Robas un 1% de salud por cada ataque crítico.',
                'requirement' => 'Cura 5000 puntos de vida.',
                'type' => 'item',
                'rank' => 'S',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ],
            [
                'id' => 'item-04',
                'name' => 'Amuleto del Rey',
                'description' => 'Aumenta un 10% todas las estadísticas.',
                'requirement' => 'Gana una partida en la máxima dificultad.',
                'type' => 'item',
                'rank' => 'S',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ],
            [
                'id' => 'item-05',
                'name' => 'Capa de Invisibilidad',
                'description' => 'Tienes un 5% de probabilidad de evadir cualquier daño.',
                'requirement' => 'Esquiva 1000 ataques.',
                'type' => 'item',
                'rank' => 'A',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ],
            [
                'id' => 'item-06',
                'name' => 'Reloj de Arena Roto',
                'description' => 'Los enemigos aparecen un 5% más lento.',
                'requirement' => 'Juega un total de 10 horas.',
                'type' => 'item',
                'rank' => 'B',
                'image_path' => 'items/default-item.webp',
                'votes' => 0,
            ]
        ];

        foreach ($itemsData as $item) {
            Item::updateOrCreate(['id' => $item['id']], $item);
        }

        // 3. Crear Estrategias Dominantes (Meta Strategies)
        $metaDps = MetaStrategy::firstOrCreate(
            ['title' => 'Estrategia DPS'],
            ['description' => 'Maximiza el daño para eliminar enemigos rápidamente.', 'build_type' => 'DPS', 'is_active' => true]
        );

        $metaSoporte = MetaStrategy::firstOrCreate(
            ['title' => 'Estrategia Soporte'],
            ['description' => 'Control de masas y apoyo para facilitar la partida.', 'build_type' => 'Soporte', 'is_active' => true]
        );

        $metaHealer = MetaStrategy::firstOrCreate(
            ['title' => 'Estrategia Healer'],
            ['description' => 'Prioriza la curación y la supervivencia extrema.', 'build_type' => 'Healer', 'is_active' => true]
        );

        // 4. Crear 3 Builds asociadas al Admin, a las estrategias y a los ítems
        $build1 = Build::firstOrCreate(
            ['name' => 'Build de Daño Rápido'],
            [
                'user_id' => $admin->id,
                'character_id' => 'pj-01',
                'description' => 'Una build excelente para limpiar rápidamente olas de enemigos usando ataques veloces.',
                'rating' => 5,
                'type' => 'DPS',
                'meta_strategy_id' => $metaDps->id
            ]
        );
        // Asociar ítems a la build
        $build1->items()->sync([
            'arma-03' => ['slot_type' => 'Arma'], // Dagas Gemelas
            'tomo-04' => ['slot_type' => 'Tomo'], // Papiro Eléctrico
            'item-02' => ['slot_type' => 'Item']  // Botas Aladas
        ]);

        $build2 = Build::firstOrCreate(
            ['name' => 'Mago de Control y Soporte'],
            [
                'user_id' => $admin->id,
                'character_id' => 'pj-02',
                'description' => 'Mantén a los enemigos a raya congelándolos y ralentizándolos constantemente.',
                'rating' => 4,
                'type' => 'Soporte',
                'meta_strategy_id' => $metaSoporte->id
            ]
        );
        // Asociar ítems a la build
        $build2->items()->sync([
            'tomo-02' => ['slot_type' => 'Tomo'], // Grimorio Helado
            'tomo-03' => ['slot_type' => 'Tomo'], // Códice del Vacío
            'item-06' => ['slot_type' => 'Item']  // Reloj de Arena Roto
        ]);

        $build3 = Build::firstOrCreate(
            ['name' => 'Superviviente Healer'],
            [
                'user_id' => $admin->id,
                'character_id' => 'pj-01',
                'description' => 'Recuperación constante de salud gracias a los golpes críticos y curaciones.',
                'rating' => 5,
                'type' => 'Healer',
                'meta_strategy_id' => $metaHealer->id
            ]
        );
        // Asociar ítems a la build
        $build3->items()->sync([
            'arma-01' => ['slot_type' => 'Arma'], // Hacha de Hierro
            'item-03' => ['slot_type' => 'Item'], // Anillo Sanguijuela
            'item-01' => ['slot_type' => 'Item']  // Collar de Ajo
        ]);

        // 5. Crear Información del Juego (GameInfo y FAQs)
        $gameInfos = [
            ['title' => 'Sobre Megabonk', 'content' => 'Megabonk es un juego roguelite de supervivencia extrema donde cada decisión cuenta.', 'category' => 'General'],
            ['title' => 'Cómo jugar', 'content' => 'Recoge objetos, sobrevive oleadas y desbloquea nuevas builds.', 'category' => 'Guía'],
            ['title' => 'Mecánicas Base', 'content' => 'Descubre sinergias únicas al combinar armas con su tomo correspondiente. ¡La experimentación es clave para la supervivencia!', 'category' => 'Mecánicas']
        ];

        $faq = [
            ['title' => '¿Es multijugador?', 'content' => 'Por ahora, ¡conquista las tablas de clasificación en solitario!', 'category' => 'FAQ'],
            ['title' => '¿Cómo puedo subir mis propias builds?', 'content' => 'Debes iniciar sesión con tu cuenta de Discord y dirigirte al apartado de Builds dentro de tu perfil.', 'category' => 'FAQ'],
            ['title' => '¿El juego soporta mando?', 'content' => 'Megabonk está optimizado tanto para teclado y ratón como para mando de consola.', 'category' => 'FAQ'],
            ['title' => '¿Cómo funciona el Leaderboard?', 'content' => 'Tu puntuación se envía automáticamente al finalizar cada run exitosa; se guardan tus 10 mejores marcas personales.', 'category' => 'FAQ']
        ];

        foreach ($gameInfos as $info) {
            GameInfo::firstOrCreate(['title' => $info['title']], $info);
        }

        foreach ($faq as $f) {
            Faq::firstOrCreate(['title' => $f['title']], $f);
        }

        // 6. Crear Posts de la Comunidad
        $posts = [
            ['user_id' => $admin->id, 'title' => 'Mi primera victoria', 'content' => '¡Por fin logré vencer al jefe del Abismo con Lyra!', 'category' => 'build'],
            ['user_id' => $admin->id, 'title' => '¿Nerf a las Dagas Gemelas?', 'content' => 'El DPS es demasiado alto, ¿qué opinan?', 'category' => 'meta'],
            ['user_id' => $admin->id, 'title' => '¿Cuándo sale el nuevo parche?', 'content' => 'Llevo esperando meses.', 'category' => 'question']
        ];
        foreach ($posts as $post) {
            CommunityPost::firstOrCreate(['title' => $post['title']], $post);
        }

        // 7. Crear Puntuaciones (Leaderboard)
        $scores = [
            ['user_id' => $admin->id, 'character_id' => 'pj-01', 'build_id' => $build1->id, 'points' => 35000, 'time' => '30:00', 'status' => 'approved'],
        ];
        foreach ($scores as $score) {
            Score::firstOrCreate(['points' => $score['points'], 'time' => $score['time']], $score);
        }

        // 8. Crear Tier Lists
        $tierPersonajes = TierList::firstOrCreate(
            ['titulo' => 'Meta de Personajes'],
            ['user_id' => $admin->id, 'categoria' => 'Personajes', 'descripcion' => 'Los mejores personajes para farmear oro']
        );
        TierListRow::firstOrCreate(['tier_list_id' => $tierPersonajes->id, 'item_id' => 'pj-01'], ['rank' => 'S']);
        TierListRow::firstOrCreate(['tier_list_id' => $tierPersonajes->id, 'item_id' => 'pj-02'], ['rank' => 'A']);

        $tierArmas = TierList::firstOrCreate(
            ['titulo' => 'Tier List de Armas'],
            ['user_id' => $admin->id, 'categoria' => 'Armas', 'descripcion' => 'Clasificación de armas según su daño por segundo']
        );
        TierListRow::firstOrCreate(['tier_list_id' => $tierArmas->id, 'item_id' => 'arma-04'], ['rank' => 'S']);
        TierListRow::firstOrCreate(['tier_list_id' => $tierArmas->id, 'item_id' => 'arma-03'], ['rank' => 'S']);
        TierListRow::firstOrCreate(['tier_list_id' => $tierArmas->id, 'item_id' => 'arma-02'], ['rank' => 'A']);
        TierListRow::firstOrCreate(['tier_list_id' => $tierArmas->id, 'item_id' => 'arma-01'], ['rank' => 'C']);
    }
}
