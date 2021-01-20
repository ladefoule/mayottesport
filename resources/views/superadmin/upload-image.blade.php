@extends('layouts.site')

@section('title', 'Upload Image')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 p-3">
        <div class="card bg-light">
            <div class="card-header h4">Upload Image</div>

            <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">    
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>{{ $message }}</strong>
                    </div>
                    <div class="col-12">
                        <img class="p-3 image-fluid w-100" src="{{ asset('storage/upload/img/' . Session::get('image')) }}">
                    </div>
                @endif
        
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Erreur !</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="" method="POST" enctype="multipart/form-data" id="formulaire">
                    @csrf

                    <div class="form-group row pb-2">
                        <label for="nom" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Nom</label>

                        <div class="col-md-6">
                            <input id="nom" type="text" pattern=".{5,50}" class="form-control @error('nom') is-invalid @enderror" name="nom"
                                value="{{ old('nom') }}" required autocomplete="nom" autofocus
                                data-msg="Le <span class='text-danger font-italic'>Nom</span> ne peut contenir que des caractères alphanumériques ainsi que les caractères suivants : '-' et '_'">

                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <label for="nom" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Image</label>

                        <div class="col-md-6">
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row px-3">
                        <div class="offset-md-4 col-md-6 mt-3 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row pb-2 mb-0">
                        <div class="offset-md-4 col-md-6">
                            <button type="submit" class="btn btn-success">Upload</button>
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
