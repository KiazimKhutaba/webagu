<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WebAGU</title>

        @if(env('APP_ENV') === 'prod'):
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
        @else
         @vite(['resources/css/bootstrap.min.css', 'resources/css/bootstrap-icons.css'])
        @endif
    </head>
    <body style="overflow: auto !important;" id="mainBody">
        <div id="app"></div>

        @if(env('APP_ENV') === 'prod'):
        {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
        @else
            {{-- Todo: when npm run buid happens this is not working as expected --}}
            {{-- You should add this to vite.config.js --}}
            @vite('resources/js/assets/bootstrap.min.js')
        @endif

        <script>
            /**
             * this hack disables overflow-y-hidden bug
             *
             * @type {HTMLElement}
             */
            const body = document.getElementById('mainBody');

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutationRecord) {
                    //console.log(body.style);
                    if(body.style.cssText.length !== 0)
                        body.style.cssText = '';
                });
            });

            observer.observe(body, { attributes : true, attributeFilter : ['style'] });
        </script>
    </body>
    @vite('resources/js/app.js')
</html>
