const fs = require('fs');
const path = require('path');

const fileMappings = [
  {
    file: 'about/index.html',
    old: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=900',
    new: '/images/chatgpt-banner.png'
  },
  {
    file: 'services/ad-security/index.html',
    old: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800',
    new: '/images/endpoint-defense.png'
  },
  {
    file: 'services/app-security/index.html',
    old: 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800',
    new: '/images/app-security.png'
  },
  {
    file: 'services/cloud-security/index.html',
    old: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&q=80&w=800',
    new: '/images/cloud-security.png'
  },
  {
    file: 'services/dlp/index.html',
    old: 'https://images.unsplash.com/photo-1558494949-ef01091118a4?auto=format&fit=crop&q=80&w=800',
    new: '/images/dlp.png'
  },
  {
    file: 'services/email-security/index.html',
    old: 'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?auto=format&fit=crop&q=80&w=800',
    new: '/images/ecosystem.png'
  },
  {
    file: 'services/endpoint-defense/index.html',
    old: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800',
    new: '/images/endpoint-defense.png'
  },
  {
    file: 'services/it-solutions/index.html',
    old: 'https://images.unsplash.com/photo-1558494949-ef010f5e0fd0?auto=format&fit=crop&q=80&w=800',
    new: '/images/it-solutions.png'
  },
  {
    file: 'services/qa-testing/index.html',
    old: 'https://images.unsplash.com/photo-1518349619113-03114f06ac3a?auto=format&fit=crop&q=80&w=800',
    new: '/images/qa-software-testing.png'
  },
  {
    file: 'services/network-security/index.html',
    old: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&q=80&w=800',
    new: '/images/cloud-security.png'
  },
  {
    file: 'services/vapt/index.html',
    old: 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?auto=format&fit=crop&q=80&w=800',
    new: '/images/vapt.png'
  },
  {
    file: 'services/remote-it-support/index.html',
    old: 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&q=80&w=800',
    new: '/images/our-services.png'
  },
  {
    file: 'services/software-development/index.html',
    old: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=800',
    new: '/images/our-products.png'
  },
  {
    file: 'services/web-development/index.html',
    old: 'https://images.unsplash.com/photo-1547658719-da2b51169166?auto=format&fit=crop&q=80&w=800',
    new: '/images/our-services.png'
  }
];

const basePath = 'c:\\xampp\\htdocs\\twiis';
for (const map of fileMappings) {
  const p = path.join(basePath, map.file);
  if (fs.existsSync(p)) {
    let content = fs.readFileSync(p, 'utf-8');
    content = content.replace(map.old, map.new);
    fs.writeFileSync(p, content);
    console.log('Updated ' + map.file);
  }
}

const prodP = path.join(basePath, 'products/index.html');
if (fs.existsSync(prodP)) {
  let c = fs.readFileSync(prodP, 'utf-8');
  c = c.replace(
    '<i class="fas fa-layer-group shield-icon" style="font-size: 10rem;"></i>',
    '<img src="/images/products-hero.png" alt="Products" style="max-width:100%; border-radius:12px; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.12);">'
  );
  fs.writeFileSync(prodP, c);
  console.log('Updated products');
}

const servP = path.join(basePath, 'services/index.html');
if (fs.existsSync(servP)) {
  let c = fs.readFileSync(servP, 'utf-8');
  c = c.replace(
    '<i class="fas fa-network-wired shield-icon" style="font-size: 10rem;"></i>',
    '<img src="/images/services-hero.png" alt="Services" style="max-width:100%; border-radius:12px; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.12);">'
  );
  fs.writeFileSync(servP, c);
  console.log('Updated services');
}
