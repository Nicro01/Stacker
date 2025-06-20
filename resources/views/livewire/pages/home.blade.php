<div data-theme="" class="container mx-auto w-full">
    <div class="">

        <livewire:components.menu />

        <livewire:sections.hero />

        {{-- <div class="my-32 grid min-h-[70vh] grid-cols-8">

            <div class="col-span-3 flex flex-col gap-6">
                <h2 class="section -ms-[67px] flex items-center gap-6 border-s-4 ps-16 font-mono text-xl font-semibold">

                </h2>
                <div class="flex flex-col gap-6 ps-12">
                    <span>Lorem Ipsum</span>
                    <span>Lorem Ipsum</span>
                    <span>Lorem Ipsum</span>
                    <span>Lorem Ipsum</span>
                    <span>Lorem Ipsum</span>
                    <span>Lorem Ipsum</span>
                </div>
            </div>

            <div class="col-span-5 flex flex-col gap-6">

            </div>

        </div> --}}

        <section class="grid w-full grid-cols-1 items-center gap-12 py-20 max-lg:px-6 lg:grid-cols-2">

            <div class="flex flex-col space-y-6">
                <h2 class="aldrich text-4xl font-bold">Supported Stacks</h2>
                <p class="text-lg">
                    Choose from battle-tested templates or create your own. All stacks come with optimal configuration,
                    pre-configured environments, and community-best practices baked right in.
                </p>
            </div>

            <div class="grid grid-cols-1 grid-rows-4 gap-8 sm:grid-cols-2 sm:grid-rows-2">
                <div class="card bg-base-200 shadow-base-300 shadow-lg">
                    <div class="card-body">
                        <figure class="mb-8 self-start">
                            <img src="{{ asset('images/logos/laravel.png') }}" alt="TALL Stack" draggable="false"
                                class="size-24" />
                        </figure>

                        <h3 class="mb-2 text-2xl font-semibold">Laravel</h3>
                        <p class="aldrich text-sm">DEFAULT + TALL + VILT + REACT</p>
                    </div>
                </div>


                <div class="card bg-base-200 shadow-base-300 shadow-lg">
                    <div class="card-body">
                        <figure class="mb-8 self-start">
                            <img src="{{ asset('images/logos/nodejs.png') }}" alt="TALL Stack" draggable="false"
                                class="size-24" />
                        </figure>

                        <h3 class="mb-2 text-2xl font-semibold">NodeJS</h3>
                        <p class="aldrich text-sm">VUE + REACT + NEXT + NUXT + ...</p>
                    </div>
                </div>

                <div class="col-span-1 h-full w-full">
                    <div class="skeleton h-full w-full"></div>
                </div>

                <div class="col-span-1 h-full w-full">
                    <div class="skeleton h-full w-full"></div>
                </div>
            </div>

        </section>


        <section class="max-lg:px-6 max-sm:mb-20 sm:my-20 sm:py-20">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div class="flex flex-col space-y-6">
                    <h2 class="aldrich text-4xl font-bold">Your Project, Your Rules</h2>
                    <p class="text-lg">
                        Configure once, deploy everywhere. Stacker adapts to your workflow.
                    </p>
                    <ul class="space-y-6">
                        <li class="flex items-center space-x-4">
                            <div class="bg-base-200 rounded-lg p-3">⚡</div>
                            <div>
                                <h3 class="aldrich text-lg font-semibold">Instant Setup</h3>
                                <p class="">Create projects with pre-configured stacks in seconds</p>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="bg-base-200 rounded-xl p-8">

                    <div class="mockup-code h-full w-full">
                        <pre data-prefix="$"><code># .env.example</code></pre>
                        <pre data-prefix=">" class="text-success"><code>"My Stacker Project"</code></pre>
                        <pre data-prefix=">" class="text-warning"><code>mysql</code></pre>
                        <pre data-prefix=">"><code># Auto-generated by Stacker</code></pre>
                    </div>

                </div>
            </div>
        </section>



        {{-- <livewire:sections.carousel /> --}}

        <livewire:sections.cta />

        {{-- <section class="my-20 py-20 max-lg:px-6">
            <div class="flex flex-col items-center space-y-10 text-center">
                <h2 class="aldrich text-4xl font-bold">Why Developers Choose Us</h2>
                <p class="max-w-2xl text-lg">
                    From hobbyists to enterprise teams, our developer-first approach helps launch and scale products
                    faster.
                </p>

                <div class="grid w-full max-w-5xl grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                    <div class="bg-base-200 rounded-xl p-6 shadow-md">
                        <div class="mb-4 text-4xl">🚀</div>
                        <h3 class="aldrich text-xl font-semibold">Blazing Fast Setup</h3>
                        <p>Launch full-stack projects in seconds with pre-configured environments.</p>
                    </div>

                    <div class="bg-base-200 rounded-xl p-6 shadow-md">
                        <div class="mb-4 text-4xl">🔧</div>
                        <h3 class="aldrich text-xl font-semibold">Customizable Stacks</h3>
                        <p>Start from templates or build your own with full control over the stack.</p>
                    </div>

                    <div class="bg-base-200 rounded-xl p-6 shadow-md">
                        <div class="mb-4 text-4xl">🌐</div>
                        <h3 class="aldrich text-xl font-semibold">Cross-Platform</h3>
                        <p>Deploy on Linux, Windows, or MacOS without modifying a single line of code.</p>
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- <section class="my-20 py-20 max-lg:px-6">
            <div class="flex flex-col items-center space-y-10 text-center">
                <h2 class="aldrich text-4xl font-bold">Tool Highlights</h2>
                <p class="max-w-2xl text-lg">
                    A closer look at some of the built-in tools and features that streamline your workflow and boost
                    productivity.
                </p>


            </div>
        </section> --}}


        <livewire:sections.footer />

    </div>


</div>
