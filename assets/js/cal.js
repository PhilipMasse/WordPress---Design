/* Calendrier Berre-les-Alpes v6 */
var BCAL = {
  ajaxUrl : (typeof BERRE_CAL !== 'undefined') ? BERRE_CAL.ajax : '',
  restUrl : (typeof BERRE_CAL !== 'undefined' && BERRE_CAL.rest) ? BERRE_CAL.rest : '',
  popup   : null,

  buildPopup: function() {
    if (document.getElementById('berre-popup')) return;
    var el = document.createElement('div');
    el.id = 'berre-popup';
    el.innerHTML =
      '<div id="bpp-box">' +
        '<button id="bpp-close">&#215;</button>' +
        '<div id="bpp-img"></div>' +
        '<div id="bpp-body">' +
          '<p id="bpp-cats"></p>' +
          '<h3 id="bpp-title"></h3>' +
          '<div id="bpp-meta"></div>' +
          '<div id="bpp-content"></div>' +
          '<div id="bpp-btns">' +
            '<a id="bpp-btn" href="#">En savoir plus</a>' +
            '<a id="bpp-gcal" href="#" target="_blank" rel="noopener">' +
              '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">' +
                '<rect x="3" y="4" width="18" height="18" rx="2"/>' +
                '<line x1="16" y1="2" x2="16" y2="6"/>' +
                '<line x1="8" y1="2" x2="8" y2="6"/>' +
                '<line x1="3" y1="10" x2="21" y2="10"/>' +
              '</svg>' +
              'Ajouter\u00a0\u00e0 l\u2019agenda' +
            '</a>' +
          '</div>' +
        '</div>' +
      '</div>';
    document.body.appendChild(el);
    this.popup = el;
    el.addEventListener('click', function(e) { if (e.target === el) BCAL.close(); });
    document.getElementById('bpp-close').addEventListener('click', function() { BCAL.close(); });
  },

  open: function(btn) {
    this.buildPopup();
    var d = btn.dataset;

    /* Image */
    var imgEl = document.getElementById('bpp-img');
    if (d.img) {
      imgEl.innerHTML = '<img src="' + d.img + '" alt="">';
      imgEl.style.display = 'block';
    } else {
      imgEl.innerHTML = '';
      imgEl.style.display = 'none';
    }

    /* Catégorie */
    var catEl = document.getElementById('bpp-cats');
    catEl.textContent = d.cats || '';
    catEl.style.color = d.catColor || '#DEA128';

    /* Titre */
    document.getElementById('bpp-title').textContent = d.title || '';

    /* Date + lieu */
    var meta = '';
    if (d.start) {
      try {
        var ds = new Date(d.start.replace(/-/g,'/')).toLocaleDateString('fr-FR',
          {weekday:'short',day:'numeric',month:'long',year:'numeric'});
        if (d.end && d.end !== d.start)
          ds += '\u00a0\u2013\u00a0' + new Date(d.end.replace(/-/g,'/')).toLocaleDateString('fr-FR',
            {day:'numeric',month:'long'});
        if (d.time) ds += ', ' + d.time;
        meta += '<div style="display:flex;gap:5px"><span>\uD83D\uDCC5</span><span>' + ds + '</span></div>';
      } catch(e) {}
    }
    if (d.loc) meta += '<div style="display:flex;gap:5px"><span>\uD83D\uDCCD</span><span>' + d.loc + '</span></div>';
    document.getElementById('bpp-meta').innerHTML = meta;

    /* Contenu depuis data-content (base64 encodé en PHP) */
    var contentEl = document.getElementById('bpp-content');
    contentEl.innerHTML = '';
    contentEl.style.display = 'none';
    if (d.content) {
      try {
        /* Décoder base64 UTF-8 */
        var b64 = d.content;
        var html = decodeURIComponent(
          Array.prototype.map.call(atob(b64), function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
          }).join('')
        );
        var plain = html.replace(/<[^>]*>/g, '').trim();
        var title = (d.title || '').trim();
        if (plain && plain !== title && plain.length > 2) {
          contentEl.innerHTML = html;
          contentEl.style.display = 'block';
        }
      } catch(e) {}
    }

    /* Boutons */
    document.getElementById('bpp-btn').href  = d.url  || '#';
    document.getElementById('bpp-gcal').href = d.gcal || '#';

    /* Afficher la popup */
    this.popup.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  },

  close: function() {
    if (this.popup) this.popup.style.display = 'none';
    document.body.style.overflow = '';
  },

  nav: function(month) {
    var grid  = document.getElementById('berre-cal-grid');
    var label = document.getElementById('berre-cal-label');
    if (!grid) return;
    grid.style.opacity = '0.4';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', this.ajaxUrl + '?action=berre_cal_nav&month=' + month, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState !== 4) return;
      grid.style.opacity = '1';
      if (xhr.status !== 200) return;
      try {
        var d = JSON.parse(xhr.responseText);
        grid.innerHTML = d.html;
        if (label) label.textContent = d.label;
        var btns = document.querySelectorAll('#berre-cal .berre-cal__btn');
        if (btns[0]) btns[0].setAttribute('onclick', "BCAL.nav('" + d.prev + "')");
        if (btns[1]) btns[1].setAttribute('onclick', "BCAL.nav('" + d.next + "')");
      } catch(e) {}
    };
    xhr.send();
  }
};

function berreOpenPopup(btn) { BCAL.open(btn); }
function berreCalNav(month)  { BCAL.nav(month); }
function berreClosePopup()   { BCAL.close(); }

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') BCAL.close();
});
