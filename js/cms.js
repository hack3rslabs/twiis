/**
 * Twiis CMS Client - Diagnostic Version
 * Added granular error reporting to fix save issues.
 */

(function() {
  console.log("CMS: Loaded.");
  
  const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
  if (!isLocal) return;
  if (sessionStorage.getItem('twiis_admin') !== 'true') return;

  // Panel Styling
  const style = document.createElement('style');
  style.innerHTML = `
    .cms-panel { position: fixed; bottom: 20px; left: 20px; z-index: 9999; display: flex; gap: 10px; background: rgba(31, 58, 95, 0.95); padding: 12px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); }
    .cms-btn { background: #1f3a5f; color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 700; font-size: 14px; transition: all 0.2s; }
    .cms-btn:hover { background: #1f7a5c; }
    .cms-btn.save { background: #1f7a5c; }
    [contenteditable="true"] { outline: 2px dashed #1f7a5c !important; background: rgba(31, 122, 92, 0.05) !important; }
  `;
  document.head.appendChild(style);

  const panel = document.createElement('div');
  panel.className = 'cms-panel';
  document.body.appendChild(panel);

  const editBtn = document.createElement('button');
  editBtn.className = 'cms-btn';
  editBtn.innerText = 'Edit Content';
  panel.appendChild(editBtn);

  const saveBtn = document.createElement('button');
  saveBtn.className = 'cms-btn save';
  saveBtn.innerText = 'Save Changes';
  saveBtn.style.display = 'none';
  panel.appendChild(saveBtn);

  let isEditing = false;

  editBtn.onclick = () => {
    isEditing = !isEditing;
    document.querySelectorAll('h1, h2, h3, h4, p, li, span, a.btn').forEach(el => {
      el.setAttribute('contenteditable', isEditing ? 'true' : 'false');
    });
    editBtn.innerText = isEditing ? 'Stop Editing' : 'Edit Content';
    saveBtn.style.display = isEditing ? 'block' : 'none';
  };

  saveBtn.onclick = async () => {
    console.log("CMS: Preparing to save...");
    
    // Clean up
    panel.style.display = 'none';
    document.querySelectorAll('[contenteditable="true"]').forEach(el => el.removeAttribute('contenteditable'));
    
    // Clone body to manipulate without affecting live page immediately
    const clone = document.documentElement.cloneNode(true);
    clone.querySelector('.cms-panel').remove();
    const entryBtn = clone.querySelector('button[style*="position:fixed"]');
    if (entryBtn) entryBtn.remove();
    
    const fullHtml = '<!DOCTYPE html>\\n' + clone.outerHTML;
    
    const pagePath = window.location.pathname === '/' ? '/index.html' : 
                     (window.location.pathname.endsWith('/') ? window.location.pathname + 'index.html' : 
                     (window.location.pathname.endsWith('.html') ? window.location.pathname : window.location.pathname + '/index.html'));
    
    const payload = { 
        path: pagePath.replace(/\/\//g, '/'), 
        content: fullHtml 
    };

    console.log("CMS: Sending payload to /api/save-page.php", payload);

    try {
      const res = await fetch('/api/save-page.php', { 
          method: 'POST', 
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(payload) 
      });
      
      const responseText = await res.text();
      console.log("CMS: Server response:", responseText);

      if (res.ok) {
        alert('Page saved successfully!');
        window.location.reload();
      } else {
        alert('Save failed. Check console for details.');
      }
    } catch (err) {
      console.error("CMS: Save error:", err);
      alert('Save error. Check console.');
      panel.style.display = 'flex';
    }
  };
})();
