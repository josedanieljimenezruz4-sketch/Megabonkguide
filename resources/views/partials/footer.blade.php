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
                <li><a href="{{ route('wiki.index') }}" class="footer-neon-link">Preguntas Frecuentes</a></li>
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

<style>
.footer-neon-link {
    transition: all 0.3s ease;
}
.footer-neon-link:hover {
    color: var(--color-primary-accent) !important;
    text-shadow: 0 0 10px rgba(65, 232, 239, 0.8) !important;
}
</style>

<!-- Modal de Sugerencias Global -->
<div id="suggestionModal" class="modal-overlay fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#121212] w-full max-w-md rounded-2xl p-8 relative border border-purple-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">
        <button onclick="toggleModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-2xl font-bold">&times;</button>
        
        <h2 class="text-2xl font-bold text-white mb-2 uppercase italic">Enviar Sugerencia</h2>
        <p class="text-gray-400 text-sm mb-6">¿Tienes alguna idea para mejorar la guía? ¡Te escuchamos!</p>
        
        @if(session('suggestion_success'))
            <div class="bg-green-500/20 border border-green-500 text-green-300 p-3 rounded mb-4 text-sm">
                {{ session('suggestion_success') }}
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
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('suggestionModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    toggleModal();
                }
            });
        }
        
        @if(session('suggestion_success'))
            toggleModal();
        @endif
    });
</script>

@if (!request()->routeIs('home'))
<script>
  tailwind = {
    corePlugins: {
      preflight: false,
    }
  }
</script>
<script src="https://cdn.tailwindcss.com"></script>
@endif
