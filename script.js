// =================================================
// DarLoc — JavaScript natif (DOM + Validation + AJAX)
// =================================================

document.addEventListener('DOMContentLoaded', () => {
  initRangeDisplay();
  initLiveSearch();      // AJAX
  initAuthValidation();  // Validation formulaires
  initAdminForm();       // Validation form admin
  initStickyHeader();
});

// ---------- Affichage dynamique du slider de prix (DOM) ----------
function initRangeDisplay() {
  const range = document.getElementById('maxPrice');
  const out   = document.getElementById('maxPriceLabel');
  if (!range || !out) return;
  const update = () => out.textContent = Number(range.value).toLocaleString() + ' TND';
  range.addEventListener('input', update);
  update();
}

// ---------- Recherche AJAX (sans rechargement) ----------
function initLiveSearch() {
  const form = document.getElementById('searchForm');
  const grid = document.getElementById('resultsGrid');
  const count = document.getElementById('resultsCount');
  if (!form || !grid) return;

  const fetchResults = () => {
    const params = new URLSearchParams(new FormData(form));
    if (form.querySelector('#onlyAvailable')?.checked) {
      params.set('available', '1');
    }

    grid.style.opacity = '0.5';

    fetch('back/search.php?' + params.toString())
      .then(r => r.json())
      .then(data => {
        renderCards(data, grid);
        if (count) {
          count.textContent = data.length + ' résultat' + (data.length > 1 ? 's' : '');
        }
        grid.style.opacity = '1';
      })
      .catch(err => {
        console.error('Erreur AJAX :', err);
        grid.innerHTML = '<div class="empty"><h3>Erreur de chargement</h3></div>';
        grid.style.opacity = '1';
      });
  };

  // Soumission classique → on intercepte
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    fetchResults();
  });

  // Live filter (changement de ville/type/dispo, frappe dans recherche)
  form.querySelectorAll('input, select').forEach(el => {
    el.addEventListener('change', fetchResults);
  });
  const q = form.querySelector('#q');
  if (q) {
    let t;
    q.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(fetchResults, 300);
    });
  }
}

// ---------- Manipulation du DOM : génération des cartes ----------
function renderCards(items, grid) {
  if (!items.length) {
    grid.innerHTML = `
      <div class="empty" style="grid-column: 1 / -1;">
        <h3>Aucun logement ne correspond.</h3>
        <p>Essayez d'élargir vos critères de recherche.</p>
      </div>`;
    return;
  }

  grid.innerHTML = '';
  items.forEach(p => {
    const card = document.createElement('article');
    card.className = 'card';
    card.innerHTML = `
      <div class="card-img">
        <img src="images/${escapeHtml(p.image)}" alt="${escapeHtml(p.titre)}" loading="lazy">
        <div class="badges">
          <span class="badge">${escapeHtml(p.type)}</span>
          <span class="badge ${Number(p.disponible) ? 'green' : 'red'}">
            ${Number(p.disponible) ? 'Disponible' : 'Réservée'}
          </span>
        </div>
      </div>
      <div class="card-body">
        <h3>${escapeHtml(p.titre)}</h3>
        <div class="card-loc">📍 ${escapeHtml(p.quartier)}, ${escapeHtml(p.ville)}</div>
        <div class="card-meta">
          <span>📐 ${p.surface} m²</span>
          <span>🛏 ${p.pieces} pièces</span>
        </div>
        <div class="card-foot">
          <div class="price">${Number(p.prix).toLocaleString()}<small> TND/mois</small></div>
          <span class="see-more">Voir détails →</span>
        </div>
      </div>`;
    card.addEventListener('click', () => {
      window.location.href = 'html/details.php?id=' + p.id;
    });
    grid.appendChild(card);
  });
}

function escapeHtml(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => (
    { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
  ));
}

// ---------- Validation des formulaires (login / register) ----------
function initAuthValidation() {
  // ----- Inscription -----
  const reg = document.getElementById('registerForm');
  if (reg) {
    reg.addEventListener('submit', (e) => {
      let ok = true;
      const nom = reg.querySelector('#nom');
      const email = reg.querySelector('#email');
      const pwd = reg.querySelector('#password');
      const confirm = reg.querySelector('#confirm');

      ok &= setError(nom, nom.value.trim() === '' ? 'Le nom est obligatoire.' : '');
      ok &= setError(email,
        email.value.trim() === '' ? 'L\'email est obligatoire.'
        : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value) ? 'Format d\'email invalide.'
        : '');
      ok &= setError(pwd,
        pwd.value === '' ? 'Le mot de passe est obligatoire.'
        : pwd.value.length < 6 ? 'Au moins 6 caractères.'
        : '');
      ok &= setError(confirm,
        confirm.value !== pwd.value ? 'Les mots de passe ne correspondent pas.' : '');

      if (!ok) e.preventDefault();
    });

    // Vérification email AJAX (au blur)
    const email = reg.querySelector('#email');
    email.addEventListener('blur', () => {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) return;
      fetch('back/check_email.php?email=' + encodeURIComponent(email.value))
        .then(r => r.json())
        .then(d => {
          if (d.exists) setError(email, 'Cet email est déjà utilisé.');
        });
    });
  }

  // ----- Connexion -----
  const log = document.getElementById('loginForm');
  if (log) {
    log.addEventListener('submit', (e) => {
      let ok = true;
      const email = log.querySelector('#email');
      const pwd = log.querySelector('#password');
      ok &= setError(email,
        email.value.trim() === '' ? 'L\'email est obligatoire.'
        : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value) ? 'Format invalide.'
        : '');
      ok &= setError(pwd, pwd.value === '' ? 'Mot de passe obligatoire.' : '');
      if (!ok) e.preventDefault();
    });
  }
}

function setError(field, msg) {
  if (!field) return true;
  let span = field.parentElement.querySelector('.error-field');
  if (!span) {
    span = document.createElement('div');
    span.className = 'error-field';
    field.parentElement.appendChild(span);
  }
  span.textContent = msg;
  field.style.borderColor = msg ? 'var(--danger)' : 'var(--border)';
  return msg ? 0 : 1;
}

// ---------- Validation form admin (ajout / modif maison) ----------
function initAdminForm() {
  const form = document.getElementById('maisonForm');
  if (!form) return;
  form.addEventListener('submit', (e) => {
    let ok = true;
    const required = ['titre', 'ville', 'quartier', 'adresse', 'description'];
    required.forEach(id => {
      const f = form.querySelector('#' + id);
      ok &= setError(f, f.value.trim() === '' ? 'Champ obligatoire.' : '');
    });
    const prix = form.querySelector('#prix');
    ok &= setError(prix, !prix.value || Number(prix.value) <= 0 ? 'Prix invalide.' : '');
    const surface = form.querySelector('#surface');
    ok &= setError(surface, !surface.value || Number(surface.value) <= 0 ? 'Surface invalide.' : '');
    if (!ok) e.preventDefault();
  });
}

// ---------- Header sticky ----------
function initStickyHeader() {
  const header = document.querySelector('.site-header');
  if (!header || header.classList.contains('solid')) return;
  // On ne fait rien : le header reste transparent sur la home, solide sur les autres pages.
}
