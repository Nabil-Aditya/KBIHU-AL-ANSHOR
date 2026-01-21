<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/ecozyne.png') }}" />
    <link href="{{ asset('assets/css/styles-loader.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Loader -->
    <div id="loader">
        <div class="loader"></div>
    </div>
</body>

   <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.addEventListener("load", function () {
                const loader = document.getElementById("loader");
                const content = document.querySelector(".content");

                setTimeout(function () {
                    loader.style.opacity = "0";
                    loader.style.transition = "opacity 0.5s ease";

                    setTimeout(function () {
                        loader.style.display = "none";
                        content.style.display = "block";
                    }, 500);
                }, 500);
            });
        });
    </script>

</html>