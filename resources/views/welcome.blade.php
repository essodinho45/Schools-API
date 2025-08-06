<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Al-Adel School Web Application || ثانوية العادل الشرعية</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen bg-green-500 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="block fixed top-0 right-0 px-6 py-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-teal-300 underline">Dashboard</a>
                    {{-- <a href="{{ route('login') }}" class="text-sm text-cyan-300 underline">Log in</a> --}}

                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif --}}
                @endauth
            </div>
        @endif
        <div class="grid grid-cols-1">
            <div class="w-full flex justify-center">
                <img src="\images\iconwhite.png" style="max-height: 10vh; max-width: 10vw;" class="my-5">
            </div>
            <div class="text-white text-center text-lg font-bold" dir="rtl">
                <p>أهلاً بكم في تطبيق ثانوية العادل الشرعية</p>
                <p>يرجى متابعة الفيديو التوضيحي لطريقة تنصيب وتفعيل البرنامج على نظامَي IOS و Android</p>
                <br>
            </div>
            <div class="grid grid-cols-1 justify-items-center">
                <iframe class="aspect-video md:w-7/12 w-11/12" src="https://www.youtube.com/embed/YzkPw-_Oh1E?si=YQBDoFdYGtR4h6PL" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 md:w-7/12 w-11/12 justify-self-center">
                <div class="m-2 p-2 sm:m-3 sm:p-5">
                    <a href="https://play.google.com/store/apps/details?id=com.alkhazen.alfurqan_school" target="_blank">
                        <img src="\images\Google-Play-Logo.png">
                    </a>
                </div>
                <div class="m-2 p-2 sm:m-3 sm:p-5">
                    {{-- <a href="https://apps.apple.com/se/app/success/id1665818282?l=en" target="_blank"> --}}
                    {{-- <a>
                        <img class="max-w-full" src="\images\apple-app-store-logo.png">
                    </a> --}}
                </div>
                <div class="m-2 p-2 sm:m-3 sm:p-5">
                    <a href="\storage\app-alfurqan_school-release.apk">
                        <img class="max-w-full" src="\images\apk-logo.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
