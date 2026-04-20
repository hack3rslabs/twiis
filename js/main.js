// ── Web Components ────────────────────────────────────────────
class SiteHeader extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
    <nav id="navbar">
      <div class="container">
        <a href="/" class="nav-logo"><img src="/images/logo.png" alt="Twiis Innovations"></a>
        <button class="menu-toggle" id="menu-toggle" aria-label="Toggle Menu">
          <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
          <li><a href="/">Home</a></li>
          <li><a href="/services">Services</a></li>
          <li><a href="/clients">Clients</a></li>
          <li><a href="/technologies">Technologies</a></li>
          <li><a href="/partners">Partners</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="/contact" class="btn btn-primary nav-cta">Get Secured</a></li>
        </ul>
      </div>
    </nav>`;
  }
}

class SiteFooter extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
    <footer>
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <a href="/" class="logo"><img src="/images/logo.png" alt="Twiis"></a>
            <p>Securing the future of global enterprises. A registered Startup India company delivering high-end cybersecurity resilience through <strong>fully remote, borderless operations.</strong></p>
            <div class="footer-social">
              <a href="https://www.linkedin.com/company/twiis-innovations/" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
            </div>
            <p style="margin-top:1rem;font-size:.85rem;"><i class="fas fa-envelope" style="color:var(--primary);margin-right:.4rem;"></i>help@twiis.in</p>
          </div>
          <div class="footer-links">
            <h4>Security Services</h4>
            <ul>
              <li><a href="/services/app-security">App Security</a></li>
              <li><a href="/services/endpoint-defense">Endpoint Defense</a></li>
              <li><a href="/services/cloud-security">Cloud Security</a></li>
              <li><a href="/services/vapt">AI-Guided VAPT</a></li>
              <li><a href="/services/network-security">Network Security</a></li>
              <li><a href="/services/dlp">Data Loss Prevention</a></li>
              <li><a href="/services/email-security">Email Security</a></li>
              <li><a href="/services/ad-security">Active Directory Security</a></li>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Solutions</h4>
            <ul>
              <li><a href="/services/software-development">Software Development</a></li>
              <li><a href="/services/remote-it-support">Remote IT Support</a></li>
              <li><a href="/clients">Sectors &amp; Clients</a></li>
              <li><a href="/partners">Partner Program</a></li>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Compliance</h4>
            <ul>
              <li><a href="/privacy">Privacy Policy</a></li>
              <li><a href="/terms">Terms of Service</a></li>
              <li><a href="/security-statement">Security Statement</a></li>
              <li><a href="/ethics">Ethics & Conduct</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Twiis Innovations OPC Pvt. Ltd. All rights reserved. Registered Startup India Company.</p>
        </div>
      </div>
    </footer>`;
  }
}

customElements.define('site-header', SiteHeader);
customElements.define('site-footer', SiteFooter);

