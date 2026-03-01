(function () {
  // ================= CLOCK =================
  function initClock() {
    function tick() {
      var el = document.getElementById('clock');
      if (!el) return;
      var d = new Date();
      var hh = String(d.getHours()).padStart(2, '0');
      var mm = String(d.getMinutes()).padStart(2, '0');
      el.textContent = hh + ':' + mm;
    }
    tick();
    setInterval(tick, 10000);
  }

  // ================= HELPERS =================
  function isFocusable(el) {
    return !!(el && el.matches && el.matches('a, button, [tabindex]:not([tabindex="-1"])') && !el.hasAttribute('disabled'));
  }

  function keyDir(e) {
    var k = e.key, c = e.keyCode;
    if (k === 'ArrowLeft'  || c === 21 || c === 37) return 'left';
    if (k === 'ArrowRight' || c === 22 || c === 39) return 'right';
    if (k === 'ArrowUp'    || c === 19 || c === 38) return 'up';
    if (k === 'ArrowDown'  || c === 20 || c === 40) return 'down';
    return null;
  }
  
  function isBackKey(e) { 
    var k = e.key, c = e.keyCode;
    return k === 'Escape' || k === 'Backspace' || k === 'BrowserBack' || c === 27 || c === 4 || c === 8 || c === 166 || c === 10009; 
  }
  
  function isEnterKey(e) {
    return e.key === 'Enter' || e.keyCode === 13 || e.keyCode === 23 || e.keyCode === 66;
  }

  // ================= NAVIGATION LOGIC (INDEX.PHP) =================
  function initIndexNav() {
    var grid = document.getElementById('grid');
    if (!grid) return null;

    function safeFocus(el) {
      if (!el) return;
      try { el.focus({ preventScroll: true }); } catch (_) { el.focus(); }
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    if (!window.location.hash && window.location.pathname.indexOf('index.php') !== -1) {
        setTimeout(function() {
          var first = document.querySelector('.tile');
          if (first) safeFocus(first);
        }, 100);
    }

    function panelOpen() { return document.querySelector('.panel[aria-hidden="false"]'); }

    function openPanel(id) {
      var p = document.getElementById(id);
      if (!p) return;
      p.setAttribute('aria-hidden', 'false');
      setTimeout(function() { 
        var firstItem = p.querySelector('.backbtn, .item, .bezky-row');
        if (firstItem) safeFocus(firstItem); 
      }, 50);
    }

    function closePanel(p) {
      if (!p) return;
      p.setAttribute('aria-hidden', 'true');
      var trigger = document.querySelector('[data-panel="' + p.id + '"]');
      if (trigger) safeFocus(trigger);
    }

    function handleKey(e) {
      var dir = keyDir(e), p = panelOpen();
      
      if (isBackKey(e)) { 
        if (p) { e.preventDefault(); closePanel(p); return true; }
        return false; 
      }
      
      if (isEnterKey(e) && !p) {
        var el = document.activeElement;
        if (el && el.dataset.panel) { e.preventDefault(); openPanel(el.dataset.panel); return true; }
      }
      
      if (!dir) return false;
      
      var scope = p || grid;
      var focusables = Array.from(scope.querySelectorAll('a, button, [tabindex]')).filter(isFocusable);
      var current = document.activeElement;
      
      if (!current || !focusables.includes(current)) { 
        e.preventDefault(); 
        safeFocus(focusables[0]); 
        return true; 
      }

      return false;
    }
    return { handleKey: handleKey };
  }
  
  // ================= NAVIGATION LOGIC (MANUALS / LYZE.PHP) =================
  function initManualNav() {
    var main = document.getElementById('manualMain') || document.querySelector('.manual-main');
    if (!main) return null;

    // Najdeme všechny sekce
    var sections = Array.from(main.querySelectorAll('section.manual-section'));
    sections.forEach(function(s) { 
        if (!s.hasAttribute('tabindex')) s.setAttribute('tabindex', '0'); 
    });

    // OKAMŽITÝ SKOK PŘI NAČTENÍ (pokud je v URL #bezky)
    function checkHashAndFocus() {
        if (window.location.hash) {
            var targetId = window.location.hash.substring(1);
            var target = document.getElementById(targetId);
            if (target) {
                target.focus();
                target.scrollIntoView({ behavior: 'auto', block: 'start' });
            }
        }
    }
    // Spustíme hned i s mírným zpožděním pro jistotu
    checkHashAndFocus();
    setTimeout(checkHashAndFocus, 150);

    function handleKey(e) {
      // Ignorovat, pokud jsme v elementu, který si navigaci řeší sám (např. detail)
      if (document.activeElement && document.activeElement.classList.contains('nav-ignore')) {
          return false; 
      }

      var dir = keyDir(e);
      if (!dir && !isBackKey(e)) return false;

      // Zpět na index
      if (isBackKey(e)) { 
        e.preventDefault(); 
        window.location.href = 'index.php' + window.location.search;
        return true; 
      }
      
      var current = document.activeElement;
      var currentIndex = sections.indexOf(current);

      // Pokud není fokus na žádné sekci (např. po načtení), najdeme tu správnou
      if (currentIndex === -1) {
          var target = null;
          if (window.location.hash) {
              target = document.querySelector(window.location.hash);
          }
          var startSection = (target && sections.includes(target)) ? target : sections[0];
          
          if (startSection) {
              e.preventDefault();
              startSection.focus();
              return true;
          }
          return false;
      }
        
      var nextIndex = currentIndex;
      if (dir === 'up') nextIndex--;
      else if (dir === 'down') nextIndex++;
      else return false;

      if (nextIndex >= 0 && nextIndex < sections.length) {
          e.preventDefault();
          // Dočasně vypneme snap, aby scrollIntoView fungovalo hladce
          main.style.scrollSnapType = 'none';
          sections[nextIndex].focus();
          sections[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'start' });
          
          // Vrátíme snap zpět po dokončení animace
          setTimeout(function() { 
              main.style.scrollSnapType = 'y proximity'; 
          }, 600);
          return true;
      }
      return false;
    }
        
    return { handleKey: handleKey };
  }

  // ================= INIT =================
  var indexNav, manualNav;
  
  // Použijeme DOMContentLoaded pro rychlejší start
  document.addEventListener('DOMContentLoaded', function () {
    initClock();
    indexNav = initIndexNav();
    manualNav = initManualNav();
  });

  document.addEventListener('keydown', function (e) {
    // Globální ignorování
    if (document.activeElement && document.activeElement.classList.contains('nav-ignore')) {
        return; 
    }

    // Refresh na nulu
    if (e.key === '0' || e.keyCode === 48) { 
        e.preventDefault(); 
        window.location.reload(true); 
        return; 
    }
    
    // Priorita navigace podle stránky
    if (indexNav && indexNav.handleKey(e)) return;
    if (manualNav && manualNav.handleKey(e)) return;

    // Globální BackKey
    if (isBackKey(e)) {
        if (window.location.href.indexOf('index.php') === -1) {
            e.preventDefault();
            window.location.href = 'index.php' + window.location.search;
        }
    }
  }, false);
  
})();