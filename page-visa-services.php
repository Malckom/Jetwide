<?php
/*
Template Name: Visa Services
*/
get_header(); ?>

<style>
/* WordPress-specific styles for visa page */
.visa-hero {
  position: relative;
  min-height: 70vh;
  display: flex;
  align-items: center;
  overflow: hidden;
}

.visa-hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
}

.visa-hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.visa-hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(26, 95, 122, 0.9) 0%, rgba(212, 175, 55, 0.8) 100%);
  z-index: 2;
}

.visa-hero .container {
  position: relative;
  z-index: 3;
}

.visa-hero-content {
  color: white;
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.visa-hero-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 20px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  line-height: 1.2;
}

.visa-hero-subtitle {
  font-size: 1.3rem;
  line-height: 1.6;
  margin-bottom: 40px;
  color: rgba(255, 255, 255, 0.95);
}

.visa-hero-actions {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

.visa-cta-btn {
  display: inline-block;
  padding: 15px 30px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.visa-cta-btn:not(.secondary) {
  background: #d4af37;
  color: white;
  border-color: #d4af37;
}

.visa-cta-btn.secondary {
  background: transparent;
  color: white;
  border-color: white;
}

.visa-cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.visa-main {
  background: #f8f9fa;
  padding: 80px 0;
}

.visa-section {
  margin-bottom: 80px;
}

.visa-section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 30px;
  text-align: center;
  position: relative;
}

.visa-section-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #d4af37, #b8941f);
  border-radius: 2px;
}

.visa-section-intro {
  font-size: 1.2rem;
  line-height: 1.7;
  color: #666;
  text-align: center;
  max-width: 800px;
  margin: 0 auto 50px;
}

.visa-services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  margin-bottom: 40px;
}

.visa-service-card {
  background: white;
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.visa-service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.visa-service-icon {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #d4af37, #b8941f);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0 auto 20px;
}

.visa-service-card h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
}

.visa-service-card p {
  color: #666;
  line-height: 1.6;
}

.visa-disclaimer {
  background: linear-gradient(135deg, #fff9e6, #f7f2d8);
  padding: 30px;
  border-radius: 15px;
  border-left: 5px solid #d4af37;
  text-align: center;
}

.visa-disclaimer p {
  margin: 0;
  font-size: 1.1rem;
  color: #8b7355;
}

.visa-important-info {
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.visa-info-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.visa-info-card {
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  padding: 25px;
  border-radius: 15px;
  text-align: center;
}

.visa-info-card h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #d4af37;
  margin-bottom: 15px;
}

.visa-info-card p {
  color: #666;
  line-height: 1.6;
  margin: 0;
}

.visa-countries-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 30px;
}

.visa-country-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.visa-country-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.visa-country-header {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.visa-country-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.visa-country-card:hover .visa-country-image {
  transform: scale(1.05);
}

.visa-country-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
  color: white;
  padding: 30px 25px 20px;
}

.visa-country-title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.visa-country-types {
  font-size: 0.9rem;
  opacity: 0.9;
}

.visa-country-content {
  padding: 25px;
}

.visa-country-content h4 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #d4af37;
  margin-bottom: 10px;
  margin-top: 20px;
}

.visa-country-content h4:first-child {
  margin-top: 0;
}

.visa-country-content p {
  color: #666;
  line-height: 1.6;
  font-size: 0.95rem;
  margin: 0;
}

.passport-services {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 30px;
}

