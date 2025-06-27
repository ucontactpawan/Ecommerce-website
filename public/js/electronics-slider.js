document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.electronics-track');
    const prevButton = document.querySelector('.slider-nav.prev');
    const nextButton = document.querySelector('.slider-nav.next');
    
    if (!track || !prevButton || !nextButton) return;

    // Calculate the scroll amount (one item width + gap)
    const scrollAmount = 220 + 24; // item width + gap

    function updateNavButtons() {
        // Show/hide prev button based on scroll position
        prevButton.style.display = track.scrollLeft <= 0 ? 'none' : 'flex';
        
        // Show/hide next button based on scroll position
        const maxScroll = track.scrollWidth - track.clientWidth;
        nextButton.style.display = track.scrollLeft >= maxScroll ? 'none' : 'flex';
    }

    // Initially hide prev button if at start
    prevButton.style.display = 'none';

    prevButton.addEventListener('click', () => {
        track.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    });

    nextButton.addEventListener('click', () => {
        track.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    });

    // Update buttons visibility on scroll
    track.addEventListener('scroll', updateNavButtons);
    
    // Update on resize
    window.addEventListener('resize', updateNavButtons);
    
    // Initial update
    updateNavButtons();
});
