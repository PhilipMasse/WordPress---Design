/* Berre-les-Alpes — main.js v2.5 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {


    /* ── Header sticky : remonter au bon élément parent ── */
    (function() {
      var header = document.querySelector('.berre-header');
      if (!header) return;

      // Trouver l'ancêtre à rendre sticky (template part ou groupe WP)
      // position:sticky doit être sur un enfant DIRECT du conteneur de scroll
      var stickyEl = header;
      var parent = header.parentElement;
      // Remonter jusqu'à trouver un enfant direct de .wp-site-blocks ou body
      while (parent && parent !== document.body) {
        var gp = parent.parentElement;
        if (!gp) break;
        if (
          gp.classList.contains('wp-site-blocks') ||
          gp === document.body ||
          gp.tagName === 'BODY'
        ) {
          stickyEl = parent;
          break;
        }
        parent = gp;
      }

      // Appliquer sticky sur le bon élément
      stickyEl.style.position   = 'sticky';
      stickyEl.style.zIndex     = '9999';
      stickyEl.style.top        = '0px';

      // Ajuster pour la barre admin WordPress
      function setTop() {
        var adminBar = document.getElementById('wpadminbar');
        if (adminBar) {
          stickyEl.style.top = adminBar.offsetHeight + 'px';
        }
      }
      setTop();
      window.addEventListener('resize', setTop, { passive: true });

      // Ombre au scroll
      function onScroll() {
        header.classList.toggle('scrolled', window.scrollY > 10);
      }
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    })();

    /* ══ NETTOYAGE CACHE ══════════════════════════════════════════
       Supprime TOUS les anciens éléments avec les vieilles classes
       (.berre-icons-row, .berre-icons-block, .berre-icon-btn...)
       et garde uniquement les nouveaux blocs .berre-ar
       ══════════════════════════════════════════════════════════ */
    (function cleanOldCache() {
      // Supprimer tout ce qui utilise les ANCIENNES classes
      [
        '.berre-icons-row',
        '.berre-icons-block',
        '.berre-icon-btn'
      ].forEach(function(sel) {
        document.querySelectorAll(sel).forEach(function(el) {
          // Ne supprimer QUE si l'élément N'EST PAS à l'intérieur d'un .berre-ar
          if (!el.closest('.berre-ar')) el.remove();
        });
      });

      // S'il y a plusieurs .berre-ar (shortcode appelé 2x), garder le dernier
      var ars = document.querySelectorAll('[data-berre-ar="1"]');
      if (ars.length > 1) {
        Array.from(ars).slice(0, ars.length - 1).forEach(function(el) {
          el.remove();
        });
      }

      // Forcer le masquage des grilles secondaires
      document.querySelectorAll('.berre-ar__grid--secondary').forEach(function(el) {
        if (!el.classList.contains('open')) {
          el.style.setProperty('display', 'none', 'important');
        }
      });
    })();

    /* ══ BOUTON "VOIR PLUS" ═══════════════════════════════════════ */
    var btn = document.querySelector('.berre-see-more__btn');
    var sec = document.querySelector('.berre-ar__grid--secondary');

    if (btn && sec) {
      btn.addEventListener('click', function () {
        var open = sec.classList.toggle('open');
        if (open) {
          sec.style.removeProperty('display');
        } else {
          sec.style.setProperty('display', 'none', 'important');
        }
        btn.setAttribute('aria-expanded', open);
        var txt = btn.querySelector('.berre-see-more__text');
        var dot = btn.querySelector('.berre-see-more__dot');
        if (txt) txt.textContent = open
          ? 'Voir moins d\u2019acc\u00e8s rapides '
          : 'Voir plus d\u2019acc\u00e8s rapides ';
        if (dot) dot.textContent = open ? '\u2212' : '+';
      });
    }

    /* ══ RECHERCHE ════════════════════════════════════════════════ */
    var searchInput = document.querySelector('.berre-search-form input[type="search"]');
    if (searchInput) {
      searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          var q = this.value.trim();
          if (q) window.location.href = '/?s=' + encodeURIComponent(q);
        }
      });
    }

  });

})();


