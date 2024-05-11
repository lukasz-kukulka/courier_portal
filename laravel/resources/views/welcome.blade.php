@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Otwórz okno modalne
          </button>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Okno modalne</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Jesteś niezalogowany. Wybierz jedną z opcji:
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                  <a href="/zaloguj" class="btn btn-primary">Zaloguj</a>
                  <a href="/zarejestruj" class="btn btn-primary">Zarejestruj</a>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>
@endsection
