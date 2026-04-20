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
          <li><a href="/clients">Ecosystem</a></li>
          <li><a href="/technologies">Technologies</a></li>
          <li><a href="/partners">Partners</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="https://twiis.in/helpdesk/">Help</a></li>
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
              <li><a href="/clients">Business Ecosystem</a></li>
              <li><a href="/partners">Partner Program</a></li>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Compliance</h4>
            <ul>
              <li><a href="/privacy">Privacy Policy</a></li>
              <li><a href="/terms">Terms of Service</a></li>
              <li><a href="https://twiis.in/helpdesk/">Helpdesk & Support</a></li>
              <li><a href="/security-statement">Security Statement</a></li>
              <li><a href="/ethics">Ethics & Conduct</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Twiis Innovations OPC Pvt. Ltd. All rights reserved. Registered Startup India Company.</p>
        </div>
      </div>
    </footer>
    <a href="https://twiis.in/helpdesk/" class="support-fab" title="Support Helpdesk" aria-label="Support">
      <i class="fas fa-headset"></i>
      <span>Help</span>
    </a>`;
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

  // Scroll effect & Reveal
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
});
