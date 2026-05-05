<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | MEGABONK GUIDE</title>
    <!-- Tailwind CSS (CDN o local según tu setup, si no funciona añade CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="icon" href="{{ asset('images/iconotlabaho.webp') }}?v=1" type="image/webp">
    <link rel="shortcut icon" href="{{ asset('images/iconotlabaho.webp') }}">
</head>
<body class="text-white flex flex-col min-h-screen">
    
    @include('partials.header')

    <main class="flex-grow">
        <!-- CÓDIGO PROPORCIONADO POR EL USUARIO -->
        <style>
            .glass-card {
                background: rgba(15, 15, 15, 0.8) !important;
                backdrop-filter: blur(10px) !important;
                -webkit-backdrop-filter: blur(10px) !important;
                border: 1px solid rgba(0, 255, 255, 0.2);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
                transition: all 0.3s ease;
            }
            .glass-card:hover {
                border-color: rgba(0, 255, 255, 0.6);
                transform: translateY(-5px);
                box-shadow: 0 0 25px rgba(0, 255, 255, 0.15);
            }
            .glow-orange:hover {
                border-color: rgba(255, 85, 0, 0.8) !important;
                box-shadow: 0 0 30px rgba(255, 85, 0, 0.4) !important;
            }
            .glow-purple:hover {
                border-color: rgba(168, 85, 247, 0.8) !important;
                box-shadow: 0 0 30px rgba(168, 85, 247, 0.4) !important;
            }
            .glow-cyan:hover {
                border-color: rgba(34, 211, 238, 0.8) !important;
                box-shadow: 0 0 30px rgba(34, 211, 238, 0.4) !important;
            }
            .modal-overlay {
                backdrop-filter: blur(8px);
                background: rgba(0, 0, 0, 0.8);
            }
        </style>

        <div class="container mx-auto px-4 py-4">
            <div class="text-center mb-8">
                <h1 class="text-6xl font-bold text-cyan-400 drop-shadow-[0_0_15px_rgba(34,211,238,0.8)] mb-2 uppercase tracking-tighter">
                    MEGABONK GUIDE
                </h1>
                <p class="text-gray-400 text-lg">Tu centro de mando para dominar el Meta de Megabonk.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="glass-card glow-orange rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <span class="text-2xl mr-2">🔥</span>
                            <h3 class="text-xl font-bold text-white uppercase italic">Último Parche</h3>
                        </div>
                        <h4 class="text-cyan-300 font-bold mb-2">{{ $latestUpdate->title ?? 'H A T S' }}</h4>
                        <p class="text-gray-400 text-sm line-clamp-3">
                            {{ Str::limit($latestUpdate->content ?? 'Cargando novedades...', 100) }}
                        </p>
                    </div>
                    <a href="/novedades" class="mt-4 text-cyan-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                        Ver Novedades →
                    </a>
                </div>

                <div class="glass-card glow-purple rounded-xl p-6 border-l-4 border-l-purple-500 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <span class="text-2xl mr-2">🏆</span>
                            <h3 class="text-xl font-bold text-white uppercase italic">Top Build</h3>
                        </div>
                        <h4 class="text-purple-400 font-bold mb-2">{{ $topBuild->name ?? 'Build de Prueba' }}</h4>
                        <p class="text-gray-400 text-sm">Por: <span class="text-white">{{ $topBuild->user->name ?? 'Anónimo' }}</span></p>
                        <p class="text-gray-400 text-sm">PJ: <span class="text-cyan-300">{{ $topBuild->character->name ?? 'Sombra la Asesina' }}</span></p>
                    </div>
                    <a href="/builds" class="mt-4 text-purple-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                        Ver Build →
                    </a>
                </div>

                <div class="glass-card glow-cyan rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <span class="text-2xl mr-2">💡</span>
                            <h3 class="text-xl font-bold text-white uppercase italic">Descubre</h3>
                        </div>
                        <h4 class="text-yellow-400 font-bold mb-2">{{ $randomItem->name ?? 'Anillo del Ojo Ciego' }}</h4>
                        <p class="text-gray-400 text-sm italic">"{{ Str::limit($randomItem->effect ?? $randomItem->description ?? 'Aumenta la probabilidad de crítico...', 100) }}"</p>
                    </div>
                    <a href="/info-general" class="mt-4 text-yellow-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                        Ir a la Wiki →
                    </a>
                </div>
            </div>

            <div class="bg-black/80 border border-gray-800 rounded-full py-4 px-8 flex justify-around items-center mb-8 max-w-4xl mx-auto shadow-[0_0_15px_rgba(0,0,0,0.8)]">
                <div class="text-center">
                    <span class="block text-2xl font-black text-white">{{ $buildsCount ?? 6 }}</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest">Builds</span>
                </div>
                <div class="h-8 w-[1px] bg-gray-800"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-white">{{ $usersCount ?? 3 }}</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest">Bonkers</span>
                </div>
                <div class="h-8 w-[1px] bg-gray-800"></div>
                <div class="text-center">
                    <span class="block text-2xl font-black text-white">{{ $newsCount ?? 10 }}</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest">Noticias</span>
                </div>
            </div>

            <div class="flex justify-center gap-6">
                <button onclick="toggleModal()" class="bg-purple-600 hover:bg-purple-500 text-white font-bold py-4 px-8 rounded-xl transition-all flex items-center shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                    <span class="mr-2">📧</span> ENVIAR SUGERENCIA
                </button>
                <a href="/info-general" class="bg-cyan-500 hover:bg-cyan-400 text-black font-black py-4 px-8 rounded-xl transition-all flex items-center shadow-[0_0_15px_rgba(6,182,212,0.4)] uppercase">
                    💡 Información General
                </a>
            </div>
        </div>

        <div id="suggestionModal" class="modal-overlay fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-[#121212] w-full max-w-md rounded-2xl p-8 relative border border-purple-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">
                <button onclick="toggleModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-2xl font-bold">&times;</button>
                
                <h2 class="text-2xl font-bold text-white mb-2 uppercase italic">Enviar Sugerencia</h2>
                <p class="text-gray-400 text-sm mb-6">¿Tienes alguna idea para mejorar la guía? ¡Te escuchamos!</p>
                
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500 text-green-300 p-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('comunity.suggestions.store') }}" method="POST">
                    @csrf
                    <label class="block text-gray-300 text-sm font-bold mb-2">Nombre de Usuario</label>
                    <input type="text" name="name" value="{{ auth()->check() ? auth()->user()->name : '' }}" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none mb-4 transition-all" placeholder="Tu nombre..." required>

                    <label class="block text-gray-300 text-sm font-bold mb-2">Asunto</label>
                    <input type="text" name="subject" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none mb-4 transition-all" placeholder="Ej. Nuevo diseño de builds" required>

                    <label class="block text-gray-300 text-sm font-bold mb-2">Sugerencia</label>
                    <textarea name="content" rows="4" class="w-full bg-black/50 border border-gray-700 rounded-lg p-3 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none mb-6 transition-all" placeholder="Escribe aquí tu idea..." required></textarea>
                    
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 rounded-lg transition-colors uppercase tracking-widest shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                        Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>

        <script>
            function toggleModal() {
                const modal = document.getElementById('suggestionModal');
                modal.classList.toggle('hidden');
            }
            
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('suggestionModal');
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        toggleModal();
                    }
                });
                
                @if(session('success'))
                    toggleModal();
                @endif
            });
        </script>
        <!-- FIN CÓDIGO DEL USUARIO -->
    </main>

    <footer class="main-footer">
        <div class="footer-sections">
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('tierlist') }}">TIERLIST</a></li>
                    <li><a href="{{ route('meta') }}">META</a></li>
                    <li><a href="{{ route('info.news') }}">NOVEDADES</a></li>
                </ul>
            </div>
            <div>
                <h3>Soporte</h3>
                <ul>
                    <li><a href="mailto:soporte@megabonkguide.com">Contáctanos</a></li>
                    <li><a href="#" onclick="event.preventDefault(); toggleModal();">Sugerencias</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div>
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-copy">
            &copy; 2025 MEGABONK GUIDE. Todos los derechos reservados.
        </div>
    </footer>

</body>
</html>

