/* navigace.js
 * - Android TV / klávesová navigace
 * - Index (grid + panely) + Manuál (sekce na celou výšku)
 * - Měří --topbar-h a hlavně --main-h (výška okna pro sekce)
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
  function isEnterKey(e){ return e.key === 'Enter' || e.keyCode === 13 || e.keyCode === 23 || e.keyCode === 66; } // DPAD_CENTER

  // ================= LAYOUT VARS: --topbar-h + --main-h =================
  function initLayoutVars() {
    var topbar = document.querySelector('.topbar');
    var main = document.getElementById('manualMain') || document.querySelector('.manual-main');

    function setVars() {
      // topbar
      if (topbar) {
        var th = Math.round(topbar.getBoundingClientRect().height);
        if (th > 0) document.documentElement.style.setProperty('--topbar-h', th + 'px');
      }

      // main viewport height (to je “okno”, na které se mají sekce přesně vejít)
      if (main) {
        var mh = Math.round(main.clientHeight);
        if (mh > 0) document.documentElement.style.setProperty('--main-h', mh + 'px');
      }
    }

    // hned + po layoutu
    setVars();
    requestAnimationFrame(function () { requestAnimationFrame(setVars); });

    // resize
    window.addEventListener('resize', setVars);

    // když se něco přelije / zalomí / změní (Android TV často)
    if (window.ResizeObserver) {
      try {
        var ro = new ResizeObserver(function () { setVars(); });
        if (topbar) ro.observe(topbar);
        if (main) ro.observe(main);
      } catch (_) {}
    }

    return { setVars: setVars };
  }

  // ================= INDEX (grid + panels) =================
  function initIndexNav() {
    var grid = document.getElementById('grid');
    var main = document.getElementById('main');
    if (!grid) return null; // nejsem na indexu

    function safeFocus(el) {
      if (!el) return;
      try { el.focus({ preventScroll: true }); } catch (_) { el.focus(); }
      ensureVisible(el);
    }

    function ensureVisible(el) {
      if (!el || !main) return;
      var r = el.getBoundingClientRect();
      var c = main.getBoundingClientRect();
      var pad = 16;

      if (r.top < c.top + pad) main.scrollTop -= (c.top + pad - r.top);
      else if (r.bottom > c.bottom - pad) main.scrollTop += (r.bottom - (c.bottom - pad));
    }

    function panelOpen() {
      return document.querySelector('.panel[aria-hidden="false"]');
    }

    function openPanel(panelId) {
      var p = document.getElementById(panelId);
      if (!p) return;
      p.setAttribute('aria-hidden', 'false');
      var first = p.querySelector('[data-close]') || p.querySelector('a,button,[tabindex]:not([tabindex="-1"])');
      safeFocus(first);
    }

    function closePanel(p) {
      if (!p) return;
      p.setAttribute('aria-hidden', 'true');
      var active = document.activeElement;
      if (!active || !active.classList || !active.classList.contains('tile')) {
        var firstTile = grid.querySelector('.tile');
        if (firstTile) safeFocus(firstTile);
      }
    }

    // klik pro myš (PC)
    document.addEventListener('click', function (e) {
      var tile = e.target.closest('a.tile[data-panel]');
      if (tile) {
        e.preventDefault();
        openPanel(tile.dataset.panel);
        return;
      }
      var closeBtn = e.target.closest('[data-close]');
      if (closeBtn) {
        var p = closeBtn.closest('.panel');
        if (p) closePanel(p);
      }
    });

    function getFocusables(scope) {
      if (!scope) return [];
      return Array.prototype.slice.call(
        scope.querySelectorAll('a.tile, a.item, .panel[aria-hidden="false"] a, .panel[aria-hidden="false"] button, .panel[aria-hidden="false"] [tabindex]:not([tabindex="-1"])')
      ).filter(isFocusable);
    }

    function center(rect) { return { x: rect.left + rect.width / 2, y: rect.top + rect.height / 2, w: rect.width, h: rect.height }; }

    function pickNext(current, candidates, dir) {
      var cr = center(current.getBoundingClientRect());
      var best = null, bestScore = Infinity;

      for (var i = 0; i < candidates.length; i++) {
        var el = candidates[i];
        if (el === current) continue;

        var er = center(el.getBoundingClientRect());
        var dx = er.x - cr.x;
        var dy = er.y - cr.y;

        if (dir === 'left'  && dx >= -1) continue;
        if (dir === 'right' && dx <=  1) continue;
        if (dir === 'up'    && dy >= -1) continue;
        if (dir === 'down'  && dy <=  1) continue;

        var adx = Math.abs(dx), ady = Math.abs(dy);
        var primary = (dir === 'left' || dir === 'right') ? adx : ady;
        var cross   = (dir === 'left' || dir === 'right') ? ady : adx;

        if (dir === 'left' || dir === 'right') {
          var rowTol = Math.max(18, cr.h * 0.55);
          if (ady > rowTol) cross *= 6;
        }
        if (dir === 'up' || dir === 'down') {
          var colTol = Math.max(18, cr.w * 0.55);
          if (adx > colTol) cross *= 2.5;
        }

        var score = primary + cross * 0.8;
        if (score < bestScore) { bestScore = score; best = el; }
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
          if (el && el.classList && el.classList.contains('tile') && el.dataset.panel) {
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
      if (next) { e.preventDefault(); safeFocus(next); return true; }

      return false;
    }

    window.addEventListener('load', function () {
      if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
      if (main) main.scrollTop = 0;
      var firstTile = grid.querySelector('.tile');
      if (firstTile) safeFocus(firstTile);
    });

    return { handleKey: handleKey };
  }

  // ================= MANUAL (sections) =================
  function initManualNav(layout) {
    var main = document.getElementById('manualMain') || document.querySelector('.manual-main');
    var backBtn = document.getElementById('backBtn');
    var sections = Array.prototype.slice.call(document.querySelectorAll('.manual-section'));
    if (!main && sections.length === 0 && !backBtn) return null;

    function scrollToSectionTop(el) {
      if (!el || !main) return;

      // nejstabilnější je měření vůči main
      var mainRect = main.getBoundingClientRect();
      var elRect = el.getBoundingClientRect();
      var top = main.scrollTop + (elRect.top - mainRect.top);

      main.scrollTop = top;
    }

    function focusEl(el) {
      if (!el) return;
      try { el.focus({ preventScroll: true }); } catch (_) { try { el.focus(); } catch (_) {} }
      scrollToSectionTop(el);
    }

    function focusHashTarget() {
      if (!location.hash) return;
      var id = location.hash.slice(1);
      if (!id) return;
      var el = document.getElementById(id);
      if (!el) return;
      focusEl(el);
    }

    function goBack() {
      if (history.length > 1) history.back();
      else goIndexFallback();
    }

    if (backBtn) backBtn.addEventListener('click', goBack);

    function indexOfSection(el) {
      for (var i = 0; i < sections.length; i++) if (sections[i] === el) return i;
      return -1;
    }

    function focusSectionByOffset(offset) {
      var current = document.activeElement;

      if (backBtn && current === backBtn && offset > 0) {
        if (sections[0]) focusEl(sections[0]);
        return;
      }

      var idxNow = indexOfSection(current);
      if (idxNow === -1) {
        if (offset < 0) { if (backBtn) focusEl(backBtn); }
        else { if (sections[0]) focusEl(sections[0]); }
        return;
      }

      var idxNext = idxNow + offset;

      if (idxNext < 0) {
        if (backBtn) focusEl(backBtn);
        return;
      }

      if (idxNext >= sections.length) {
        // poslední – už nikam
        return;
      }

      focusEl(sections[idxNext]);
    }

    function handleKey(e) {
      var dir = keyDir(e);

      if (isBackKey(e)) { e.preventDefault(); goBack(); return true; }
      if (dir === 'up')   { e.preventDefault(); focusSectionByOffset(-1); return true; }
      if (dir === 'down') { e.preventDefault(); focusSectionByOffset( 1); return true; }

      return false;
    }

    window.addEventListener('load', function () {
      if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

      // nejdřív doměř proměnné (hlavně --main-h), pak teprve fokus+scroll
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          if (layout && layout.setVars) layout.setVars();
          if (location.hash) focusHashTarget();
          else if (backBtn) focusEl(backBtn);
          else if (sections[0]) focusEl(sections[0]);
        });
      });
    });

    window.addEventListener('hashchange', function () {
      requestAnimationFrame(function () {
        if (layout && layout.setVars) layout.setVars();
        focusHashTarget();
      });
    });

    return { handleKey: handleKey };
  }

  // ================= INIT + ONE KEY HANDLER =================
  var indexNav = null;
  var manualNav = null;

  window.addEventListener('load', function () {
    initClock();
    var layout = initLayoutVars();
    indexNav = initIndexNav();
    manualNav = initManualNav(layout);
  });

  document.addEventListener('keydown', function (e) {
    if (indexNav && indexNav.handleKey(e)) return;
    if (manualNav && manualNav.handleKey(e)) return;
  }, true);

})();
