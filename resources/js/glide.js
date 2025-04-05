import Glide from "@glidejs/glide";

let glide;

try {
    if (window.innerWidth < 768) {
        new Glide(".carousel-section", {
            type: "slide",
            startAt: 0,
            peek: { before: 0, after: 56 },
            autoplay: 0,
            perView: 1,
        }).mount();
    } else {
        new Glide(".carousel-section", {
            type: "slide",
            startAt: 0,
            peek: { before: 0, after: 160 },
            autoplay: 0,
            gap: 42,
            perView: 1,
        }).mount();
    }
} catch (error) {
    console.info("Elemento 'carousel-section' não foi encontrado", error);
}
