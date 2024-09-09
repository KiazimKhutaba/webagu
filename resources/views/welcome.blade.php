<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>WebAGU</title>

         @vite(['resources/css/bootstrap.min.css', 'resources/css/bootstrap-icons.css'])
    </head>
    <body style="overflow: auto !important;" id="mainBody">
        <div id="app"></div>

        {{-- @vite('resources/js/assets/bootstrap.min.js') --}}

        <script>
            /**
             * this hack disables overflow-y-hidden bug
             *
             * @type {HTMLElement}
             */
            const body = document.getElementById('mainBody');

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function() {
                    //console.log(body.style);
                    if(body.style.cssText.length !== 0)
                        body.style.cssText = '';
                });
            });

            observer.observe(body, { attributes : true, attributeFilter : ['style'] });
        </script>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    @vite('resources/js/app.js')
</html>
