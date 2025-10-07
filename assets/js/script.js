// assets/js/script.js - Update navigation links
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Language switcher
    const langSwitches = document.querySelectorAll('.lang-switch');
    langSwitches.forEach(switchEl => {
        switchEl.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            
            // Update active state
            langSwitches.forEach(el => {
                if (el === this) {
                    el.classList.add('bg-green-600', 'text-white');
                    el.classList.remove('hover:bg-gray-800');
                } else {
                    el.classList.remove('bg-green-600', 'text-white');
                    el.classList.add('hover:bg-gray-800');
                }
            });
            
            // Switch language content
            document.querySelectorAll('[data-en]').forEach(element => {
                if (lang === 'en') {
                    element.textContent = element.getAttribute('data-en');
                } else if (lang === 'id') {
                    element.textContent = element.getAttribute('data-id');
                }
            });
        });
    });
    
    // Fade in animation
    const fadeElements = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });
    
    fadeElements.forEach(el => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});