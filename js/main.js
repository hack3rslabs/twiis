// ── Web Components ────────────────────────────────────────────
class SiteHeader extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
    <nav id="navbar">
      <div class="container">
        <a href="/" class="logo-brand">
          <div class="logo-icon">T</div>
          Twiis
        </a>
        <button class="menu-toggle" id="menu-toggle" aria-label="Toggle Menu">
          <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
          <li><a href="/">Home</a></li>
          <li class="dropdown">
            <a href="/services" class="drop-trigger">Services <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="/services/it-solutions">IT Solutions & Support</a></li>
              <li><a href="/services/software-development">Software Development</a></li>
              <li><a href="/services/web-development">Web Development</a></li>
              <li><a href="/services/devops-devsecops">DevOps & DevSecOps</a></li>
              <li><a href="/services/qa-testing">QA & Testing</a></li>
              <li><a href="/services/managed-hosting">Managed Hosting</a></li>
              <li><a href="/services/app-security">App Security</a></li>
              <li><a href="/services/vapt">VAPT & Pentesting</a></li>
              <li><a href="/services/cloud-security">Cloud Security</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="/products" class="drop-trigger">Products <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="/products">All Products</a></li>
              <li><a href="https://ledger.twiis.in" target="_blank">Ledger Book</a></li>
              <li><a href="/products">ERP Suite</a></li>
              <li><a href="/products">School Management</a></li>
              <li><a href="/products">College Management</a></li>
            </ul>
          </li>
          <li><a href="/clients">Ecosystem</a></li>
          <li class="dropdown">
            <a href="/about" class="drop-trigger">About <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="/about">Our Company</a></li>
              <li><a href="/team">Engineering Team</a></li>
            </ul>
          </li>
          <li><a href="/contact" class="btn btn-primary nav-cta">Contact Us</a></li>
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
            <a href="/" class="logo-brand">
              <div class="logo-icon">T</div>
              Twiis
            </a>
            <p>Securing the future of global enterprises through elite <strong>SaaS innovation</strong> and <strong>cybersecurity resilience</strong>.</p>
            <div class="footer-social">
              <a href="https://www.linkedin.com/company/twiis-innovations/" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
            </div>
            <p style="margin-top:1rem;font-size:.85rem;"><i class="fas fa-envelope" style="color:var(--primary);margin-right:.4rem;"></i>help@twiis.in</p>
          </div>
          <div class="footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><a href="/services/it-solutions">IT Solutions</a></li>
              <li><a href="/services/software-development">Software Development</a></li>
              <li><a href="/services/app-security">App Security</a></li>
              <li><a href="/services/vapt">AI-Guided VAPT</a></li>
              <li><a href="/services/cloud-security">Cloud Security</a></li>
              <li><a href="/services/endpoint-defense">Endpoint Defense</a></li>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Our Products</h4>
            <ul>
              <li><a href="https://ledger.twiis.in">Ledger Book</a></li>
              <li><a href="/products">Twiis ERP Suite</a></li>
              <li><a href="/products">School Management</a></li>
              <li><a href="/products">College Management</a></li>
              <li><a href="/products">Custom SaaS</a></li>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Company</h4>
            <ul>
              <li><a href="/about">About Us</a></li>
              <li><a href="/contact">Contact Us</a></li>
              <li><a href="/blogs">Engineering Blog</a></li>
              <li><a href="/clients">Business Ecosystem</a></li>
              <li><a href="/privacy">Privacy Policy</a></li>
              <li><a href="/terms">Terms of Service</a></li>
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

  // Load Dynamic CMS Content
  async function loadDynamicContent() {
    try {
      const res = await fetch('/api/content.php');
      if (res.ok) {
        const data = await res.json();
        
        // Update first hero slide if it exists
        const heroH1 = document.querySelector('.slide:nth-child(1) .hero-content h1');
        const heroP = document.querySelector('.slide:nth-child(1) .hero-content p');
        if (heroH1 && data.hero?.title) {
            heroH1.innerHTML = data.hero.title.replace('Software Engineering', '<span class="gradient-text">Software Engineering</span>');
        }
        if (heroP && data.hero?.subtitle) {
            heroP.innerText = data.hero.subtitle;
        }

        // We could theoretically loop through data.products, data.clients here
        // and inject them into specific DOM containers if they exist.
      }
    } catch (e) {
      console.log('CMS dynamic content load failed:', e);
    }
  }
  loadDynamicContent();
});
