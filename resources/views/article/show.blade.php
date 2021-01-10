@extends($article->sport_id ? 'layouts.sport' : 'layouts.site')

@section('title', $article->titre)

@section('content')
<div class="row bg-white">
    <div class="col-lg-8 p-0 d-flex flex-wrap justify-content-center">
        <h1 class="col-12 titre-article py-3">{{ $article->titre }}</h1>
        <div class="col-10 d-flex justify-content-center">
            <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
        </div>

        <!-- Create the editor container -->
        <div class="col-12 pt-3">
           <span class="font-weight-bold">
              {!! $article->preambule !!}
           </span>
            {!! $article->article !!}
        </div>

        <div class="col-12 bg-white pt-2">
            <div id="disqus_thread"></div>
            <script>
                /**
                *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
                /*
                var disqus_config = function () {
                this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                };
                */
                (function() { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://mayottesport-v2.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Veuillez activer JavaScript pour voir les commentaires alimentés par Disqus.</noscript>
        </div>
    </div>
    <div class="col-4 d-none d-lg-block p-2 bg-resultats">
      <h2 class="alert alert-danger h2 py-4 text-center">Les résultats</h2>
      {!! $journees !!}
   </div>
</div>
@endsection