// ── DOM Ready ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

  // Navbar scroll effect + mobile toggle
  const navbar  = document.getElementById('navbar');
  const toggle  = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');

  toggle && toggle.addEventListener('click', () => {
    navLinks.classList.toggle('open');
    toggle.classList.toggle('open');
  });

  // Close mobile menu on link click
  document.querySelectorAll('.nav-links a').forEach(a => {
    a.addEventListener('click', () => {
      navLinks && navLinks.classList.remove('open');
      toggle && toggle.classList.remove('open');
    });
  });

  // Active nav link
  const path = window.location.pathname.replace(/\/$/, '') || '/';
  document.querySelectorAll('.nav-links a').forEach(a => {
    const href = a.getAttribute('href').replace(/\/$/, '') || '/';
    if (href === path) a.classList.add('active');
  });

  // Scroll behaviour
  const onScroll = () => {
    if (!navbar) return;
    navbar.classList.toggle('scrolled', window.scrollY > 50);
    document.querySelectorAll('.reveal.hidden').forEach(el => {
      if (el.getBoundingClientRect().top < window.innerHeight - 80) {
        el.classList.remove('hidden');
      }
    });
  };
  // Init reveal
  document.querySelectorAll('.reveal').forEach(el => {
    if (el.getBoundingClientRect().top > window.innerHeight) el.classList.add('hidden');
  });
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ── Contact Form (Lead Capture to help@twiis.in) ───────────
  const form = document.getElementById('contact-form');
  if (form) {
    form.addEventListener('submit', e => {
      e.preventDefault();
      const nameEl = form.querySelector('input[name="name"]');
      const emailEl = form.querySelector('input[name="email"]');
      const companyEl = form.querySelector('input[name="company"]');
      const serviceEl = form.querySelector('select[name="service"]');
      const msgEl  = form.querySelector('textarea[name="message"]');
      const countryEl = form.querySelector('select[name="country_code"]');
      const phoneEl = form.querySelector('input[name="phone"]');
      const errorMsg = document.getElementById('email-error');
      
      // New Scoping Fields
      const infraEl = form.querySelector('input[name="infra"]:checked');
      const scaleEl = form.querySelector('input[name="scale"]:checked');
      
      if (!nameEl || !emailEl) return;
      if (nameEl.value.length > 120 || emailEl.value.length > 120) {
        alert('Input exceeds maximum allowed length.');
        return;
      }
      
      // Corporate Email Validation
      const freeEmailDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'aol.com', 'icloud.com', 'protonmail.com', 'yandex.com', 'mail.com'];
      const emailDomain = emailEl.value.split('@')[1]?.toLowerCase();
      
      if (freeEmailDomains.includes(emailDomain)) {
        emailEl.style.borderColor = '#ff3366';
        if (errorMsg) errorMsg.style.display = 'block';
        return;
      } else {
        emailEl.style.borderColor = '';
        if (errorMsg) errorMsg.style.display = 'none';
      }
      
      // Phone Number Validation (Country-Based Length)
      const phoneErrorMsg = document.getElementById('phone-error');
      const countryCode = countryEl ? countryEl.value : '';
      // Strip non-numeric characters for length check
      const digitsOnly = phoneEl.value.replace(/\D/g, '');
      
      let expectedLength = 0;
      let valid = true;

      if (countryCode === '+91') { expectedLength = 10; valid = digitsOnly.length === 10; }
      else if (countryCode === '+1') { expectedLength = 10; valid = digitsOnly.length === 10; }
      else if (countryCode === '+44') { valid = (digitsOnly.length === 10 || digitsOnly.length === 11); }
      else if (countryCode === '+61') { expectedLength = 9; valid = digitsOnly.length === 9; }
      else if (countryCode === '+971') { expectedLength = 9; valid = digitsOnly.length === 9; }
      else if (countryCode === '+65') { expectedLength = 8; valid = digitsOnly.length === 8; }
      else if (countryCode === '+33') { expectedLength = 9; valid = digitsOnly.length === 9; }
      else if (countryCode === '+49') { valid = (digitsOnly.length >= 10 && digitsOnly.length <= 11); }
      else { valid = (digitsOnly.length >= 7 && digitsOnly.length <= 15); } // Generic fallback

      if (!valid) {
        phoneEl.style.borderColor = '#ff3366';
        if (phoneErrorMsg) {
          phoneErrorMsg.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Invalid number length for ${countryCode}.`;
          if(expectedLength > 0) phoneErrorMsg.innerHTML += ` Required digits: ${expectedLength}.`;
          phoneErrorMsg.style.display = 'block';
        }
        return;
      } else {
        phoneEl.style.borderColor = '';
        if (phoneErrorMsg) phoneErrorMsg.style.display = 'none';
      }
      
      const safeName = document.createTextNode(nameEl.value).textContent;
      const btn = form.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Generating Secure Brief…';

      const fullPhone = (countryEl && phoneEl) ? `${countryEl.value} ${phoneEl.value}` : 'N/A';

      const leadData = {
          Name: nameEl.value,
          Email: emailEl.value,
          Phone: fullPhone,
          Company: companyEl ? companyEl.value : 'N/A',
          Infrastructure: infraEl ? infraEl.value : 'N/A',
          Scale: scaleEl ? scaleEl.value : 'N/A',
          Service_Required: serviceEl ? serviceEl.value : 'N/A',
          Requirements: msgEl ? msgEl.value : 'N/A',
          Timestamp: new Date().toISOString()
      };

      // 1. Send to FormSubmit (Email fallback)
      fetch("https://formsubmit.co/ajax/help@twiis.in", {
          method: "POST",
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({
              _subject: `New Enterprise Scoping Request: ${leadData.Company}`,
              ...leadData
          })
      });

      // Success UI
      form.innerHTML = `
        <div style="text-align:center;padding:2.5rem 1rem;">
          <i class="fas fa-check-circle" style="font-size:4rem;color:var(--primary);display:block;margin-bottom:1.5rem;"></i>
          <h3 style="margin-bottom:.8rem;">Scoping Brief Transmitted</h3>
          <p style="color:var(--text-muted);">Thank you, <strong>${safeName}</strong>. Your infrastructure details have been securely captured. Our engineers will review your scoping brief and contact you shortly.</p>
          <button onclick="location.reload()" class="btn btn-outline" style="margin-top:2rem;">Start New Scoping</button>
        </div>`;
    });
  }

  // ── Partner form (Lead Capture to help@twiis.in) ───────────
  const pform = document.getElementById('partner-form');
  if (pform) {
    pform.addEventListener('submit', e => {
      e.preventDefault();
      const nameEl = pform.querySelector('#p-name');
      const companyEl = pform.querySelector('#p-company');
      const btn = pform.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Transmitting…';
      
      const safeName = document.createTextNode(nameEl.value).textContent;
      const safeCompany = document.createTextNode(companyEl.value).textContent;

      fetch("https://formsubmit.co/ajax/help@twiis.in", {
          method: "POST",
          headers: { 
              'Content-Type': 'application/json',
              'Accept': 'application/json'
          },
          body: JSON.stringify({
              _subject: `New Technology Partnership Inquiry: ${companyEl.value}`,
              Name: nameEl.value,
              Company: companyEl.value,
              Type: "Partnership Request"
          })
      })
      .then(response => response.json())
      .then(data => {
        pform.innerHTML = `
          <div style="text-align:center;padding:2.5rem 1rem;">
            <i class="fas fa-check-circle" style="font-size:4rem;color:var(--primary);display:block;margin-bottom:1.5rem;"></i>
            <h3 style="margin-bottom:.8rem;">Partnership Enquiry Received</h3>
            <p style="color:var(--text-muted);">Thank you, <strong>${safeName}</strong> from <strong>${safeCompany}</strong>. Our partnerships team has been notified at help@twiis.in and will be in touch within one business day.</p>
            <a href="/" class="btn btn-outline" style="margin-top:2rem;">Back to Home</a>
          </div>`;
      })
      .catch(error => {
          console.error('Submission Error:', error);
          btn.disabled = false;
          btn.textContent = 'Transmission Failed. Try Again.';
          alert("There was an issue sending your request. Please email us directly at help@twiis.in.");
      });
    });
  }
});

// ── Canvas Particle Background (Neural Network Style) ───────────────
const canvas = document.getElementById('canvas-bg');
if (canvas) {
  const ctx = canvas.getContext('2d');
  let W = canvas.width  = window.innerWidth;
  let H = canvas.height = window.innerHeight;
  class Particle {
    constructor() { this.reset(); }
    reset() {
      this.x  = Math.random() * W;
      this.y  = Math.random() * H;
      this.r  = Math.random() * 1.5 + .5;
      this.vx = (Math.random() - .5) * .4;
      this.vy = (Math.random() - .5) * .4;
    }
    update() {
      this.x += this.vx; this.y += this.vy;
      if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
    }
    draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(0, 243, 255, 0.4)';
      ctx.fill();
    }
  }
  const particleCount = window.innerWidth < 768 ? 40 : 80;
  const particles = Array.from({ length: particleCount }, () => new Particle());
  
  function animate() {
    ctx.clearRect(0, 0, W, H);
    
    // Draw connections
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        
        if (dist < 120) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0, 243, 255, ${0.15 - dist/800})`;
          ctx.lineWidth = 0.6;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
    
    // Draw particles
    particles.forEach(p => { p.update(); p.draw(); });
    requestAnimationFrame(animate);
  }
  animate();
  window.addEventListener('resize', () => {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
    particles.forEach(p => p.reset());
  });
}
