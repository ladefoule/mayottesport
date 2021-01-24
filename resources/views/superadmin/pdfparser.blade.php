@extends('layouts.site')

@section('title', 'Pdf Parser')

@section('content')
    <div class="row mx-0 mt-4 center-children">
        <div class="col-lg-12 d-flex center-children">
            <h1 class="h4">PDF Parser</h1>
        </div>
        <div class="col-md-8 col-lg-6 d-flex center-children">
            <form action="" method="POST" class="needs-validation" enctype="multipart/form-data">
                @csrf
                <div class="form-row justify-content-center">
                    <div class="col-12 mb-3">
                        <label for="pdf">Fichier PDF</label>
                        <input type="file" name="pdf" class="form-control @error('pdf') is-invalid @enderror">
                        @error('pdf')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000">
                    </div>
                </div>
                <div class="centre mt-2"><button class="btn btn-primary px-5" type="submit">Valider</button></div>
            </form>
        </div>
        <div class="col-12 text-left">
            <?php
                if(isset($file))
                {
                    // Parse pdf file and build necessary objects.
                    $parser = new \Smalot\PdfParser\Parser();
                    // var_dump(get_class_methods('Illuminate\Http\UploadedFile'));
                    // var_dump($file->getType());
                    // var_dump($file->getMimeType());

                    $pdf = $parser->parseFile($file->getPathname());

                    $details  = $pdf->getText();

                    echo '<pre>' . $details . '</pre>';
                }
            ?>
        </div>
    </div>
@endsection
