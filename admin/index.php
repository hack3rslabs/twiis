<?php
session_start();
if (!isset($_SESSION['twiis_admin']) || $_SESSION['twiis_admin'] !== true) {
    header('Location: /admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Twiis Admin | Enterprise Dashboard</title>
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f0f4f8; color: #1f3a5f; }
    .admin-nav { background: #1f3a5f; color: white; padding: 1rem 0; position: sticky; top: 0; z-index: 100; }
    .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
    .tab-nav { display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; }
    .tab-btn { background: none; border: none; font-size: 1rem; font-weight: 600; color: #6b7280; cursor: pointer; padding: 0.5rem 1rem; border-radius: 6px; transition: all 0.2s; }
    .tab-btn.active { color: #1f3a5f; background: #e2e8f0; }
    .admin-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { text-align: left; padding: 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.9rem; }
    th { color: #1f3a5f; font-weight: 700; background: #f8fafc; }
    .editor-form { display: grid; gap: 1rem; }
    .editor-form input, .editor-form textarea, .editor-form select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; }
    .toolbar { display: flex; gap: 0.5rem; margin-bottom: 0.5rem; }
    #editor-box { min-height: 200px; border: 1px solid #ddd; padding: 1rem; border-radius: 6px; }
  </style>
</head>
<body>

<div class="admin-nav">
  <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
    <h2 style="color:white; margin:0;">Twiis <span style="color:var(--secondary)">Admin</span></h2>
    <a href="/" class="btn btn-outline" style="color:white; border-color:white; padding:0.4rem 1rem; font-size:0.8rem;">View Site</a>
  </div>
</div>

<div class="admin-container">
  <div class="tab-nav">
    <button class="tab-btn active" onclick="showTab('leads', event)"><i class="fas fa-user-tie"></i> Leads</button>
    <button class="tab-btn" onclick="showTab('support', event)"><i class="fas fa-headset"></i> Support</button>
    <button class="tab-btn" onclick="showTab('blogs', event)"><i class="fas fa-blog"></i> Blogs</button>
    <button class="tab-btn" onclick="showTab('team', event)"><i class="fas fa-users"></i> Team</button>
    <button class="tab-btn" onclick="showTab('contacts', event)"><i class="fas fa-address-book"></i> Contacts</button>
    <button class="tab-btn" onclick="showTab('ecosystem', event)"><i class="fas fa-handshake"></i> Clients</button>
    <button class="tab-btn" onclick="showTab('content', event)"><i class="fas fa-edit"></i> CMS</button>
  </div>

  <!-- LEADS TAB -->
  <div id="leads" class="tab-content active"><div class="admin-card"><h3>Leads</h3><div id="leads-list">Loading...</div></div></div>
  <!-- SUPPORT TAB -->
  <div id="support" class="tab-content"><div class="admin-card"><h3>Tickets</h3><div id="tickets-list">Loading...</div></div></div>
  
  <!-- BLOGS TAB -->
  <div id="blogs" class="tab-content">
    <div class="admin-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3>Blogs</h3>
        <button class="btn btn-primary" onclick="newBlog()">+ New</button>
      </div>
      <div id="blogs-list">Loading...</div>
    </div>
    <div id="blog-editor-card" class="admin-card" style="display:none;">
      <h3 id="editor-title">Edit Post</h3>
      <div class="editor-form">
        <input type="hidden" id="blog-id"><input type="text" id="blog-title" placeholder="Title">
        <input type="text" id="blog-author" placeholder="Author"><input type="text" id="blog-image" placeholder="Image URL">
        <div class="toolbar"><button type="button" onclick="execCmd('bold')">B</button><button type="button" onclick="execCmd('italic')">I</button><button type="button" onclick="execCmd('formatBlock', 'h2')">H2</button></div>
        <div id="editor-box" contenteditable="true"></div>
        <button class="btn btn-primary" onclick="saveBlog()">Save</button>
      </div>
    </div>
  </div>

  <!-- TEAM TAB -->
  <div id="team" class="tab-content">
    <div class="admin-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3>Team</h3>
        <button class="btn btn-primary" onclick="newMember()">+ Add</button>
      </div>
      <div id="team-list">Loading...</div>
    </div>
    <div id="team-editor-card" class="admin-card" style="display:none;">
      <h3 id="team-title">Add Member</h3>
      <div class="editor-form">
        <input type="hidden" id="member-id"><input type="text" id="member-name" placeholder="Name">
        <input type="text" id="member-designation" placeholder="Designation"><input type="text" id="member-linkedin" placeholder="LinkedIn">
        <input type="text" id="member-photo" placeholder="Photo Path">
        <select id="member-hierarchy"><option value="1">Exec</option><option value="4">Eng</option></select>
        <button class="btn btn-primary" onclick="saveMember()">Save</button>
      </div>
    </div>
  </div>

  <!-- CONTACTS TAB -->
  <div id="contacts" class="tab-content">
    <div class="admin-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3>Business Contacts</h3>
        <button class="btn btn-primary" onclick="newContact()">+ Add Contact</button>
      </div>
      <div id="contacts-list">Loading...</div>
    </div>
    <div id="contact-editor-card" class="admin-card" style="display:none;">
      <h3 id="contact-title">Add Contact</h3>
      <div class="editor-form" style="grid-template-columns: 1fr 1fr;">
        <input type="hidden" id="contact-id"><input type="text" id="contact-name" placeholder="Name">
        <input type="text" id="contact-business" placeholder="Business Name"><input type="text" id="contact-nature" placeholder="Business Nature">
        <input type="text" id="contact-address" placeholder="Address"><input type="email" id="contact-email" placeholder="Email">
        <input type="tel" id="contact-mobile" placeholder="Mobile"><input type="text" id="contact-role" placeholder="Role/Dept">
        <button class="btn btn-primary" onclick="saveContact()">Save</button>
        <button class="btn btn-outline" onclick="closeEditor('contact-editor-card')">Cancel</button>
      </div>
    </div>
  </div>

  <!-- CLIENTS/ECOSYSTEM TAB -->
  <div id="ecosystem" class="tab-content">
    <div class="admin-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3>Client Ecosystem</h3>
        <button class="btn btn-primary" onclick="newClient()">+ Add Client</button>
      </div>
      <div id="clients-list">Loading...</div>
    </div>
    <div id="client-editor-card" class="admin-card" style="display:none;">
      <h3 id="client-editor-title">Add Client</h3>
      <div class="editor-form" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
        <input type="hidden" id="client-id">
        <div>
          <label style="font-weight: 600; font-size: 0.9rem;">Client/Venture Name</label>
          <input type="text" id="client-name" placeholder="Name">
        </div>
        <div>
          <label style="font-weight: 600; font-size: 0.9rem;">Domain URL</label>
          <input type="text" id="client-url" placeholder="https://example.com">
        </div>
        <div style="grid-column: span 2;">
          <label style="font-weight: 600; font-size: 0.9rem;">Description</label>
          <textarea id="client-desc" placeholder="Brief description of client/venture" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"></textarea>
        </div>
        <div>
          <label style="font-weight: 600; font-size: 0.9rem;">Category</label>
          <select id="client-category" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;">
            <option value="client">Client Portfolio</option>
            <option value="venture">Twiis Ventures (Internal)</option>
          </select>
        </div>
        <div>
          <label style="font-weight: 600; font-size: 0.9rem;">Client Type/Tag</label>
          <input type="text" id="client-tag" placeholder="e.g. E-Commerce, EdTech, IT Solutions">
        </div>
        <div style="grid-column: span 2; display: flex; gap: 0.5rem; margin-top: 0.5rem;">
          <button class="btn btn-primary" onclick="saveClient()">Save</button>
          <button class="btn btn-outline" onclick="closeEditor('client-editor-card')">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <!-- CMS TAB -->
  <div id="content" class="tab-content">
    <div class="admin-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3>Site Content Management</h3>
        <button class="btn btn-primary" onclick="saveSiteContent()">Save All Content</button>
      </div>
      <div class="editor-form" id="cms-form">
        <label>Hero Title</label>
        <input type="text" id="cms-hero-title">
        <label>Hero Subtitle</label>
        <textarea id="cms-hero-subtitle" rows="3"></textarea>
        
        <label>Products (JSON format)</label>
        <textarea id="cms-products" rows="4"></textarea>
        
        <label>Services (JSON format)</label>
        <textarea id="cms-services" rows="4"></textarea>

        <label>Clients (JSON format)</label>
        <textarea id="cms-clients" rows="4"></textarea>

        <label>Footer Menu (JSON format)</label>
        <textarea id="cms-footer" rows="4"></textarea>
      </div>
    </div>
  </div>
</div>

<script>
  // session validated by backend

  function showTab(tabId, event) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
    if (tabId === 'leads') fetchLeads();
    else if (tabId === 'support') fetchTickets();
    else if (tabId === 'blogs') fetchBlogs();
    else if (tabId === 'team') fetchTeam();
    else if (tabId === 'contacts') fetchContacts();
    else if (tabId === 'ecosystem') fetchClients();
    else if (tabId === 'content') fetchSiteContent();
  }

  function execCmd(cmd, val = null) { document.execCommand(cmd, false, val); }
  async function apiFetch(url, options = {}) { return await (await fetch('..' + url, options)).json(); }

  async function fetchLeads() {
    const data = await apiFetch('/api/leads.php');
    document.getElementById('leads-list').innerHTML = data.length === 0 ? "No leads." : 
        `<table><tr><th>Name</th><th>Status</th></tr>` + data.reverse().map(l => `<tr><td>${l.name}</td><td>${l.status}</td></tr>`).join('') + `</table>`;
  }
  async function fetchTickets() {
    const data = await apiFetch('/api/tickets.php');
    document.getElementById('tickets-list').innerHTML = data.length === 0 ? "No tickets." : 
        `<table><tr><th>ID</th><th>Subject</th></tr>` + data.reverse().map(t => `<tr><td>${t.id}</td><td>${t.description.substring(0, 30)}</td></tr>`).join('') + `</table>`;
  }
  async function fetchBlogs() {
    const data = await apiFetch('/api/blogs.php');
    document.getElementById('blogs-list').innerHTML = `<table>` + data.map(b => `<tr><td>${b.title}</td><td><button onclick='editBlog(${JSON.stringify(b)})'>Edit</button></td></tr>`).join('') + `</table>`;
  }
  function editBlog(b) {
    document.getElementById('blog-id').value = b.id;
    document.getElementById('blog-title').value = b.title;
    document.getElementById('editor-box').innerHTML = b.content;
    document.getElementById('blog-editor-card').style.display = "block";
  }
  async function saveBlog() {
    const b = { title: document.getElementById('blog-title').value, content: document.getElementById('editor-box').innerHTML };
    if (document.getElementById('blog-id').value) b.id = document.getElementById('blog-id').value;
    await fetch('../api/save-blog.php', { method: 'POST', body: JSON.stringify(b) });
    alert('Saved!'); fetchBlogs();
  }
  async function fetchTeam() {
    const data = await apiFetch('/api/team.php');
    document.getElementById('team-list').innerHTML = `<table>` + data.map(m => `<tr><td>${m.name}</td><td><button onclick='editMember(${JSON.stringify(m)})'>Edit</button></td></tr>`).join('') + `</table>`;
  }
  function editMember(m) {
    document.getElementById('member-id').value = m.id;
    document.getElementById('member-name').value = m.name;
    document.getElementById('team-editor-card').style.display = "block";
  }
  async function saveMember() {
    const m = { name: document.getElementById('member-name').value, hierarchy: document.getElementById('member-hierarchy').value };
    if (document.getElementById('member-id').value) m.id = document.getElementById('member-id').value;
    await fetch('../api/save-team.php', { method: 'POST', body: JSON.stringify(m) });
    alert('Saved!'); fetchTeam();
  }
  async function fetchContacts() {
    const data = await apiFetch('/api/contacts.php');
    document.getElementById('contacts-list').innerHTML = data.length === 0 ? "No contacts." :
        `<table>
          <tr>
            <th>Name</th>
            <th>Business</th>
            <th>Role</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Actions</th>
          </tr>` + 
        data.map(c => `
          <tr>
            <td><strong>${c.name}</strong></td>
            <td>${c.business || '-'} (${c.nature || '-'})</td>
            <td>${c.role || '-'}</td>
            <td>${c.email ? `<a href="mailto:${c.email}" style="color:#0369a1; font-weight:600; text-decoration:none;">${c.email}</a>` : '-'}</td>
            <td>${c.mobile ? `<a href="tel:${c.mobile}" style="color:#0369a1; font-weight:600; text-decoration:none;">${c.mobile}</a>` : '-'}</td>
            <td>
              <button onclick='editContact(${JSON.stringify(c).replace(/'/g, "&apos;")})' style="background: #1f3a5f; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; margin-right: 4px;">Edit</button>
              <button onclick='deleteContact("${c.id}")' style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Delete</button>
            </td>
          </tr>`).join('') + `</table>`;
  }
  function newContact() {
    document.getElementById('contact-title').innerText = "Add Contact";
    ['contact-id', 'contact-name', 'contact-business', 'contact-nature', 'contact-address', 'contact-email', 'contact-mobile', 'contact-role'].forEach(id => document.getElementById(id).value = "");
    document.getElementById('contact-editor-card').style.display = "block";
  }
  function editContact(c) {
    document.getElementById('contact-title').innerText = "Edit Contact";
    document.getElementById('contact-id').value = c.id || "";
    document.getElementById('contact-name').value = c.name || "";
    document.getElementById('contact-business').value = c.business || "";
    document.getElementById('contact-nature').value = c.nature || "";
    document.getElementById('contact-address').value = c.address || "";
    document.getElementById('contact-email').value = c.email || "";
    document.getElementById('contact-mobile').value = c.mobile || "";
    document.getElementById('contact-role').value = c.role || "";
    document.getElementById('contact-editor-card').style.display = "block";
    document.getElementById('contact-editor-card').scrollIntoView({ behavior: 'smooth' });
  }
  async function saveContact() {
    const c = { name: document.getElementById('contact-name').value, business: document.getElementById('contact-business').value, nature: document.getElementById('contact-nature').value, address: document.getElementById('contact-address').value, email: document.getElementById('contact-email').value, mobile: document.getElementById('contact-mobile').value, role: document.getElementById('contact-role').value };
    if (document.getElementById('contact-id').value) c.id = document.getElementById('contact-id').value;
    const res = await fetch('../api/save-contact.php', { method: 'POST', body: JSON.stringify(c) });
    const data = await res.json();
    if (data.status === 'success') {
      alert('Saved!');
      closeEditor('contact-editor-card');
      fetchContacts();
    } else {
      alert('Failed: ' + (data.message || 'Unknown error'));
    }
  }
  async function deleteContact(id) {
    if (!confirm('Are you sure you want to delete this contact?')) return;
    try {
      const res = await fetch('../api/delete-contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });
      const resData = await res.json();
      if (resData.status === 'success') {
        alert(resData.message);
        fetchContacts();
      } else {
        alert('Error: ' + resData.message);
      }
    } catch (e) {
      alert('Delete failed.');
    }
  }
  function closeEditor(id) { document.getElementById(id).style.display = "none"; }
  function newMember() { document.getElementById('team-title').innerText = "Add Member"; document.getElementById('team-editor-card').style.display = "block"; }
  function newBlog() { document.getElementById('editor-title').innerText = "Create Blog"; document.getElementById('blog-editor-card').style.display = "block"; }
  async function fetchSiteContent() {
    const data = await apiFetch('/api/content.php');
    if (data.hero) {
      document.getElementById('cms-hero-title').value = data.hero.title || '';
      document.getElementById('cms-hero-subtitle').value = data.hero.subtitle || '';
      document.getElementById('cms-products').value = JSON.stringify(data.products || [], null, 2);
      document.getElementById('cms-services').value = JSON.stringify(data.services || [], null, 2);
      document.getElementById('cms-clients').value = JSON.stringify(data.clients || [], null, 2);
      document.getElementById('cms-footer').value = JSON.stringify(data.footer || [], null, 2);
    }
  }

  async function saveSiteContent() {
    try {
      const payload = {
        hero: {
          title: document.getElementById('cms-hero-title').value,
          subtitle: document.getElementById('cms-hero-subtitle').value
        },
        products: JSON.parse(document.getElementById('cms-products').value),
        services: JSON.parse(document.getElementById('cms-services').value),
        clients: JSON.parse(document.getElementById('cms-clients').value),
        footer: JSON.parse(document.getElementById('cms-footer').value)
      };
      
      const res = await fetch('../api/save-content.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (data.status === 'success') alert('Site content saved successfully!');
      else alert('Failed to save: ' + (data.message || 'Unknown error'));
    } catch (e) {
      alert('Invalid JSON format in one of the fields!');
    }
  }
  
  async function fetchClients() {
    const data = await apiFetch('/api/clients.php');
    document.getElementById('clients-list').innerHTML = data.length === 0 ? "No clients added yet." : 
        `<table>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Type</th>
            <th>URL</th>
            <th>Actions</th>
          </tr>` + 
        data.map(c => `
          <tr>
            <td><strong>${c.name}</strong></td>
            <td><span style="text-transform: capitalize; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; background: ${c.category === 'venture' ? '#e0f2fe; color: #0369a1;' : '#f3f4f6; color: #374151;'}">${c.category}</span></td>
            <td>${c.client_type || '-'}</td>
            <td><a href="${c.url}" target="_blank" style="color: var(--primary); text-decoration: none;">${c.url || 'No URL'}</a></td>
            <td>
              <button onclick='editClient(${JSON.stringify(c).replace(/'/g, "&apos;")})' style="background: #1f3a5f; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; margin-right: 4px;">Edit</button>
              <button onclick='deleteClient(${c.id})' style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Delete</button>
            </td>
          </tr>`).join('') + `</table>`;
  }

  function newClient() {
    document.getElementById('client-editor-title').innerText = "Add New Client/Venture";
    document.getElementById('client-id').value = "";
    document.getElementById('client-name').value = "";
    document.getElementById('client-url').value = "";
    document.getElementById('client-desc').value = "";
    document.getElementById('client-category').value = "client";
    document.getElementById('client-tag').value = "";
    document.getElementById('client-editor-card').style.display = "block";
    document.getElementById('client-editor-card').scrollIntoView({ behavior: 'smooth' });
  }

  function editClient(c) {
    document.getElementById('client-editor-title').innerText = "Edit Client/Venture";
    document.getElementById('client-id').value = c.id;
    document.getElementById('client-name').value = c.name;
    document.getElementById('client-url').value = c.url;
    document.getElementById('client-desc').value = c.description;
    document.getElementById('client-category').value = c.category;
    document.getElementById('client-tag').value = c.client_type;
    document.getElementById('client-editor-card').style.display = "block";
    document.getElementById('client-editor-card').scrollIntoView({ behavior: 'smooth' });
  }

  async function saveClient() {
    const payload = {
      name: document.getElementById('client-name').value,
      url: document.getElementById('client-url').value,
      description: document.getElementById('client-desc').value,
      category: document.getElementById('client-category').value,
      client_type: document.getElementById('client-tag').value
    };
    if (document.getElementById('client-id').value) {
      payload.id = document.getElementById('client-id').value;
    }
    
    if (!payload.name) {
      alert("Name is required!");
      return;
    }

    try {
      const res = await fetch('../api/save-client.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const resData = await res.json();
      if (resData.status === 'success') {
        alert(resData.message);
        closeEditor('client-editor-card');
        fetchClients();
      } else {
        alert('Error: ' + resData.message);
      }
    } catch (e) {
      alert('Save failed.');
    }
  }

  async function deleteClient(id) {
    if (!confirm('Are you sure you want to delete this client?')) return;
    try {
      const res = await fetch('../api/delete-client.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });
      const resData = await res.json();
      if (resData.status === 'success') {
        alert(resData.message);
        fetchClients();
      } else {
        alert('Error: ' + resData.message);
      }
    } catch (e) {
      alert('Delete failed.');
    }
  }

  fetchLeads();
</script>
</body>
</html>
