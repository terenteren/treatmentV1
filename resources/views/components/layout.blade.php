<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>서울대병원 - 치료모니터링</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">

    <script src="{{ asset('assets/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/therapy/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">

    <!-- Swiper 모듈 불러오기 -->
    <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}">

    <link rel="shortcut icon" href="{{ asset('img/logo/icon.png') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body>
    <span class="wrap">
        <span class="inner">

            {{ $slot }}

            <div class="cursor"></div>
            <style>
                /* CURSOR ANIMATION */
                .expend{width: 80px; height: 80px; background-color: #BCFEFF; border: none; border-radius: 50%; position: absolute; transition-duration: 200ms; transition-timing-function: ease-out; animation: expendAnim 0.5s; pointer-events: none; z-index: 999999;}
                .expend::after{content: ""; width: 80px; height: 80px; position: absolute; background-color: #40FDFF; border: none; border-radius: 50%; top: 0px; left: 0px; animation: expendAnim2 0.5s;}


                @keyframes expendAnim {
                    from{opacity: 1; border-radius: 50%;}
                    to{border-radius: 50%; opacity: 0;}
                }

                @keyframes expendAnim2 {
                    from{transform: scale(0.1);}
                    to{transform: scale(1);}
                }

            </style>
            <script>
                // Cursor
                const cursor = document.querySelector(".cursor");

                // Attaching Event listner to follow cursor
                document.addEventListener("mousemove", (e) => {
                    cursor.setAttribute("style", "top: "+(e.pageY - 40)+"px; left:"+(e.pageX - 40)+"px;");
                });


                // ON CLICK ADD/REMOVE CLASS ".expend"
                document.addEventListener("click", () => {
                    cursor.classList.add("expend");
                    setTimeout(() => {
                        cursor.classList.remove("expend");
                    }, 500);
                });
            </script>
		</span>
	</span>
</body>

<script>
    AOS.init();
</script>


</html>











