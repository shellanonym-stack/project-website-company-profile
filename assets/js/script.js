// Enhanced language switching functionality
function switchLanguage(lang) {
    // Update active button style
    document.querySelectorAll('.lang-switch').forEach(b => {
        b.classList.remove('bg-green-600', 'text-white');
        b.classList.add('hover:bg-gray-800', 'text-white');
    });
    document.querySelector(`.lang-switch[data-lang="${lang}"]`).classList.add('bg-green-600', 'text-white');
    document.querySelector(`.lang-switch[data-lang="${lang}"]`).classList.remove('hover:bg-gray-800');
    
    // Update all elements with data attributes
    document.querySelectorAll('[data-en], [data-id]').forEach(el => {
        if (el.hasAttribute(`data-${lang}`)) {
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT') {
                el.placeholder = el.getAttribute(`data-${lang}`);
            } else if (el.tagName === 'OPTION') {
                return;
            } else {
                el.textContent = el.getAttribute(`data-${lang}`);
            }
        }
    });
    
    document.querySelectorAll('select').forEach(select => {
        Array.from(select.options).forEach(option => {
            if (option.hasAttribute(`data-${lang}`) && option.value !== '') {
                option.textContent = option.getAttribute(`data-${lang}`);
            }
        });
    });
    
    localStorage.setItem('preferredLang', lang);
    document.cookie = `preferredLang=${lang};path=/;max-age=31536000`;
}

// Language switch event listeners
document.querySelectorAll('.lang-switch').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const lang = this.getAttribute('data-lang');
        switchLanguage(lang);
    });
});

// Get preferred language
function getPreferredLanguage() {
    const cookieLang = document.cookie
        .split('; ')
        .find(row => row.startsWith('preferredLang='))
        ?.split('=')[1];
    
    return cookieLang || localStorage.getItem('preferredLang') || 'en';
}

// Set initial language on page load
document.addEventListener('DOMContentLoaded', function() {
    const preferredLang = getPreferredLanguage();
    switchLanguage(preferredLang);
    
    initMobileMenu();
    initScrollAnimations();
    setActiveNav();
});

// Set active navigation based on current page
function setActiveNav() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.menu-item');
    
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPage) {
            link.classList.add('text-green-500', 'font-bold');
            link.classList.remove('text-white');
        }
    });
}

// Mobile menu functionality
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    if (mobileMenuButton) {
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
        });
        
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
            });
        });
    }
}

// Scroll animations
function initScrollAnimations() {
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    fadeElements.forEach(element => {
        observer.observe(element);
    });
}

// Smooth scrolling for anchor links (untuk internal links dalam satu halaman)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
            
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
            }
        }
    });
});

// Navbar shadow on scroll
window.addEventListener('scroll', function() {
    const scrollPosition = window.scrollY;
    const nav = document.querySelector('nav');
    
    if (scrollPosition > 100) {
        nav.classList.add('shadow-lg');
    } else {
        nav.classList.remove('shadow-lg');
    }
});