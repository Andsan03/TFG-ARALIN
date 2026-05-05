@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-users text-primary me-2"></i>
                Gestión de Usuarios
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'primary' : 'success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->is_blocked)
                                                    <span class="badge bg-danger">Bloqueado</span>
                                                @else
                                                    <span class="badge bg-success">Activo</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($user->role !== 'admin')
                                                    @if($user->is_blocked)
                                                        <form action="{{ route('admin.users.unblock', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Estás seguro de desbloquear a este usuario?')">
                                                                <i class="fas fa-unlock"></i> Desbloquear
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Estás seguro de bloquear a este usuario?')">
                                                                <i class="fas fa-lock"></i> Bloquear
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Admin</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay usuarios registrados en el sistema.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
