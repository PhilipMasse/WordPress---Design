/* Berre-les-Alpes — main.js v2.5 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

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
