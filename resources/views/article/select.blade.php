{{-- @extends('layouts.app') --}}

@extends('layouts.site')

@section('title', "Choix de l'article")

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 p-3">
        <div class="card">
            <div class="card-header">Choix de l'article</div>

            <div class="card-body">
                <form method="POST" action="" id="formulaire">
                    @csrf

                    <div class="form-group row pb-2">
                        <label for="uniqid" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Article</label>

                        <div class="col-md-6">
                            <select class="form-control @error('uniqid') is-invalid @enderror" name="uniqid" data-msg="Veuillez choisir un article.">
                              <option value="">Choisir un article</option>
                              @foreach ($articles as $article)
                                  <option value="{{ $article->uniqid }}">{{ $article->titre }}</option>
                              @endforeach
                            </select>

                            @error('article')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row px-3">
                        <div class="offset-md-4 col-md-6 mt-3 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row pb-2 mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary">Valider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
