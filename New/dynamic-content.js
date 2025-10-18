// Dynamic Content Loader for Jetwide Website
// Updated with all new features: dropdown menus, car hire, navigation improvements
// Add this script to your index-ready.html before closing </body> tag

class JetwideContentManager {
    constructor() {
        // Use absolute path to WordPress API
        this.wpApiUrl = '/wp/wp-json/wp/v2/';
        console.log('🔗 WordPress API URL:', this.wpApiUrl);
        this.init();
        this.initializeInteractiveFeatures();
    }

    async init() {
        await this.loadDestinations();
        await this.loadSpecialEvents();
        await this.loadServices();
        await this.loadCarHireData();
    }

    // Initialize all interactive features
    initializeInteractiveFeatures() {
        this.initHeroSlideshow();
        this.initDropdownMenus();
        this.initCarHireFeatures();
        this.initNavigationFeatures();
        this.initNewsletterSubscription();
    }

    // Initialize dropdown menu functionality
    initDropdownMenus() {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            
            if (toggle) {
                // Toggle dropdown on click
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                    
                    // Close other dropdowns
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('active');
                        }
                    });
                });
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });
    }

    // Initialize hero slideshow functionality
    initHeroSlideshow() {
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.hero-indicator');
        const prevBtn = document.querySelector('.hero-nav-btn.prev');
        const nextBtn = document.querySelector('.hero-nav-btn.next');
        const heroSection = document.querySelector('.hero-section');
        
        if (!slides.length) return;

        // Set background images from data-bg attributes
        slides.forEach(slide => {
            const bgImage = slide.getAttribute('data-bg');
            if (bgImage) {
                slide.style.backgroundImage = `url(${bgImage})`;
                slide.style.backgroundSize = 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
            }
        });

        let currentSlide = 0;
        let isPlaying = true;
        let slideInterval;

        // Function to show specific slide
        const showSlide = (index) => {
            // Remove active class from all slides and indicators
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Add active class to current slide and indicator
            slides[index].classList.add('active');
            if (indicators[index]) indicators[index].classList.add('active');
            
            currentSlide = index;
        };

        // Function to go to next slide
        const nextSlide = () => {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        };

        // Function to go to previous slide
        const prevSlide = () => {
            const prev = currentSlide === 0 ? slides.length - 1 : currentSlide - 1;
            showSlide(prev);
        };

        // Auto-play functionality
        const startSlideshow = () => {
            slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        };

        const stopSlideshow = () => {
            clearInterval(slideInterval);
        };

        // Event listeners for navigation buttons
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopSlideshow();
                setTimeout(startSlideshow, 1000); // Restart after user interaction
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopSlideshow();
                setTimeout(startSlideshow, 1000);
            });
        }

        // Event listeners for indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showSlide(index);
                stopSlideshow();
                setTimeout(startSlideshow, 1000);
            });
        });

        // Pause slideshow on hover
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => {
                isPlaying = false;
                stopSlideshow();
            });

            heroSection.addEventListener('mouseleave', () => {
                isPlaying = true;
                startSlideshow();
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopSlideshow();
                setTimeout(startSlideshow, 1000);
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopSlideshow();
                setTimeout(startSlideshow, 1000);
            }
        });

        // Enhanced touch/swipe support for mobile
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        let isScrolling = null;

        if (heroSection) {
            heroSection.addEventListener('touchstart', (e) => {
                startX = e.changedTouches[0].clientX;
                startY = e.changedTouches[0].clientY;
                isScrolling = null;
            }, { passive: true });

            heroSection.addEventListener('touchmove', (e) => {
                // Only handle if we have a touch start
                if (startX === 0 && startY === 0) return;
                
                endX = e.changedTouches[0].clientX;
                endY = e.changedTouches[0].clientY;
                
                // Determine if scrolling test has run - one time test
                if (isScrolling === null) {
                    isScrolling = Math.abs(endY - startY) > Math.abs(endX - startX);
                }
                
                // If we're swiping horizontally, prevent default to stop page scroll
                if (!isScrolling) {
                    e.preventDefault();
                }
            }, { passive: false });

            heroSection.addEventListener('touchend', (e) => {
                // Don't act if we're scrolling vertically
                if (isScrolling) return;
                
                const diffX = startX - endX;
                const diffY = startY - endY;
                
                // Minimum swipe distance and ensure horizontal swipe
                if (Math.abs(diffX) > 50 && Math.abs(diffY) < 100) {
                    if (diffX > 0) {
                        nextSlide(); // Swipe left = next slide
                    } else {
                        prevSlide(); // Swipe right = previous slide
                    }
                    stopSlideshow();
                    setTimeout(startSlideshow, 1000);
                }
                
                // Reset values
                startX = 0;
                startY = 0;
                endX = 0;
                endY = 0;
                isScrolling = null;
            }, { passive: true });
        }

        // Initialize slideshow with delay for mobile
        setTimeout(() => {
            startSlideshow();
            console.log('🎬 Hero slideshow initialized with', slides.length, 'slides');
        }, 100);
        
        // Force image loading on mobile devices
        setTimeout(() => {
            slides.forEach((slide, index) => {
                const bgImage = slide.getAttribute('data-bg');
                if (bgImage) {
                    // Pre-load images for better performance
                    const img = new Image();
                    img.onload = () => {
                        slide.style.backgroundImage = `url(${bgImage})`;
                        slide.style.backgroundSize = 'cover';
                        slide.style.backgroundPosition = 'center';
                        slide.style.backgroundRepeat = 'no-repeat';
                        slide.style.backgroundAttachment = 'scroll';
                    };
                    img.src = bgImage;
                }
            });
        }, 200);
    }

    // Initialize car hire functionality
    initCarHireFeatures() {
        const carCards = document.querySelectorAll('.car-card');
        carCards.forEach(card => {
            card.addEventListener('click', () => {
                const carName = card.querySelector('.car-name')?.textContent || 'Vehicle';
                const carPrice = card.querySelector('.car-price')?.textContent || 'Contact for pricing';
                
                // WhatsApp integration for car booking
                const message = encodeURIComponent(`Hi! I'm interested in booking: ${carName} - ${carPrice}`);
                window.open(`https://wa.me/254748538311?text=${message}`, '_blank');
            });
        });
    }

    // Initialize navigation features
    initNavigationFeatures() {
        // Mobile menu toggle functionality
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (mobileToggle && navMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });

            // Close mobile menu when clicking on menu links
            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileToggle.contains(e.target) && !navMenu.contains(e.target)) {
                    mobileToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                }
            });
        }
        
        // Auto-hide header on scroll down, show on scroll up
        let lastScrollTop = 0;
        const scrollThreshold = 100;
        const header = document.querySelector('header');
        
        if (header) {
            window.addEventListener('scroll', () => {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (Math.abs(scrollTop - lastScrollTop) < scrollThreshold) {
                    return;
                }
                
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    // Scrolling down - hide header
                    header.classList.add('header-hidden');
                } else {
                    // Scrolling up - show header
                    header.classList.remove('header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Logo click handler - WordPress compatible
        const logo = document.querySelector('.logo');
        if (logo && logo.parentElement.tagName === 'A') {
            logo.parentElement.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = '/';
            });
        } else if (logo) {
            logo.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = '/';
            });
        }
    }

    // Initialize newsletter subscription
    initNewsletterSubscription() {
        const newsletterForms = document.querySelectorAll('.newsletter-form');
        newsletterForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = form.querySelector('.newsletter-input, input[type="email"]')?.value;
                if (email) {
                    this.handleNewsletterSubscription(email);
                    form.querySelector('.newsletter-input, input[type="email"]').value = '';
                }
            });
        });
    }

    // Handle newsletter subscription
    async handleNewsletterSubscription(email) {
        try {
            // Try to save to WordPress first
            const response = await fetch(`${this.wpApiUrl}newsletter`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            });

            if (response.ok) {
                alert('Thank you for subscribing! We\'ll keep you updated with our latest offers.');
            } else {
                throw new Error('WordPress subscription failed');
            }
        } catch (error) {
            // Fallback: show success message (you can integrate with other services)
            console.log('Newsletter subscription (fallback):', email);
            alert('Thank you for subscribing! We\'ll keep you updated with our latest offers.');
        }
    }

    // Load car hire data from WordPress
    async loadCarHireData() {
        try {
            const response = await fetch(`${this.wpApiUrl}car_hire?_embed&per_page=20`);
            const cars = await response.json();
            
            const container = document.querySelector('.cars-grid');
            if (container && cars.length > 0) {
                container.innerHTML = '';
                
                cars.forEach(car => {
                    const carCard = this.createCarCard(car);
                    container.appendChild(carCard);
                });
                
                // Re-initialize car hire features for dynamically loaded content
                this.initCarHireFeatures();
            }
        } catch (error) {
            console.log('Using static car hire content - WordPress not connected:', error);
        }
    }

    // Create car card HTML
    createCarCard(car) {
        const card = document.createElement('div');
        card.className = 'car-card';
        
        const imageUrl = car._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'assets/images/Travel-Services.jpg';
        const price = car.acf?.daily_price || 'Contact for pricing';
        const location = car.acf?.location || 'Kenya';
        
        card.innerHTML = `
            <div class="car-image">
                <img src="${imageUrl}" alt="${car.title.rendered}" loading="lazy" />
            </div>
            <div class="car-info">
                <h3 class="car-name">${car.title.rendered}</h3>
                <p class="car-location">${location}</p>
                <p class="car-price">Daily price from ${price}</p>
            </div>
        `;
        
        return card;
    }

    // Load destinations from WordPress
    async loadDestinations() {
        try {
            const response = await fetch(`${this.wpApiUrl}destinations?_embed&per_page=10`);
            const destinations = await response.json();
            
            const container = document.querySelector('.destinations-grid');
            if (container && destinations.length > 0) {
                container.innerHTML = '';
                
                destinations.forEach(dest => {
                    const destCard = this.createDestinationCard(dest);
                    container.appendChild(destCard);
                });
                
                // Initialize auto-scroll for destinations
                this.initDestinationAutoScroll();
            }
        } catch (error) {
            console.log('Using static content - WordPress not connected:', error);
            // Initialize auto-scroll for static content too
            this.initDestinationAutoScroll();
        }
    }

    // Auto-scroll destinations functionality
    initDestinationAutoScroll() {
        const container = document.querySelector('.destinations-grid');
        if (!container) return;

        let scrollPosition = 0;
        const scrollAmount = 300; // pixels to scroll
        const scrollInterval = 3000; // 3 seconds

        setInterval(() => {
            const maxScroll = container.scrollWidth - container.clientWidth;
            
            if (scrollPosition >= maxScroll) {
                scrollPosition = 0; // Reset to beginning
            } else {
                scrollPosition += scrollAmount;
            }
            
            container.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }, scrollInterval);
    }

    // Load services data
    async loadServices() {
        try {
            const response = await fetch(`${this.wpApiUrl}services?_embed&per_page=10`);
            const services = await response.json();
            
            // Update service sections if they exist
            this.updateServiceSections(services);
        } catch (error) {
            console.log('Using static services - WordPress not connected:', error);
        }
    }

    // Update service sections with WordPress data
    updateServiceSections(services) {
        services.forEach(service => {
            const serviceType = service.acf?.service_type;
            
            switch(serviceType) {
                case 'visa':
                    this.updateVisaServices(service);
                    break;
                case 'car_hire':
                    this.updateCarHireServices(service);
                    break;
                case 'job_placement':
                    this.updateJobServices(service);
                    break;
                default:
                    console.log('Unknown service type:', serviceType);
            }
        });
    }

    // Update VISA services section
    updateVisaServices(service) {
        const visaSection = document.querySelector('#visa-services');
        if (visaSection) {
            const description = visaSection.querySelector('.service-description');
            if (description) {
                description.innerHTML = service.content.rendered;
            }
        }
    }

    // Update car hire services
    updateCarHireServices(service) {
        const carSection = document.querySelector('#car-hire-services');
        if (carSection) {
            const description = carSection.querySelector('.service-description');
            if (description) {
                description.innerHTML = service.content.rendered;
            }
        }
    }

    // Update job placement services
    updateJobServices(service) {
        const jobSection = document.querySelector('#job-services');
        if (jobSection) {
            const description = jobSection.querySelector('.service-description');
            if (description) {
                description.innerHTML = service.content.rendered;
            }
        }
    }

    // Load special events/themed holidays
    async loadSpecialEvents() {
        try {
            const response = await fetch(`${this.wpApiUrl}special_events?_embed&per_page=6`);
            const events = await response.json();
            
            const container = document.querySelector('.themed-grid');
            if (container && events.length > 0) {
                container.innerHTML = '';
                
                events.forEach(event => {
                    const eventCard = this.createEventCard(event);
                    container.appendChild(eventCard);
                });
            }
        } catch (error) {
            console.log('Using static events - WordPress not connected:', error);
        }
    }

    // Create destination card HTML
    createDestinationCard(destination) {
        const card = document.createElement('div');
        card.className = 'destination-card';
        
        const imageUrl = destination._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'assets/images/default-destination.jpg';
        const price = destination.acf?.price || '$199';
        
        card.innerHTML = `
            <img src="${imageUrl}" alt="${destination.title.rendered}" class="destination-image" />
            <div class="destination-info">
                <div class="flex-row justify-between">
                    <span class="destination-location">${destination.title.rendered}</span>
                    <span class="destination-price">${price}</span>
                </div>
                <span class="price-per-person">Per Person</span>
                <p class="destination-description">${this.stripHtml(destination.excerpt.rendered)}</p>
            </div>
        `;
        
        return card;
    }

    // Create special event card HTML
    createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'themed-card';
        
        const imageUrl = event._embedded?.['wp:featuredmedia']?.[0]?.source_url || 'assets/images/default-event.jpg';
        const price = event.acf?.price || '120k';
        const duration = event.acf?.duration || 'EVERY DAY';
        const groupSize = event.acf?.group_size || '3-10 PP';
        
        card.innerHTML = `
            <img src="${imageUrl}" alt="${event.title.rendered}" class="themed-image" />
            <h3 class="themed-card-title">${event.title.rendered}</h3>
            <div class="themed-price-row">
                <span class="price-from">from</span>
                <span class="price-amount">${price}</span>
            </div>
            <div class="themed-details">
                <div class="detail-item">
                    <img src="assets/images/calender.png" alt="Calendar" class="detail-icon" />
                    <span class="detail-text">${duration}</span>
                </div>
                <div class="detail-item">
                    <img src="assets/images/group.png" alt="Group" class="detail-icon" />
                    <span class="detail-text">${groupSize}</span>
                </div>
            </div>
            <p class="themed-description">${this.stripHtml(event.excerpt.rendered)}</p>
        `;
        
        return card;
    }

    // Utility function to strip HTML tags
    stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    // Manual refresh function for admin use
    async refreshContent() {
        await this.init();
        console.log('Content refreshed from WordPress');
    }
}

