@extends('layouts.admin')

@section('title', 'Leaderboard Moderation | Admin')

@section('content')
<div class="admin-header">
    <h1 class="admin-title">🏆 Moderación de Leaderboard</h1>
    <p>Aprueba o rechaza las puntuaciones enviadas por los usuarios.</p>
</div>

@if(session('success'))
    <div style="background: rgba(0, 255, 0, 0.2); border-left: 4px solid #0f0; padding: 10px; margin-bottom: 20px; color: #fff;">{{ session('success') }}</div>
@endif

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Personaje</th>
                    <th>Dificultad</th>
                    <th>Puntuación</th>
                    <th>Tiempo</th>
                    <th>Prueba</th>
                    <th>Build</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingScores as $score)
                    <tr>
                        <td>{{ $score->user->name }}</td>
                        <td>{{ $score->character->name ?? 'N/A' }}</td>
                        <td>{{ strtoupper($score->difficulty) }}</td>
                        <td><strong style="color: #ffcf00;">{{ number_format($score->points) }}</strong></td>
                        <td>{{ $score->time }}</td>
                        <td>
                            <a href="{{ $score->proof_url }}" target="_blank" style="color: #00f0ff;">Ver Prueba ↗</a>
                        </td>
                        <td>
                            @if($score->build)
                                <a href="{{ route('builds.show', $score->build_id) }}" target="_blank" style="color: #00f0ff;">Ver Build</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <form action="{{ route('admin.leaderboard.approve', $score->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-edit" style="background: #28a745; color: white;">Aprobar</button>
                                </form>
                                <form action="{{ route('admin.leaderboard.reject', $score->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres rechazar este récord?');">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-danger">Rechazar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #888; padding: 20px;">No hay puntuaciones pendientes de moderación.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
