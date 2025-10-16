@extends('layouts.app')

@section('title', 'Session expirée')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Session expirée ou jeton invalide</h4>
                </div>
                <div class="card-body">
                    <p>Votre session a probablement expiré ou votre navigateur n'a pas envoyé le jeton de sécurité (CSRF). Pour des raisons de sécurité, nous vous demandons de vous déconnecter puis de vous reconnecter.</p>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Se déconnecter et revenir au login</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary ms-2">Aller au formulaire de connexion</a>
                    </form>

                    <hr>
                    <p class="text-muted small">Si le problème persiste, videz le cache/cookies de votre navigateur ou essayez en mode navigation privée.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
