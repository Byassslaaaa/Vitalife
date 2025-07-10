document.addEventListener("DOMContentLoaded", function () {
    const imageSlider = document.getElementById("imageSlider");
    const sliderImages = imageSlider.querySelectorAll(".slider-image");
    const totalImages = sliderImages.length;
    const visibleImages = 3;
    let currentIndex = 0;

    function showImages() {
        sliderImages.forEach((image, index) => {
            const adjustedIndex =
                (index - currentIndex + totalImages) % totalImages;
            if (adjustedIndex < visibleImages) {
                image.style.display = "block";
                image.style.order = adjustedIndex;
            } else {
                image.style.display = "none";
            }
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalImages;
        showImages();
    }

    function startAutoSlide() {
        return setInterval(nextSlide, 3000); // Geser setiap 3 detik
    }

    // Inisialisasi tampilan awal
    showImages();

    // Mulai pergeseran otomatis
    const intervalId = startAutoSlide();

    // Opsional: Hentikan pergeseran saat kursor di atas slider
    imageSlider.addEventListener("mouseenter", () => clearInterval(intervalId));
    imageSlider.addEventListener("mouseleave", () => startAutoSlide());
});
