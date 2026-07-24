/* Calendrier agenda Berre-les-Alpes */
/* BERRE_CAL.ajax est défini via wp_localize_script */

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
      /* Mettre à jour onclick des boutons nav */
      var btns = document.querySelectorAll('#berre-cal .berre-cal__btn');
      if (btns[0]) btns[0].setAttribute('onclick', "berreCalNav('" + d.prev + "')");
      if (btns[1]) btns[1].setAttribute('onclick', "berreCalNav('" + d.next + "')");
    } catch(e) { }
  };
  xhr.send();
}

function berreOpenPopup(btn) {
  var d     = btn.dataset;
  var popup = document.getElementById('berre-popup');
  if (!popup) return;

  /* Image */
  document.getElementById('bpp-img').innerHTML = d.img
    ? '<img src="' + d.img + '" style="width:100%;height:160px;object-fit:cover;display:block" alt="">'
    : '';

  /* Catégorie, titre */
  document.getElementById('bpp-cats').textContent  = d.cats  || '';
  document.getElementById('bpp-title').textContent = d.title || '';

  /* Méta date + lieu */
  var meta = '';
  if (d.start) {
    try {
      var opts = { weekday:'short', day:'numeric', month:'long', year:'numeric' };
      var ds = new Date(d.start.replace(/-/g, '/')).toLocaleDateString('fr-FR', opts);
      if (d.end && d.end !== d.start)
        ds += ' \u2013 ' + new Date(d.end.replace(/-/g, '/')).toLocaleDateString('fr-FR', { day:'numeric', month:'long' });
      if (d.time) ds += ', ' + d.time;
      meta += '<div>\uD83D\uDCC5 ' + ds + '</div>';
    } catch(e) {
      meta += '<div>' + (d.start || '') + '</div>';
    }
  }
  if (d.loc) meta += '<div>\uD83D\uDCCD ' + d.loc + '</div>';
  document.getElementById('bpp-meta').innerHTML = meta;
  document.getElementById('bpp-btn').href = d.url || '#';

  /* Afficher */
  popup.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function berreClosePopup() {
  var popup = document.getElementById('berre-popup');
  if (popup) popup.style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') berreClosePopup();
});
