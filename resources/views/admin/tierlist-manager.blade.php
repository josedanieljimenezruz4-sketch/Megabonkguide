@extends('layouts.admin')

@section('title', 'Gestión Master Tier List | Administrador')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="main-content-tierlist tierlist-manager-content">
        <h1 class="page-title tierlist-manager-title">Admin Tier List Manager</h1>
        <p class="intro-text-tierlist tierlist-manager-intro">
            En esta vista puedes gestionar masivamente TODOS los ítems de la Tier List.
            Selecciona ítems, elige un rango de destino y aplícalo (incluso puedes mandar unidades de vuelta al Laboratorio al usar 'PENDIENTES').
        </p>

        <!-- Barra de Acciones Masivas FIJA (Sticky) para tenerla siempre accesible si hay selección -->
        <div id="bulk-action-bar" class="bulk-action-bar" style="display: none;">
            <span class="bulk-action-label">⚡ Acciones Masivas:</span>
            <select id="bulk-rank-select" class="bulk-rank-select">
                <option value="">Seleccionar Acción...</option>
                <option value="S">Clase S</option>
                <option value="A">Clase A</option>
                <option value="B">Clase B</option>
                <option value="C">Clase C</option>
                <option value="D">Clase D</option>
                <option value="E">Clase E</option>
                <option value="F">Clase F</option>
                <option value="PENDING">🔄 Mover a Pendientes (Reset)</option>
            </select>
            <button id="submit-bulk-btn" class="btn btn-submit-bulk" onclick="submitBulkApprove()">
                MOVER SELECCIONADOS
            </button>
        </div>

        <div class="select-all-container">
            <label class="select-all-label">
                <input type="checkbox" id="select-all-master" onchange="toggleAllMaster()" class="select-all-checkbox"> SELECCIONAR ABSOLUTAMENTE TODOS
            </label>
        </div>

        <div class="tierlist-container tierlist-table-container">
            <table class="tierlist-table">
                <thead>
                    <tr>
                        <th class="tier-rank" style="width: 80px;">RANGO</th>
                        <th>ÍTEMS CONSOLIDADOS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
                    @endphp

                    @foreach($ranksOrder as $rank)
                        @if(isset($elementosPorRango[$rank]) && $elementosPorRango[$rank]->count() > 0)
                            <tr class="tier-{{ strtolower($rank) }}">
                                <td class="tier-rank">{{ $rank }}</td>
                                <td>
                                    <div class="tier-items-list">
                                        @foreach($elementosPorRango[$rank] as $item)
                                            <div class="tier-item admin-select-item tier-item-admin" id="admin-item-{{ $item->id }}" data-tippy-content="{{ $item->name }}">
                                                
                                                <input type="checkbox" class="admin-item-checkbox admin-item-checkbox-input" value="{{ $item->id }}" onchange="toggleItemSelection('{{ $item->id }}')">
                                                
                                                @php
                                                    $imgSrc = asset('images/' . $item->image_path);
                                                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) $imgSrc = asset('storage/' . $item->image_path);
                                                @endphp
                                                @if($item->image_path)
                                                    <img src="{{ $imgSrc }}" alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';" class="tier-item-admin-img">
                                                @else
                                                    <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}" class="tier-item-admin-img">
                                                @endif
                                                <span class="tier-item-admin-name">{{ $item->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <section class="meta-links pending-section">
            <h2 class="pending-title">🧪 Laboratorio de la Comunidad (Pendientes)</h2>
            <div class="tier-items-list pending-items-list">
                @if(isset($elementosPendientes) && $elementosPendientes->count() > 0)
                    @foreach($elementosPendientes as $item)
                        <div class="tier-item admin-select-item pending-item pending-item-admin" id="admin-item-{{ $item->id }}" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}">
                            
                            <input type="checkbox" class="admin-item-checkbox pending-item-checkbox" value="{{ $item->id }}" onchange="toggleItemSelection('{{ $item->id }}')">
                            
                            @php
                                $imgSrcP = asset('images/' . $item->image_path);
                                if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) $imgSrcP = asset('storage/' . $item->image_path);
                            @endphp
                            @if($item->image_path)
                                <img src="{{ $imgSrcP }}" alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';" class="pending-item-img">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" class="pending-item-img">
                            @endif
                            <span class="pending-item-name">{{ $item->name }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="empty-pending-msg">No hay ítems pendientes en el Laboratorio.</p>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[data-tippy-content]', { theme: 'tierlist', placement: 'top', arrow: true });

            const rankColors = {
                'S': '#ff416c',
                'A': '#ffb100',
                'B': '#00d2ff',
                'C': '#00e676',
                'PENDING': '#6D6D6D',
                '': '#ff4757'
            };

            const selectEl = document.getElementById('bulk-rank-select');
            selectEl.addEventListener('change', function() {
                const btn = document.getElementById('submit-bulk-btn');
                const color = rankColors[this.value] || '#ff4757';
                btn.style.backgroundColor = color;
                btn.style.borderColor = '#fff';
            });
        });

        function toggleItemSelection(itemId) {
            const itemDiv = document.getElementById('admin-item-' + itemId);
            const checkbox = itemDiv.querySelector('.admin-item-checkbox');
            if (checkbox.checked) {
                itemDiv.style.boxShadow = '0 0 0 3px #ff4757';
                itemDiv.style.background = 'rgba(255, 71, 87, 0.2)';
            } else {
                itemDiv.style.boxShadow = '0 0 0 2px transparent';
                // Restore original backgrounds
                if (itemDiv.classList.contains('pending-item')) {
                    itemDiv.style.background = '#2c2f33';
                } else {
                    itemDiv.style.background = 'rgba(0,0,0,0.3)';
                }
            }
            updateBulkActionBar();
        }

        function toggleAllMaster() {
            const masterCheckbox = document.getElementById('select-all-master');
            const checkboxes = document.querySelectorAll('.admin-item-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
                toggleItemSelection(cb.value);
            });
        }

        function updateBulkActionBar() {
            const checkboxes = document.querySelectorAll('.admin-item-checkbox:checked');
            const bulkActionBar = document.getElementById('bulk-action-bar');
            if (bulkActionBar) {
                bulkActionBar.style.display = checkboxes.length > 0 ? 'block' : 'none';
            }
        }

        function submitBulkApprove() {
            const checkedBoxes = document.querySelectorAll('.admin-item-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            const rank = document.getElementById('bulk-rank-select').value;
            if (!rank) {
                alert('Por favor selecciona una acción/rango de destino.');
                return;
            }

            const actionText = rank === 'PENDING' ? 'DEVOLVER A PENDIENTES (Reset)' : 'promover al Rango ' + rank;
            if (!confirm(`¿Estás seguro de ${actionText} los ${checkedBoxes.length} ítems seleccionados?`)) return;

            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            fetch(`/admin/items/bulk-approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: ids, rank: rank })
            })
            .then(res => {
                if(res.status === 401) {
                    alert('Debes iniciar sesión como administrador.');
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    alert(data.message);
                    location.reload();
                } else if (data && !data.success && data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error in bulk approve:', error));
        }
    </script>
@endpush