.passport-service-item {
  background: white;
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.passport-service-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.passport-service-icon {
  font-size: 2.5rem;
  margin-bottom: 15px;
}

.passport-service-item h4 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.visa-process {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.visa-process-step {
  background: white;
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  position: relative;
  transition: all 0.3s ease;
}

.visa-process-step:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.visa-process-number {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #d4af37, #b8941f);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0 auto 20px;
}

.visa-process-content h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
}

.visa-process-content p {
  color: #666;
  line-height: 1.6;
  margin: 0;
}

.why-choose-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.why-choose-item {
  background: white;
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.why-choose-item:hover {
  background: linear-gradient(135deg, #d4af37, #b8941f);
  color: white;
  transform: translateY(-5px);
}

.why-choose-icon {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.why-choose-item h4 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 15px;
}

.why-choose-item p {
  line-height: 1.6;
  margin: 0;
  opacity: 0.9;
}

.visa-faqs {
  max-width: 800px;
  margin: 0 auto;
}

.visa-faq-item {
  background: white;
  border-radius: 15px;
  margin-bottom: 20px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.visa-faq-question {
  width: 100%;
  padding: 25px;
  background: none;
  border: none;
  text-align: left;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.visa-faq-question:hover {
  background: #f8f9fa;
}

.visa-faq-question h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.visa-faq-toggle {
  font-size: 1.5rem;
  color: #d4af37;
  font-weight: 700;
  transition: transform 0.3s ease;
}

.visa-faq-item.active .visa-faq-toggle {
  transform: rotate(45deg);
}

.visa-faq-answer {
  padding: 0 25px;
  max-height: 0;
  overflow: hidden;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.visa-faq-item.active .visa-faq-answer {
  max-height: 150px;
  padding: 25px;
}

.visa-faq-answer p {
  color: #666;
  line-height: 1.6;
  margin: 0;
}

.visa-contact-cta {
  background: linear-gradient(135deg, #1a5f7a, #57cc99);
  padding: 80px 0;
  color: white;
}

.visa-contact-content {
  text-align: center;
}

.visa-contact-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 50px;
}

.visa-contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  max-width: 1000px;
  margin: 0 auto;
}

.visa-contact-item {
  background: rgba(255, 255, 255, 0.1);
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.visa-contact-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-5px);
}

.visa-contact-icon {
  font-size: 2rem;
  margin-bottom: 15px;
}

.visa-contact-item h4 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 15px;
}

.visa-contact-item a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.visa-contact-item a:hover {
  color: #d4af37;
}

.visa-whatsapp-btn {
  display: inline-block;
  background: #25d366;
  color: white !important;
  padding: 10px 20px;
  border-radius: 25px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.visa-whatsapp-btn:hover {
  background: #20b558;
  transform: scale(1.05);
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .visa-hero {
    min-height: 60vh;
  }

  .visa-hero-title {
    font-size: 2.2rem;
  }

  .visa-hero-subtitle {
    font-size: 1.1rem;
  }

  .visa-hero-actions {
    flex-direction: column;
    align-items: center;
  }

  .visa-cta-btn {
    padding: 12px 25px;
    font-size: 1rem;
  }

  .visa-section-title {
    font-size: 2rem;
  }

  .visa-services-grid,
  .visa-info-cards,
  .passport-services,
  .visa-process,
  .why-choose-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .visa-countries-grid {
    grid-template-columns: 1fr;
    gap: 25px;
  }

  .visa-contact-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .visa-contact-title {
    font-size: 2rem;
  }

  .visa-main {
    padding: 60px 0;
  }

  .visa-section {
    margin-bottom: 60px;
  }

  .visa-important-info {
    padding: 30px 20px;
  }
}
</style>

<!-- Hero Section -->
<section class="visa-hero">
  <div class="visa-hero-bg">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/UK-visa.jpg" alt="Visa Services" class="visa-hero-image" />
    <div class="visa-hero-overlay"></div>
  </div>
  <div class="container">
    <div class="visa-hero-content">
      <h1 class="visa-hero-title">Visa Services — Simple, Guided, Reliable</h1>
      <p class="visa-hero-subtitle">We assist our clients in the visa and passport application process and make sure it runs smoothly. Whether for tourism, study, family, or business, Jetwide simplifies the process for Kenyan travelers.</p>
      <div class="visa-hero-actions">
        <a href="tel:+254748538311" class="visa-cta-btn">📞 Contact Us Today</a>
        <a href="https://wa.me/254748538311" class="visa-cta-btn secondary">💬 WhatsApp Us</a>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<main class="visa-main">
  <div class="container">
    
    <!-- Planning Section -->
    <section class="visa-section">
      <h2 class="visa-section-title">Planning Your Next Trip?</h2>
      <p class="visa-section-intro">Travelling abroad can be stressful, especially when navigating strict embassy requirements. Jetwide's Visa Desk helps you:</p>
      
      <div class="visa-services-grid">
        <div class="visa-service-card">
          <div class="visa-service-icon">✓</div>
          <h3>Choose the right visa type</h3>
          <p>Tourist, study, business, family</p>
        </div>
        <div class="visa-service-card">
          <div class="visa-service-icon">✓</div>
          <h3>Prepare and review all required documents</h3>
          <p>Complete documentation assistance</p>
        </div>
        <div class="visa-service-card">
          <div class="visa-service-icon">✓</div>
          <h3>Book embassy/VFS appointments</h3>
          <p>Secure your appointment slots</p>
        </div>
        <div class="visa-service-card">
          <div class="visa-service-icon">✓</div>
          <h3>Receive interview preparation support</h3>
          <p>If required for your application</p>
        </div>
      </div>
      
      <div class="visa-disclaimer">
        <p><strong>We do not issue visas</strong> — embassies and consulates make the final decision — but our role is to make the journey clear, organized, and stress-free.</p>
      </div>
    </section>

    <!-- Important Information -->
    <section class="visa-section">
      <div class="visa-important-info">
        <h2 class="visa-section-title">⚠️ Important Information</h2>
        <div class="visa-info-cards">
          <div class="visa-info-card">
            <h4>Embassy/VFS/Government Fees</h4>
            <p>Paid directly to official channels.</p>
          </div>
          <div class="visa-info-card">
            <h4>Jetwide Service Fees</h4>
            <p>Separate fees covering consultation, document preparation, appointment booking, and interview coaching.</p>
          </div>
          <div class="visa-info-card">
            <h4>Processing Times</h4>
            <p>Vary by country and season — we'll share realistic timelines during consultation.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Get a Visa For Section -->
    <section class="visa-section">
      <h2 class="visa-section-title">Get a Visa For</h2>
      
      <div class="visa-countries-grid">
        
        <!-- UK Visa -->
        <div class="visa-country-card">
          <div class="visa-country-header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/UK-visa.jpg" alt="UK Visa" class="visa-country-image" />
            <div class="visa-country-overlay">
              <h3 class="visa-country-title">United Kingdom (UK)</h3>
              <p class="visa-country-types">Tourist | Visit | Study | Business</p>
            </div>
          </div>
          <div class="visa-country-content">
            <div class="visa-checklist">
              <h4>Checklist:</h4>
              <p>Valid passport, photos, proof of funds, itinerary, invitation (if applicable), employer/student letters.</p>
            </div>
            <div class="visa-role">
              <h4>Our Role:</h4>
              <p>Visa type guidance, form support, document review, biometrics appointment booking, interview prep.</p>
            </div>
          </div>
        </div>

        <!-- Schengen Europe -->
        <div class="visa-country-card">
          <div class="visa-country-header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Shengchen-visa.jpg" alt="Schengen Visa" class="visa-country-image" />
            <div class="visa-country-overlay">
              <h3 class="visa-country-title">Schengen Europe</h3>
              <p class="visa-country-types">Tourist | Visit | Business</p>
            </div>
          </div>
          <div class="visa-country-content">
            <div class="visa-checklist">
              <h4>Checklist:</h4>
              <p>Application form, passport, travel insurance (€30,000+), flight & hotel bookings, bank statements, cover letter.</p>
            </div>
            <div class="visa-role">
              <h4>Our Role:</h4>
              <p>Embassy routing, document guidance, VFS appointment support, interview preparation.</p>
            </div>
          </div>
        </div>

        <!-- UAE/Dubai -->
        <div class="visa-country-card">
          <div class="visa-country-header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/UAE-visa.jpg" alt="UAE Visa" class="visa-country-image" />
            <div class="visa-country-overlay">
              <h3 class="visa-country-title">UAE / Dubai</h3>
              <p class="visa-country-types">Tourist | Visit | Business</p>
            </div>
          </div>
          <div class="visa-country-content">
            <div class="visa-options">
              <h4>Options:</h4>
              <p>30/60/90 days, single or multiple entry.</p>
            </div>
            <div class="visa-role">
              <h4>Our Role:</h4>
              <p>Eligibility confirmation, online submission through approved channels, tracking & updates.</p>
            </div>
            <div class="visa-notes">
              <h4>Notes:</h4>
              <p>No embassy interview required — eVisa issued electronically.</p>
            </div>
          </div>
        </div>

        <!-- United States -->
        <div class="visa-country-card">
          <div class="visa-country-header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/USA-visa.jpg" alt="USA Visa" class="visa-country-image" />
            <div class="visa-country-overlay">
              <h3 class="visa-country-title">United States</h3>
              <p class="visa-country-types">B1/B2 Tourist/Business | F-1 Study</p>
            </div>
          </div>
          <div class="visa-country-content">
            <div class="visa-checklist">
              <h4>Checklist:</h4>
              <p>DS-160 form, passport, financial proof, itinerary, SEVIS (for students).</p>
            </div>
            <div class="visa-role">
              <h4>Our Role:</h4>
              <p>Form assistance, MRV fee and appointment booking, interview prep.</p>
            </div>
            <div class="visa-notes">
              <h4>Notes:</h4>
              <p>Interview mandatory at US Embassy Nairobi — we help you prepare confidently.</p>
            </div>
          </div>
        </div>

        <!-- Canada -->
        <div class="visa-country-card">
          <div class="visa-country-header">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Canada-visa.jpg" alt="Canada Visa" class="visa-country-image" />
            <div class="visa-country-overlay">
              <h3 class="visa-country-title">Canada</h3>
              <p class="visa-country-types">Visitor | Study | Work</p>
            </div>
          </div>
          <div class="visa-country-content">
            <div class="visa-checklist">
              <h4>Checklist:</h4>
              <p>Passport, forms via IRCC portal, biometrics, financial proof, invitation letters (if required).</p>
            </div>
            <div class="visa-role">
              <h4>Our Role:</h4>
              <p>IRCC portal support, checklist preparation, VAC/VFS handling.</p>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Passport Services -->
    <section class="visa-section">
      <h2 class="visa-section-title">Passport Services</h2>
      <p class="visa-section-intro">Jetwide also assists with:</p>
      
      <div class="passport-services">
        <div class="passport-service-item">
          <div class="passport-service-icon">📖</div>
          <h4>New passport applications</h4>
        </div>
        <div class="passport-service-item">
          <div class="passport-service-icon">🔄</div>
          <h4>Renewals and replacements</h4>
        </div>
        <div class="passport-service-item">
          <div class="passport-service-icon">🚨</div>
          <h4>Lost/damaged passport re-issuance</h4>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section class="visa-section">
      <h2 class="visa-section-title">How It Works</h2>
      
      <div class="visa-process">
        <div class="visa-process-step">
          <div class="visa-process-number">1</div>
          <div class="visa-process-content">
            <h4>Pre-Check & Consult</h4>
            <p>Confirm the correct visa type and requirements.</p>
          </div>
        </div>
        <div class="visa-process-step">
          <div class="visa-process-number">2</div>
          <div class="visa-process-content">
            <h4>Document Preparation</h4>
            <p>Forms, photos, insurance, funds, invitations.</p>
          </div>
        </div>
        <div class="visa-process-step">
          <div class="visa-process-number">3</div>
          <div class="visa-process-content">
            <h4>Submission & Appointment</h4>
            <p>Embassy/VFS/online systems, biometrics.</p>
          </div>
        </div>
        <div class="visa-process-step">
          <div class="visa-process-number">4</div>
          <div class="visa-process-content">
            <h4>Decision & Passport</h4>
            <p>Guidance on tracking and collection.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Jetwide -->
    <section class="visa-section">
      <h2 class="visa-section-title">🌟 Why Choose Jetwide?</h2>
      
      <div class="why-choose-grid">
        <div class="why-choose-item">
          <div class="why-choose-icon">🌍</div>
          <h4>Local expertise, global routes</h4>
          <p>UK, Europe, USA, Canada, UAE, and more</p>
        </div>
        <div class="why-choose-item">
          <div class="why-choose-icon">⏱️</div>
          <h4>Clear timelines & real expectations</h4>
          <p>No false promises, realistic processing times</p>
        </div>
        <div class="why-choose-item">
          <div class="why-choose-icon">✅</div>
          <h4>Error-free documentation support</h4>
          <p>Avoid costly errors with professional guidance</p>
        </div>
        <div class="why-choose-item">
          <div class="why-choose-icon">🎭</div>
          <h4>Mock interview coaching</h4>
          <p>Where required for your application</p>
        </div>
        <div class="why-choose-item">
          <div class="why-choose-icon">🤝</div>
          <h4>Personalized assistance</h4>
          <p>Every step of the way</p>
        </div>
      </div>
    </section>

    <!-- FAQs -->
    <section class="visa-section">
      <h2 class="visa-section-title">❓ FAQs</h2>
      
      <div class="visa-faqs">
        <div class="visa-faq-item">
          <div class="visa-faq-question">
            <h4>Is a visa guaranteed?</h4>
            <span class="visa-faq-toggle">+</span>
          </div>
          <div class="visa-faq-answer">
            <p>No. Embassies/consulates make the final decision. Our role is to maximize your chances by submitting accurate and complete applications.</p>
          </div>
        </div>
        
        <div class="visa-faq-item">
          <div class="visa-faq-question">
            <h4>How long does it take?</h4>
            <span class="visa-faq-toggle">+</span>
          </div>
          <div class="visa-faq-answer">
            <p>Varies by country — UK (2–6 weeks), Schengen (15–21 days), US (depends on appointment availability), Canada (varies).</p>
          </div>
        </div>
        
        <div class="visa-faq-item">
          <div class="visa-faq-question">
            <h4>Do you book appointments?</h4>
            <span class="visa-faq-toggle">+</span>
          </div>
          <div class="visa-faq-answer">
            <p>Yes — we help secure your slot at embassies, consulates, or VFS centers.</p>
          </div>
        </div>
      </div>
    </section>

  </div>
</main>

<!-- Contact CTA Section -->
<section class="visa-contact-cta">
  <div class="container">
    <div class="visa-contact-content">
      <h2 class="visa-contact-title">📞 Ready to Apply?</h2>
      <div class="visa-contact-grid">
        <div class="visa-contact-item">
          <div class="visa-contact-icon">📞</div>
          <h4>Call us:</h4>
          <a href="tel:+254748538311">+254 748 538 311</a>
        </div>
        <div class="visa-contact-item">
          <div class="visa-contact-icon">✉️</div>
          <h4>Email:</h4>
          <a href="mailto:info@jetwide.org">info@jetwide.org</a>
        </div>
        <div class="visa-contact-item">
          <div class="visa-contact-icon">📍</div>
          <h4>Visit:</h4>
          <p>Westlands Square, 2nd Floor, Nairobi</p>
        </div>
        <div class="visa-contact-item">
          <div class="visa-contact-icon">💬</div>
          <h4>WhatsApp:</h4>
          <a href="https://wa.me/254748538311" class="visa-whatsapp-btn">WhatsApp Us Now</a>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
jQuery(document).ready(function($) {
  // FAQ functionality
  $('.visa-faq-question').click(function() {
    var faqItem = $(this).parent();
    var isActive = faqItem.hasClass('active');
    
    // Close all FAQ items
    $('.visa-faq-item').removeClass('active');
    $('.visa-faq-toggle').text('+');
    
    // Open clicked item if it wasn't active
    if (!isActive) {
      faqItem.addClass('active');
      $(this).find('.visa-faq-toggle').text('−');
    }
  });
});
</script>

<?php get_footer(); ?>