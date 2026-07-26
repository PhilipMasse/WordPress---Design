/* Calendrier agenda Berre-les-Alpes v4 */
var berreCalAjaxUrl = (typeof BERRE_CAL !== 'undefined') ? BERRE_CAL.ajax : '';

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

function berreOpenPopup(btn) {
  var d     = btn.dataset;
  var popup = document.getElementById('berre-popup');
  if (!popup) return;

  /* Image */
  var imgEl = document.getElementById('bpp-img');
  if (imgEl) {
    if (d.img) {
      imgEl.innerHTML = '<img src="' + d.img + '" alt="">';
      imgEl.classList.add('has-img');
    } else {
      imgEl.innerHTML = '';
      imgEl.classList.remove('has-img');
    }
  }

  /* Catégorie */
  var catEl = document.getElementById('bpp-cats');
  if (catEl) { catEl.textContent = d.cats || ''; catEl.style.color = d.catColor || '#DEA128'; }

  /* Titre */
  var titleEl = document.getElementById('bpp-title');
  if (titleEl) titleEl.textContent = d.title || '';

  /* Date + lieu */
  var metaEl = document.getElementById('bpp-meta');
  var meta = '';
  if (d.start && metaEl) {
    try {
      var ds = new Date(d.start.replace(/-/g,'/')).toLocaleDateString('fr-FR',
        {weekday:'short',day:'numeric',month:'long',year:'numeric'});
      if (d.end && d.end !== d.start)
        ds += '\u00a0\u2013\u00a0' + new Date(d.end.replace(/-/g,'/')).toLocaleDateString('fr-FR',
          {day:'numeric',month:'long'});
      if (d.time) ds += ', ' + d.time;
      meta += '<div>\uD83D\uDCC5 ' + ds + '</div>';
    } catch(e) {}
    if (d.loc) meta += '<div>\uD83D\uDCCD ' + d.loc + '</div>';
    metaEl.innerHTML = meta;
  }

  /* Contenu (via berreCalContent injecté par PHP) */
  var excerptEl = document.getElementById('bpp-excerpt');
  if (excerptEl) {
    var map = (typeof berreCalContent !== 'undefined') ? berreCalContent : {};
    var pid = parseInt(d.id || 0);
    var html = pid && map[pid] ? map[pid] : '';
    if (html) { excerptEl.innerHTML = html; excerptEl.style.display = 'block'; }
    else       { excerptEl.innerHTML = ''; excerptEl.style.display = 'none'; }
  }

  /* Google Calendar — URL déjà construite en PHP */
  var gcalBtn = document.getElementById('bpp-gcal');
  if (gcalBtn) gcalBtn.href = d.gcal || '#';

  /* En savoir plus */
  var seeBtn = document.getElementById('bpp-btn');
  if (seeBtn) seeBtn.href = d.url || '#';

  popup.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function berreClosePopup() {
  var p = document.getElementById('berre-popup');
  if (p) p.style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') berreClosePopup();
});