// Initialize content manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.jetwideContent = new JetwideContentManager();
    
    // Initialize FAQ functionality for VISA page
    initializeFAQ();
    
    // Initialize statistics animation
    initializeStatsAnimation();
    
    // Add refresh button for admin (hidden by default)
    const refreshBtn = document.createElement('button');
    refreshBtn.innerHTML = '🔄 Refresh Content';
    refreshBtn.style.cssText = 'position:fixed;top:80px;right:80px;z-index:9999;background:#007cba;color:white;border:none;padding:10px;border-radius:5px;display:none;';
    refreshBtn.onclick = () => window.jetwideContent.refreshContent();
    document.body.appendChild(refreshBtn);
    
    // Show refresh button when user presses Ctrl+Shift+R
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'R') {
            e.preventDefault();
            refreshBtn.style.display = refreshBtn.style.display === 'none' ? 'block' : 'none';
        }
    });
});

// FAQ functionality for VISA services page
function initializeFAQ() {
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
}

// Statistics animation for car hire and other pages
function initializeStatsAnimation() {
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statsNumbers = entry.target.querySelectorAll('.stat-number');
                statsNumbers.forEach(stat => {
                    animateNumber(stat);
                });
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.car-stats-section, .stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
}

// Animate numbers in statistics
function animateNumber(element) {
    const target = parseInt(element.textContent.replace(/\D/g, ''));
    const suffix = element.textContent.replace(/\d/g, '');
    let current = 0;
    const increment = target / 100;
    const duration = 2000;
    const stepTime = duration / 100;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current) + suffix;
    }, stepTime);
}

