<div data-theme="" class="relative mx-auto min-h-screen bg-center sm:flex sm:items-center sm:justify-center">
    <div class="border-primary/20 container border-s-2 ps-16">

        <livewire:components.menu />

        <livewire:sections.hero />

        <div class="my-32 grid min-h-[70vh] grid-cols-8">

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

        </div>


        <livewire:sections.footer />

    </div>

    <style>
        .section {
            border-left-color: transparent;
            transition: border-color 0.3s ease;
        }

        .section-active {
            border-left-color: #ff0000;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.section');

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    entry.target.classList.toggle('section-active', entry.isIntersecting);
                });
            }, {
                threshold: 0.5,
                rootMargin: '-100px 0px'
            });

            sections.forEach(section => observer.observe(section));
        });
    </script>
</div>
