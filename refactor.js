const fs = require('fs');
const path = require('path');

function walk(dir) {
  let results = [];
  const list = fs.readdirSync(dir);
  list.forEach(file => {
    file = path.join(dir, file);
    const stat = fs.statSync(file);
    if (stat && stat.isDirectory() && !file.includes('.git') && !file.includes('node_modules')) {
      results = results.concat(walk(file));
    } else if (file.endsWith('.html')) {
      results.push(file);
    }
  });
  return results;
}

const htmlFiles = walk('c:\\xampp\\htdocs\\twiis');

for (const file of htmlFiles) {
  let content = fs.readFileSync(file, 'utf-8');
  let original = content;

  // Remove padding 10rem 0 6rem from page-hero
  content = content.replace(/<section class="page-hero white-hero" style="padding: 10rem 0 6rem; border:none;">/g, '<section class="page-hero white-hero">');
  content = content.replace(/<section class="page-hero white-hero" style="padding: 10rem 0 6rem;">/g, '<section class="page-hero white-hero">');
  
  // Replace max-width 840px with prose
  content = content.replace(/<div style="max-width:840px;" class="reveal">/g, '<div class="prose reveal">');
  
  // Also fix any text-muted color if it's already in prose, actually it's fine
  // Remove image inline styles
  content = content.replace(/style="border-radius: 20px; box-shadow: 0 30px 60px -15px rgba\(0,0,0,0\.12\);"/g, '');
  content = content.replace(/style="max-width:100%; border-radius:12px; box-shadow: 0 30px 60px -15px rgba\(0,0,0,0\.12\);"/g, '');
  
  if (content !== original) {
    fs.writeFileSync(file, content, 'utf-8');
    console.log('Refactored ' + file);
  }
}
