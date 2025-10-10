@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gestion des Utilisateurs</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel Utilisateur
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date de création</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->nom }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrateur</span>
                                @else
                                    <span class="badge bg-primary">Gestionnaire</span>
                                @endif
                            </td>
                            <td>
                                @if($user->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" 
                                          action="{{ route('users.destroy', $user) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Statistiques</h6>
                            <p class="mb-1"><strong>Total utilisateurs:</strong> {{ $users->count() }}</p>
                            <p class="mb-1"><strong>Administrateurs:</strong> {{ $users->where('role', 'admin')->count() }}</p>
                            <p class="mb-1"><strong>Gestionnaires:</strong> {{ $users->where('role', 'gestionnaire')->count() }}</p>
                            <p class="mb-0"><strong>Utilisateurs actifs:</strong> {{ $users->where('actif', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun utilisateur</h4>
                <p class="text-muted">Commencez par créer votre premier utilisateur.</p>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un utilisateur
                </a>
            </div>
        @endif
    </div>
</div>
@endsection