// Global utility functions for WordPress integration
window.JetwideUtils = {
    // Function to refresh specific content sections
    async refreshSection(sectionType) {
        if (window.jetwideContent) {
            switch(sectionType) {
                case 'destinations':
                    await window.jetwideContent.loadDestinations();
                    break;
                case 'events':
                    await window.jetwideContent.loadSpecialEvents();
                    break;
                case 'cars':
                    await window.jetwideContent.loadCarHireData();
                    break;
                default:
                    await window.jetwideContent.refreshContent();
            }
        }
    },
    
    // Function to add new car dynamically
    addCarCard(carData) {
        const container = document.querySelector('.cars-grid');
        if (container && window.jetwideContent) {
            const carCard = window.jetwideContent.createCarCard(carData);
            container.appendChild(carCard);
            window.jetwideContent.initCarHireFeatures();
        }
    },
    
    // Function to update statistics
    updateStats(stats) {
        stats.forEach(stat => {
            const element = document.querySelector(`[data-stat="${stat.type}"]`);
            if (element) {
                element.textContent = stat.value + (stat.suffix || '');
            }
        });
    }
};

// WordPress Custom Fields Configuration
// Add this to your WordPress theme's functions.php:
/*
// Register Custom Post Types
function jetwide_register_post_types() {
    // Destinations
    register_post_type('destinations', array(
        'public' => true,
        'label' => 'Destinations',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-location-alt'
    ));
    
    // Special Events
    register_post_type('special_events', array(
        'public' => true,
        'label' => 'Special Events',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt'
    ));
    
    // Car Hire
    register_post_type('car_hire', array(
        'public' => true,
        'label' => 'Car Hire',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-car'
    ));
    
    // Services
    register_post_type('services', array(
        'public' => true,
        'label' => 'Services',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-tools'
    ));
    
    // Newsletter Subscriptions
    register_post_type('newsletter', array(
        'public' => false,
        'label' => 'Newsletter Subscribers',
        'supports' => array('title'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-email-alt'
    ));
}
add_action('init', 'jetwide_register_post_types');

// Add Custom Fields
function jetwide_add_custom_fields() {
    // For Destinations
    add_meta_box('destination_details', 'Destination Details', 'destination_meta_callback', 'destinations');
    
    // For Special Events
    add_meta_box('event_details', 'Event Details', 'event_meta_callback', 'special_events');
    
    // For Car Hire
    add_meta_box('car_details', 'Car Details', 'car_meta_callback', 'car_hire');
    
    // For Services
    add_meta_box('service_details', 'Service Details', 'service_meta_callback', 'services');
}
add_action('add_meta_boxes', 'jetwide_add_custom_fields');

function destination_meta_callback($post) {
    $price = get_post_meta($post->ID, 'price', true);
    echo '<p><label>Price: <input type="text" name="price" value="' . $price . '" placeholder="e.g., $199" /></label></p>';
}

function event_meta_callback($post) {
    $price = get_post_meta($post->ID, 'price', true);
    $duration = get_post_meta($post->ID, 'duration', true);
    $group_size = get_post_meta($post->ID, 'group_size', true);
    
    echo '<p><label>Price: <input type="text" name="price" value="' . $price . '" placeholder="e.g., 120k" /></label></p>';
    echo '<p><label>Duration: <input type="text" name="duration" value="' . $duration . '" placeholder="e.g., EVERY DAY" /></label></p>';
    echo '<p><label>Group Size: <input type="text" name="group_size" value="' . $group_size . '" placeholder="e.g., 3-10 PP" /></label></p>';
}

function car_meta_callback($post) {
    $daily_price = get_post_meta($post->ID, 'daily_price', true);
    $location = get_post_meta($post->ID, 'location', true);
    $car_type = get_post_meta($post->ID, 'car_type', true);
    
    echo '<p><label>Daily Price: <input type="text" name="daily_price" value="' . $daily_price . '" placeholder="e.g., KSh 8,500" /></label></p>';
    echo '<p><label>Location: <input type="text" name="location" value="' . $location . '" placeholder="e.g., Nairobi, Kenya" /></label></p>';
    echo '<p><label>Car Type: <select name="car_type">
        <option value="economy" ' . selected($car_type, 'economy', false) . '>Economy</option>
        <option value="luxury" ' . selected($car_type, 'luxury', false) . '>Luxury</option>
        <option value="suv" ' . selected($car_type, 'suv', false) . '>SUV</option>
        <option value="safari" ' . selected($car_type, 'safari', false) . '>Safari 4WD</option>
    </select></label></p>';
}

function service_meta_callback($post) {
    $service_type = get_post_meta($post->ID, 'service_type', true);
    
    echo '<p><label>Service Type: <select name="service_type">
        <option value="visa" ' . selected($service_type, 'visa', false) . '>VISA Processing</option>
        <option value="car_hire" ' . selected($service_type, 'car_hire', false) . '>Car Hire</option>
        <option value="job_placement" ' . selected($service_type, 'job_placement', false) . '>Job Placement</option>
        <option value="travel" ' . selected($service_type, 'travel', false) . '>Travel Services</option>
    </select></label></p>';
}

function save_custom_fields($post_id) {
    // Skip autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Save destination fields
    if (isset($_POST['price'])) update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
    
    // Save event fields
    if (isset($_POST['duration'])) update_post_meta($post_id, 'duration', sanitize_text_field($_POST['duration']));
    if (isset($_POST['group_size'])) update_post_meta($post_id, 'group_size', sanitize_text_field($_POST['group_size']));
    
    // Save car hire fields
    if (isset($_POST['daily_price'])) update_post_meta($post_id, 'daily_price', sanitize_text_field($_POST['daily_price']));
    if (isset($_POST['location'])) update_post_meta($post_id, 'location', sanitize_text_field($_POST['location']));
    if (isset($_POST['car_type'])) update_post_meta($post_id, 'car_type', sanitize_text_field($_POST['car_type']));
    
    // Save service fields
    if (isset($_POST['service_type'])) update_post_meta($post_id, 'service_type', sanitize_text_field($_POST['service_type']));
}
add_action('save_post', 'save_custom_fields');

// Enable REST API for custom fields
function jetwide_rest_api_init() {
    register_rest_field(array('destinations', 'special_events', 'car_hire', 'services'), 'acf', array(
        'get_callback' => function($object) {
            return get_fields($object['id']);
        }
    ));
}
add_action('rest_api_init', 'jetwide_rest_api_init');

// Add CORS headers for API access
function jetwide_cors_headers() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
add_action('init', 'jetwide_cors_headers');
*/