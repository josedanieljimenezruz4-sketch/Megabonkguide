<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Master Tier List | Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/tierlist.css') }}?v={{ time() }}">
    <link rel="icon" href="/iconotlabaho.webp" type="image/x-icon">
</head>
<body>
    @include('partials.header')

    <main class="main-content-tierlist">
        <h1 class="page-title" style="color: #ff4757;">Admin Tier List Manager</h1>
        <p class="intro-text-tierlist" style="margin-bottom: 20px;">
            En esta vista puedes gestionar masivamente TODOS los ítems de la Tier List.
            Selecciona ítems, elige un rango de destino y aplícalo (incluso puedes mandar unidades de vuelta al Laboratorio al usar 'PENDIENTES').
        </p>

        <!-- Barra de Acciones Masivas FIJA (Sticky) para tenerla siempre accesible si hay selección -->
        <div id="bulk-action-bar" style="display: none; position: sticky; top: 100px; z-index: 100; background: rgba(255, 71, 87, 0.95); backdrop-filter: blur(5px); border: 1px dashed #fff; padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <span style="font-weight: bold; color: #fff; margin-right: 15px; text-transform: uppercase;">⚡ Acciones Masivas:</span>
            <select id="bulk-rank-select" style="padding: 10px; border-radius: 6px; background: #222; color: white; border: 2px solid transparent; outline: none; cursor: pointer; font-weight: bold;">
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
            <button id="submit-bulk-btn" class="btn" style="padding: 10px 25px; font-size: 0.95em; margin-left: 10px; cursor: pointer; background-color: #ff4757; color: white; font-weight: bold; border: 2px solid #fff; border-radius: 6px; transition: 0.3s;" onclick="submitBulkApprove()">
                MOVER SELECCIONADOS
            </button>
        </div>

        <div style="text-align: right; margin-bottom: 20px; width: 100%;">
            <label style="cursor: pointer; color: #ff4757; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; background: rgba(255,71,87,0.1); padding: 8px 15px; border-radius: 6px; border: 1px solid #ff4757;">
                <input type="checkbox" id="select-all-master" onchange="toggleAllMaster()" style="transform: scale(1.3); cursor: pointer;"> SELECCIONAR ABSOLUTAMENTE TODOS
            </label>
        </div>

        <div class="tierlist-container">
            <table>
                <thead>
                    <tr>
                        <th class="tier-rank">RANGO</th>
                        <th>ÍTEMS CONSOLIDADOS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ranksOrder = ['S', 'A', 'B', 'C', 'D', 'E', 'F'];
                    @endphp

                    @foreach($ranksOrder as $rank)
                        @if(isset($itemsByRank[$rank]) && $itemsByRank[$rank]->count() > 0)
                            <tr class="tier-{{ strtolower($rank) }}">
                                <td class="tier-rank">{{ $rank }}</td>
                                <td>
                                    <div class="tier-items-list" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                                        @foreach($itemsByRank[$rank] as $item)
                                            <div class="tier-item admin-select-item" id="admin-item-{{ $item->id }}" data-tippy-content="{{ $item->name }}"
                                                style="position: relative; display: flex; flex-direction: column; align-items: center; width: 70px; text-align: center; background: rgba(0,0,0,0.3); padding: 5px; border-radius: 8px; transition: all 0.3s ease; box-shadow: 0 0 0 2px transparent;">
                                                
                                                <input type="checkbox" class="admin-item-checkbox" value="{{ $item->id }}" onchange="toggleItemSelection('{{ $item->id }}')" style="position: absolute; top: 4px; right: 4px; cursor: pointer; transform: scale(1.2); z-index: 10;">
                                                
                                                @php
                                                    $imgSrc = asset('images/' . $item->image_path);
                                                    if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) $imgSrc = asset('storage/' . $item->image_path);
                                                @endphp
                                                @if($item->image_path)
                                                    <img src="{{ $imgSrc }}" alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                                        style="width: 40px; height: 40px; object-fit: contain; border-radius: 5px; background: #222; margin-top: 5px;">
                                                @else
                                                    <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item->name }}"
                                                        style="width: 40px; height: 40px; object-fit: contain; border-radius: 5px; background: #222; margin-top: 5px;">
                                                @endif
                                                <span style="font-size: 0.65em; margin-top: 5px; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%;">{{ $item->name }}</span>
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

        <section class="meta-links" style="margin-top: 30px;">
            <h2 style="color: #ff4757;">🧪 Laboratorio de la Comunidad (Pendientes)</h2>
            <div class="tier-items-list" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 20px;">
                @if(isset($pendingItems) && $pendingItems->count() > 0)
                    @foreach($pendingItems as $item)
                        <div class="tier-item admin-select-item pending-item" id="admin-item-{{ $item->id }}" data-tippy-content="{{ $item->description ?? 'Sin descripción disponible.' }}"
                            style="position: relative; display: flex; flex-direction: column; align-items: center; width: 100px; text-align: center; background: #2c2f33; padding: 10px; border-radius: 8px; transition: all 0.3s ease; box-shadow: 0 0 0 2px transparent;">
                            
                            <input type="checkbox" class="admin-item-checkbox" value="{{ $item->id }}" onchange="toggleItemSelection('{{ $item->id }}')" style="position: absolute; top: 8px; right: 8px; cursor: pointer; transform: scale(1.3); z-index: 10;">
                            
                            @php
                                $imgSrcP = asset('images/' . $item->image_path);
                                if (\Illuminate\Support\Str::startsWith($item->image_path, 'items/')) $imgSrcP = asset('storage/' . $item->image_path);
                            @endphp
                            @if($item->image_path)
                                <img src="{{ $imgSrcP }}" alt="{{ $item->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';"
                                    style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}"
                                    style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #222;">
                            @endif
                            <span style="font-size: 0.8em; margin: 8px 0; line-height: 1.1;">{{ $item->name }}</span>
                        </div>
                    @endforeach
                @else
                    <p style="color: #aaa; width: 100%; text-align: center;">No hay ítems pendientes en el Laboratorio.</p>
                @endif
            </div>
        </section>
    </main>

    <!-- Scripts -->
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
                body: JSON.stringify({ ids: ids, rank: rank }) /* PENDING is automatically correctly handled by controller */
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
</body>
</html>
