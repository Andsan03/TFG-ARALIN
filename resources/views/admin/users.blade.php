@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de usuarios</h3>
            <p class="text-muted mb-0">Bloquea o desbloquea cuentas de la plataforma</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
            <i class="fas fa-arrow-left me-1"></i>Dashboard
        </a>
    </div>

    {{-- FILTROS --}}
    <div class="search-filters bg-white border rounded-3 p-4 mb-4">
        <form method="GET" action="{{ route('admin.users') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               value="{{ request('search') }}" placeholder="Buscar por nombre o email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="role">
                        <option value="">Todos los roles</option>
                        <option value="student"  {{ request('role') == 'student'  ? 'selected' : '' }}>Alumnos</option>
                        <option value="teacher"  {{ request('role') == 'teacher'  ? 'selected' : '' }}>Profesores</option>
                        <option value="admin"    {{ request('role') == 'admin'    ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Todos</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Activos</option>
                        <option value="blocked"  {{ request('status') == 'blocked'  ? 'selected' : '' }}>Bloqueados</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLA --}}
    @if($users->count() > 0)

        <p class="text-muted small mb-3">{{ $users->total() }} usuarios encontrados</p>

        <div class="bg-white border rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold px-4">Usuario</th>
                            <th class="small fw-semibold">Rol</th>
                            <th class="small fw-semibold">Estado</th>
                            <th class="small fw-semibold">Registro</th>
                            <th class="small fw-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                {{-- Usuario --}}
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                 class="rounded-circle" width="36" height="36" alt="">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                                 style="width:36px;height:36px;font-size:.85rem">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Rol --}}
                                <td>
                                    <span class="badge
                                        @switch($user->role)
                                            @case('admin')   bg-danger  @break
                                            @case('teacher') bg-primary @break
                                            @default         bg-success @break
                                        @endswitch">
                                        @switch($user->role)
                                            @case('admin')   Admin    @break
                                            @case('teacher') Profesor @break
                                            @default         Alumno   @break
                                        @endswitch
                                    </span>
                                </td>

                                {{-- Estado --}}
                                <td>
                                    <span class="badge {{ $user->is_blocked ? 'bg-danger' : 'bg-success' }}">
                                        {{ $user->is_blocked ? 'Bloqueado' : 'Activo' }}
                                    </span>
                                </td>

                                {{-- Fecha --}}
                                <td class="small text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Acciones --}}
                                <td>
                                    @if($user->role !== 'admin')
                                        @if($user->is_blocked)
                                            <form action="{{ route('admin.users.unblock', $user->id) }}" method="POST"
                                                  onsubmit="return confirm('¿Desbloquear a {{ $user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-unlock me-1"></i>Desbloquear
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.block', $user->id) }}" method="POST"
                                                  onsubmit="return confirm('¿Bloquear a {{ $user->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-lock me-1"></i>Bloquear
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No se encontraron usuarios</h5>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary mt-3">
                <i class="fas fa-times me-1"></i>Limpiar filtros
            </a>
        </div>
    @endif

</div>

@endsection