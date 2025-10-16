<div class="modal fade" id="ajaxSessionModal" tabindex="-1" aria-labelledby="ajaxSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajaxSessionModalLabel">Session expirée</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Votre session a probablement expiré. Pour continuer, veuillez vous déconnecter puis vous reconnecter.</p>
      </div>
      <div class="modal-footer">
        <form id="ajaxLogoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Se déconnecter et aller au login</button>
        </form>
        <a href="{{ route('login') }}" class="btn btn-secondary">Aller au login</a>
      </div>
    </div>
  </div>
</div>
