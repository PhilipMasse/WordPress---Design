/* Calendrier agenda Berre-les-Alpes v3 */
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
      imgEl.innerHTML = '<img src="' + d.img + '" style="width:100%;height:150px;object-fit:cover;display:block" alt="">';
      imgEl.style.cssText = 'display:block;line-height:0';
    } else {
      imgEl.innerHTML = '';
      imgEl.style.cssText = 'display:none;height:0;overflow:hidden';
    }
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
        ds += ' \u2013 ' + new Date(d.end.replace(/-/g,'/')).toLocaleDateString('fr-FR',
          {day:'numeric',month:'long'});
      if (d.time) ds += ', ' + d.time;
      meta += '<div style="display:flex;gap:5px;align-items:flex-start"><span>\uD83D\uDCC5</span><span>' + ds + '</span></div>';
    } catch(e) {}
  }
  if (d.loc) meta += '<div style="display:flex;gap:5px;align-items:flex-start"><span>\uD83D\uDCCD</span><span>' + d.loc + '</span></div>';
  document.getElementById('bpp-meta').innerHTML = meta;

  /* Contenu de la page (via berreCalContent injecté par PHP) */
  var excerptEl = document.getElementById('bpp-excerpt');
  if (excerptEl) {
    var map = (typeof berreCalContent !== 'undefined') ? berreCalContent : {};
    var postId = parseInt(d.id || 0);
    var html = postId && map[postId] ? map[postId] : '';
    if (html) {
      excerptEl.innerHTML = html;
      excerptEl.style.display = 'block';
    } else {
      excerptEl.innerHTML = '';
      excerptEl.style.display = 'none';
    }
  }

  /* Google Calendar */
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

  document.getElementById('bpp-btn').href = d.url || '#';
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
