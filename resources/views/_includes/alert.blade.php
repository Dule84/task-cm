@if(Session::has('message'))
    <div class="alert alert-{{ Session::get('message-type') }} alert-dismissable mt-3">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <i class="glyphicon glyphicon-{{ Session::get('message-type') == 'success' ? 'ok' : 'remove'}}"></i> {{ Session::get('message') }}
    </div>
@endif
