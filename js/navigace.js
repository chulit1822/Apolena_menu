/* navigace.js
 * - Android TV / klávesová navigace
 * - Index (grid + panely) + Manuál (sekce na celou výšku)
 */
(function () {
  // ================= CLOCK =================
  function initClock() {
    function tick() {
      var el = document.getElementById('clock');
      if (!el) return;
      var d = new Date();
      var hh = String(d.getHours()); if (hh.length < 2) hh = '0' + hh;
      var mm = String(d.getMinutes()); if (mm.length < 2) mm = '0' + mm;
      el.textContent = hh + ':' + mm;
    }
    tick();
    setInterval(tick, 1000 * 10);
  }

  // ================= COMMON HELPERS =================
  function isFocusable(el) {
    return !!(el && el.matches && el.matches('a[href], button, [tabindex]:not([tabindex="-1"])') && !el.hasAttribute('disabled'));
  }

  function getLangFromUrl() {
    try {
      var params = new URLSearchParams(window.location.search);
      return params.get('lang') || 'cs';
    } catch (e) {
      var m = window.location.search.match(/[?&]lang=([^&]+)/);
      return m ? decodeURIComponent(m[1]) : 'cs';
    }
  }

  function goIndexFallback() {
    var lang = getLangFromUrl();
    window.location.href = 'index.php?lang=' + encodeURIComponent(lang);
  }

  // Android TV mapping
  function keyDir(e) {
    var k = e.key, c = e.keyCode;
    if (k === 'ArrowLeft'  || c === 21) return 'left';
    if (k === 'ArrowRight' || c === 22) return 'right';
    if (k === 'ArrowUp'    || c === 19 || c === 38) return 'up';
    if (k === 'ArrowDown'  || c === 20 || c === 40) return 'down';
    return null;
  }
  
  function isBackKey(e) { return e.key === 'Escape' || e.keyCode === 27 || e.keyCode === 4; }
  function isEnterKey(e){ return e.key === 'Enter' || e.keyCode === 13 || e.keyCode === 23 || e.keyCode === 66; }

  // ================= LAYOUT VARS =================
  function initLayoutVars() {
    var topbar = document.querySelector('.topbar');
    var main = document.getElementById('manualMain') || document.querySelector('.manual-main');

    function setVars() {
      if (topbar) {
        var th = Math.round(topbar.getBoundingClientRect().height);
        if (th > 0) document.documentElement.style.setProperty('--topbar-h', th + 'px');
      }
      if (main) {
        var mh = Math.round(main.clientHeight);
        if (mh > 0) document.documentElement.style.setProperty('--main-h', mh + 'px');
      }
    }

    setVars();
    window.addEventListener('resize', setVars);
    return { setVars: setVars };
  }

  // ================= INDEX (grid + panels) =================
  function initIndexNav() {
    var grid = document.getElementById('grid');
    if (!grid) return null;

    function safeFocus(el) {
      if (!el) return;
      
      // 1. Nastavíme focus, ale zakážeme ošklivý trhavý skok prohlížeče
      try { el.focus({ preventScroll: true }); } catch (_) { el.focus(); }
      
      // 2. Plynule odrolujeme tak, aby byl prvek uprostřed obrazovky
      if (typeof el.scrollIntoView === 'function') {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
    
    // Inicializační focus na první dlaždici po načtení
    setTimeout(function() {
      var firstTile = document.querySelector('.tile');
      if (firstTile) safeFocus(firstTile);
    }, 100);

    function panelOpen() {
      return document.querySelector('.panel[aria-hidden="false"]');
    }

    function openPanel(panelId) {
      var p = document.getElementById(panelId);
      if (!p) return;
      p.setAttribute('aria-hidden', 'false');
      var first = p.querySelector('.item.focusable') || p.querySelector('[data-close]');
      setTimeout(function() { safeFocus(first); }, 50);
    }

    function closePanel(p) {
      if (!p) return;
      p.setAttribute('aria-hidden', 'true');
      var tileId = p.id.replace('panel-', '');
      var trigger = document.querySelector('[data-panel="panel-' + tileId + '"]');
      if (trigger) safeFocus(trigger);
    }

    document.addEventListener('click', function(e) {
      var closeBtn = e.target.closest('[data-close]');
      if (closeBtn) {
        var p = closeBtn.closest('.panel');
        if (p) closePanel(p);
      }
    });

    function getFocusables(scope) {
      return Array.prototype.slice.call(
        scope.querySelectorAll('a.tile, a.item, button[data-close], [tabindex]:not([tabindex="-1"])')
      ).filter(isFocusable);
    }

    function pickNext(current, candidates, dir) {
      var cr = current.getBoundingClientRect();
      var best = null, bestScore = Infinity;
      var tolerance = 10; 
      
      for (var i = 0; i < candidates.length; i++) {
        var el = candidates[i];
        if (el === current) continue;
        
        var er = el.getBoundingClientRect();
        
        // Středy pro určení čistého směru
        var cx = cr.left + cr.width / 2;
        var ex = er.left + er.width / 2;
        
        // Určení, zda sdílí stejný řádek (overlapV) nebo stejný sloupec (overlapH)
        var overlapV = !(er.bottom - tolerance <= cr.top || er.top + tolerance >= cr.bottom);
        var overlapH = !(er.right - tolerance <= cr.left || er.left + tolerance >= cr.right);
        
        var valid = false;
        var score = 0;

        // Pohyb DOPRAVA/DOLEVA musí sdílet řádek!
        if (dir === 'right' && ex > cx && overlapV) {
            valid = true;
            score = er.left - cr.right;
        } 
        else if (dir === 'left' && ex < cx && overlapV) {
            valid = true;
            score = cr.left - er.right;
        } 
        // Pohyb DOLŮ/NAHORU ignoruje řádky, hlídá vertikální polohu
        else if (dir === 'down' && er.top >= cr.top + (cr.height / 2)) {
            valid = true;
            var penalty = overlapH ? 0 : 5000; // Drastická výhoda pro prvky pod sebou
            score = (er.top - cr.bottom) + Math.abs(ex - cx) + penalty;
        } 
        else if (dir === 'up' && er.bottom <= cr.top + (cr.height / 2)) {
            valid = true;
            var penalty = overlapH ? 0 : 5000;
            score = (cr.top - er.bottom) + Math.abs(ex - cx) + penalty;
        }

        if (valid && score < bestScore) {
          bestScore = score;
          best = el;
        }
      }
      return best;
    }
    
    function handleKey(e) {
      var dir = keyDir(e);
      var p = panelOpen();

      if (isBackKey(e)) {
        if (p) { e.preventDefault(); closePanel(p); return true; }
        return false;
      }

      if (isEnterKey(e)) {
        if (!p) {
          var el = document.activeElement;
          if (el && el.dataset.panel) {
            e.preventDefault();
            openPanel(el.dataset.panel);
            return true;
          }
        }
        return false; 
      }

      if (!dir) return false;
      
      var scope = p ? p : grid;
      var focusables = getFocusables(scope);
      var current = document.activeElement;

      if (!current || focusables.indexOf(current) === -1) {
        if (focusables[0]) { e.preventDefault(); safeFocus(focusables[0]); return true; }
        return false;
      }

      var next = pickNext(current, focusables, dir);
      
      // ZDE JE KLÍČOVÁ ZMĚNA
      // I když se prvek nenajde (jsme na konci řádku u span-2), natvrdo zablokujeme 
      // prohlížeč, aby si neudělal onen divoký skok jinam.
      e.preventDefault(); 
      
      if (next) { 
          safeFocus(next); 
      }
      return true;
    }

    return { handleKey: handleKey };
  }

  // ================= MANUAL =================
  function initManualNav() {
    function goBack() {
      var isIndex = window.location.pathname.endsWith('index.php') || window.location.pathname === '/';
      if (!isIndex && history.length > 1) {
        history.back();
      } else if (!isIndex) {
        goIndexFallback();
      }
    }
    
    function handleKey(e) {
      if (isBackKey(e)) { e.preventDefault(); goBack(); return true; }
      return false;
    }
    return { handleKey: handleKey };
  }

  // ================= INIT =================
  var indexNav = null;
  var manualNav = null;

  window.addEventListener('load', function () {
    initClock();
    initLayoutVars();
    indexNav = initIndexNav();
    manualNav = initManualNav();
  });

  document.addEventListener('keydown', function (e) {
    if (indexNav && indexNav.handleKey(e)) return;
    if (manualNav && manualNav.handleKey(e)) return;
  }, true);
})();