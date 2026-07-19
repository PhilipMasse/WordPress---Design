/* Berre-les-Alpes — main.js */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ══ NETTOYAGE ICÔNES ACCÈS RAPIDES ══
       Supprime les anciens conteneurs sans classe primary/secondary
       (laissés par le cache de template WordPress).
       ═════════════════════════════════════ */
    (function cleanupIcons() {

      /* 1. Supprimer TOUS les conteneurs sans primary/secondary
            (anciens wp:html hardcodés dans le cache) */
      document.querySelectorAll(
        '.berre-icons-row:not(.berre-icons-primary):not(.berre-icons-secondary)'
      ).forEach(function(r) { r.remove(); });

      /* 2. S'il reste plusieurs .berre-icons-primary (shortcode appelé 2x),
            garder uniquement le DERNIER — supprimer les précédents */
      var primaries = document.querySelectorAll('.berre-icons-primary');
      if (primaries.length > 1) {
        Array.from(primaries).slice(0, primaries.length - 1).forEach(function(r) {
          r.remove();
        });
      }

      /* 3. Pareil pour les secondary en double */
      var secondaries = document.querySelectorAll('.berre-icons-secondary');
      if (secondaries.length > 1) {
        Array.from(secondaries).slice(0, secondaries.length - 1).forEach(function(r) {
          r.remove();
        });
      }

      /* 4. Masquer les secondaires restants */
      document.querySelectorAll('.berre-icons-secondary').forEach(function(sec) {
        sec.style.cssText = 'display:none!important;visibility:hidden!important;';
        sec.setAttribute('aria-hidden', 'true');
      });

      /* 5. S'il y a plusieurs .berre-icons-block (shortcode appelé 2x depuis le cache),
            garder uniquement celui avec le data-berre-ts le plus élevé */
      var blocks = document.querySelectorAll('.berre-icons-block[data-berre-ts]');
      if (blocks.length > 1) {
        var maxTs = 0, bestBlock = null;
        blocks.forEach(function(b) {
          var ts = parseInt(b.getAttribute('data-berre-ts'), 10) || 0;
          if (ts > maxTs) { maxTs = ts; bestBlock = b; }
        });
        blocks.forEach(function(b) { if (b !== bestBlock) b.remove(); });
      }

      /* 6. Supprimer les berre-icon-btn orphelins (hors de toute .berre-icons-row) */
      document.querySelectorAll('.berre-icon-btn').forEach(function(btn) {
        if (!btn.closest('.berre-icons-row')) btn.remove();
      });

    })();


    /* ── Recherche ── */
    const searchBar = document.querySelector('.berre-search-bar input');
    if (searchBar) {
      document.querySelectorAll('#berre-search-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
          searchBar.focus();
          searchBar.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
      });
      const submitBtn = document.querySelector('.berre-search-btn');
      function doSearch() {
        const q = searchBar.value.trim();
        if (q) window.location.href = '/?s=' + encodeURIComponent(q);
      }
      if (submitBtn) submitBtn.addEventListener('click', doSearch);
      searchBar.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doSearch();
      });
    }

    /* ── Vidéo background commune ── */
    const communeVideo = document.querySelector('.berre-section-commune video');
    if (communeVideo && communeVideo.src) {
      communeVideo.play().catch(function () {
        communeVideo.style.display = 'none';
      });
    }

    /* ── Accès rapides — Voir plus / Voir moins ── */
    const seeMoreLink = document.querySelector('.berre-see-more__link');
    const secondary   = document.querySelector('.berre-icons-secondary');

    if (seeMoreLink && secondary) {
      /* Empêcher la navigation dès le chargement */
      seeMoreLink.setAttribute('href', '#');
      seeMoreLink.setAttribute('role', 'button');

      seeMoreLink.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const isOpen = secondary.classList.toggle('is-open');

        /* Texte du bouton */
        const dot = this.querySelector('.berre-see-more__dot');
        const label = this.querySelector('.berre-see-more__text');
        if (label) {
          label.textContent = isOpen
            ? "Voir moins d'accès rapides "
            : "Voir plus d'accès rapides ";
        }
        if (dot) {
          dot.textContent = isOpen ? '−' : '+';
        }

        /* Scroll doux vers les icônes secondaires si on ouvre */
        if (isOpen) {
          secondary.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      });
    }

    /* ── Agenda : couleur bordure selon catégorie ── */
    document.querySelectorAll('.berre-agenda-item').forEach(function (item) {
      const tag = item.querySelector('.berre-agenda-tag');
      if (!tag) return;
      const text = tag.textContent.toLowerCase();
      if (text.includes('culture') || text.includes('concert') || text.includes('musique')) {
        item.style.borderLeftColor = '#DEA128';
      } else if (text.includes('sport') || text.includes('rando') || text.includes('tourisme')) {
        item.style.borderLeftColor = '#587526';
      }
    });

  });
})();