/* ── Recherche header — overlay plein écran au clic sur la loupe ── */
(function() {
  'use strict';

  // Créer l'overlay
  var overlay = document.createElement('div');
  overlay.id = 'berre-search-overlay';
  overlay.setAttribute('role', 'dialog');
  overlay.setAttribute('aria-label', 'Recherche');
  overlay.style.cssText = [
    'display:none',
    'position:fixed',
    'top:0','right:0','bottom:0','left:0',
    'z-index:99999',
    'background:rgba(16,33,66,.92)',
    'align-items:center',
    'justify-content:center',
    'box-sizing:border-box',
    'overflow:hidden'
  ].join(';');

  overlay.innerHTML =
    '<div style="width:calc(100vw - 32px);max-width:580px;box-sizing:border-box">' +
      '<p style="color:rgba(255,255,255,.5);font-size:11px;text-align:center;margin:0 0 10px;letter-spacing:.08em;text-transform:uppercase">Recherche</p>' +
      '<form id="bso-form" action="/" method="get"' +
           ' style="display:flex;background:#fff;border-radius:8px;overflow:hidden;' +
                   'box-shadow:0 12px 40px rgba(0,0,0,.4);width:100%;box-sizing:border-box">' +
        '<input type="search" name="s" id="bso-input" autocomplete="off"' +
               ' placeholder="Rechercher…"' +
               ' style="flex:1;min-width:0;border:none;outline:none;' +
                       'padding:15px 16px;font-size:16px;font-family:inherit;color:#111">' +
        '<button type="submit"' +
                ' style="flex-shrink:0;background:#2D6AB0;color:#fff;border:none;' +
                        'padding:0 18px;cursor:pointer;display:flex;align-items:center;' +
                        'justify-content:center;min-width:48px">' +
          '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="2.5">' +
            '<circle cx="11" cy="11" r="8"/>' +
            '<line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
          '</svg>' +
        '</button>' +
      '</form>' +
      '<button id="bso-close"' +
              ' style="margin-top:14px;background:none;border:none;' +
                      'color:rgba(255,255,255,.5);cursor:pointer;font-size:13px;' +
                      'display:block;width:100%;text-align:center;font-family:inherit">' +
        '✕ Fermer' +
      '</button>' +
    '</div>';

  document.body.appendChild(overlay);

  var input = document.getElementById('bso-input');
  var form  = document.getElementById('bso-form');
  var close = document.getElementById('bso-close');

  function openOverlay() {
    overlay.style.display = 'flex';
    setTimeout(function() { input && input.focus(); }, 50);
    document.body.style.overflow = 'hidden';
  }

  function closeOverlay() {
    overlay.style.display = 'none';
    document.body.style.overflow = '';
    if (input) input.value = '';
  }

  // Fermeture
  close.addEventListener('click', closeOverlay);
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) closeOverlay();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeOverlay();
  });

  // Soumission
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var q = (input.value || '').trim();
    if (q) window.location.href = '/?s=' + encodeURIComponent(q);
  });

  // Ouvrir au clic sur TOUS les boutons de recherche du header
  document.addEventListener('click', function(e) {
    var btn = e.target.closest(
      '.berre-hdr-search .wp-block-search__button, ' +
      '.berre-hdr-search button[type="submit"], ' +
      '.berre-hdr-search svg'
    );
    if (!btn) return;

    // Desktop : si l'input est visible et a du contenu → laisser le form naturel
    var hdrInput = document.querySelector('.berre-hdr-search input[type="search"]');
    if (hdrInput && hdrInput.offsetParent !== null && (hdrInput.value || '').trim()) {
      return; // submit normal
    }

    e.preventDefault();
    e.stopPropagation();
    openOverlay();
  });

  // Panneau blanc : soumettre au clic
  document.addEventListener('click', function(e) {
    var btn2 = e.target.closest('.berre-search-form button[type="submit"]');
    if (!btn2) return;
    var inp2 = btn2.closest('.berre-search-form') && btn2.closest('.berre-search-form').querySelector('input[type="search"]');
    if (inp2 && (inp2.value || '').trim()) {
      window.location.href = '/?s=' + encodeURIComponent(inp2.value.trim());
    }
    e.preventDefault();
  });

})();
