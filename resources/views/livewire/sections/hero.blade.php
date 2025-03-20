<div class="relative flex min-h-screen w-full items-center justify-between gap-16">

    <div class="flex max-w-[50%] flex-col gap-8">
        <span class="badge badge-md">All-in-one tool</span>

        <h1 class="text-start text-5xl">
            <span class="section -ms-[67px] border-s-4 ps-16 font-mono font-bold">
                Create and automate
            </span>
            <br>
            <span class="font-light">projects in minutes</span>
        </h1>

        <p class="text-start text-base">
            <span class="font-mono font-light">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repudiandae, qui dignissimos. Quis a nostrum,
                veritatis autem consequuntur dolorem atque minus ea voluptatem modi eos debitis, in hic doloremque
                officiis
                minima?
            </span>
        </p>

        <div class="flex items-center justify-between gap-6">
            <button class="btn w-1/3">Default</button>
            <button class="btn bg-accent text-accent-content w-2/3">Default</button>
        </div>
    </div>


    <div class="mockup-browser border-base-300 relative z-0 w-[50%] border">
        <div class="mockup-browser-toolbar">
            <div class="input">https://stacker.com</div>
        </div>
        <div class="border-base-300 grid h-80 grid-cols-4 place-content-center gap-6 border-t p-6">
            <div class="col-span-1">
                <div class="skeleton h-32"></div>
            </div>
            <div class="col-span-1">
                <div class="skeleton h-32"></div>
            </div>
            <div class="col-span-1">
                <div class="skeleton h-32"></div>
            </div>
            <div class="col-span-1">
                <div class="skeleton h-32"></div>
            </div>
        </div>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="float absolute bottom-20 left-1/2 size-8 -translate-x-1/2 cursor-pointer">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5" />
    </svg>

    <style>
        .float {
            animation: float 1s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-.3rem);
            }

            100% {
                transform: translateY(0);
            }
        }
    </style>

</div>
