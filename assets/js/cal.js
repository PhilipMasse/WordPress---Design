/* Calendrier agenda Berre-les-Alpes */
var berreCalAjaxUrl = (typeof BERRE_CAL !== 'undefined') ? BERRE_CAL.ajax : '';
var berreCalRestUrl = (typeof BERRE_CAL !== 'undefined') ? BERRE_CAL.rest : '';

/* ── Navigation AJAX ── */
function berreCalNav(month) {
  var grid  = document.getElementById('berre-cal-grid');
  var label = document.getElementById('berre-cal-label');
  if (!grid) return;
  grid.style.opacity = '0.4';
  var xhr = new XMLHttpRequest();
  xhr.open('GET', berreCalAjaxUrl + '?action=berre_cal_nav&month=' + month, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState !== 4) return;
    grid.style.opacity = '1';
    if (xhr.status !== 200) return;
    try {
      var d = JSON.parse(xhr.responseText);
      grid.innerHTML = d.html;
      if (label) label.textContent = d.label;
      var btns = document.querySelectorAll('#berre-cal .berre-cal__btn');
      if (btns[0]) btns[0].setAttribute('onclick', "berreCalNav('" + d.prev + "')");
      if (btns[1]) btns[1].setAttribute('onclick', "berreCalNav('" + d.next + "')");
    } catch(e) {}
  };
  xhr.send();
}

/* ── Ouvrir popup ── */
function berreOpenPopup(btn) {
  var d     = btn.dataset;
  var popup = document.getElementById('berre-popup');
  if (!popup) return;

  /* Image */
  var imgEl = document.getElementById('bpp-img');
  if (d.img) {
    imgEl.innerHTML = '<img src="' + d.img + '" style="width:100%;height:150px;object-fit:cover;display:block" alt="">';
    imgEl.style.display = 'block';
  } else {
    imgEl.innerHTML = '';
    imgEl.style.display = 'none';
  }

  /* Catégorie colorée */
  var catEl = document.getElementById('bpp-cats');
  catEl.textContent = d.cats || '';
  catEl.style.color = d.catColor || '#DEA128';

  /* Titre */
  document.getElementById('bpp-title').textContent = d.title || '';

  /* Méta : date + lieu */
  var meta = '';
  if (d.start) {
    try {
      var opts = { weekday:'short', day:'numeric', month:'long', year:'numeric' };
      var ds = new Date(d.start.replace(/-/g,'/')).toLocaleDateString('fr-FR', opts);
      if (d.end && d.end !== d.start)
        ds += '\u00a0\u2013\u00a0' + new Date(d.end.replace(/-/g,'/')).toLocaleDateString('fr-FR',{day:'numeric',month:'long'});
      if (d.time) ds += ', ' + d.time;
      meta += '<div style="display:flex;align-items:flex-start;gap:5px">'
            + '<span style="flex-shrink:0">\uD83D\uDCC5</span><span>' + ds + '</span></div>';
    } catch(e) { meta += '<div>' + (d.start||'') + '</div>'; }
  }
  if (d.loc) meta += '<div style="display:flex;align-items:flex-start;gap:5px">'
                   + '<span style="flex-shrink:0">\uD83D\uDCCD</span><span>' + d.loc + '</span></div>';
  document.getElementById('bpp-meta').innerHTML = meta;

  /* Contenu — masquer en attendant le fetch */
  var excerptEl = document.getElementById('bpp-excerpt');
  excerptEl.style.display = 'none';
  excerptEl.innerHTML = '';

  /* Bouton agenda Google Calendar */
  var gcalBtn = document.getElementById('bpp-gcal');
  if (gcalBtn && d.start) {
    var gs = d.start.replace(/-/g,'');
    var ge = d.end ? d.end.replace(/-/g,'') : gs;
    gcalBtn.href = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
      + '&text=' + encodeURIComponent(d.title||'')
      + '&dates=' + gs + '/' + ge
      + (d.loc ? '&location=' + encodeURIComponent(d.loc) : '')
      + (d.url ? '&details='  + encodeURIComponent(d.url) : '');
    gcalBtn.style.display = 'inline-flex';
  }

  /* Lien "En savoir plus" */
  document.getElementById('bpp-btn').href = d.url || '#';

  /* Afficher popup */
  popup.style.display = 'flex';
  document.body.style.overflow = 'hidden';

  /* Fetch contenu formaté via REST API (async) */
  if (berreCalRestUrl && d.id) {
    var xhrC = new XMLHttpRequest();
    xhrC.open('GET', berreCalRestUrl + '/' + d.id + '?_fields=content', true);
    xhrC.onreadystatechange = function() {
      if (xhrC.readyState !== 4 || xhrC.status !== 200) return;
      try {
        var r = JSON.parse(xhrC.responseText);
        var html = (r.content && r.content.rendered) ? r.content.rendered.trim() : '';
        if (html && html !== '<p></p>') {
          excerptEl.innerHTML = html;
          excerptEl.style.display = 'block';
        }
      } catch(e) {}
    };
    xhrC.send();
  }
}

/* ── Fermer popup ── */
function berreClosePopup() {
  var popup = document.getElementById('berre-popup');
  if (popup) popup.style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') berreClosePopup();
});
