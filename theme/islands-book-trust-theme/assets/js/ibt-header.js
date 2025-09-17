(() => {
  // ---- A) Accessible search modal (no hash) ----
  const modal   = document.getElementById('ibt-search');
  const trigger = document.querySelector('.ibt-search-trigger');
  if (modal && trigger) {
    const closeBtn = modal.querySelector('.ibt-search-close');
    const input    = modal.querySelector('input[type="search"], input[type="text"]');
    let lastFocus  = null;

    function open(e){ e && e.preventDefault();
      lastFocus = document.activeElement;
      document.body.classList.add('is-search-open');
      modal.removeAttribute('aria-hidden');
      setTimeout(() => input && input.focus(), 0);
    }
    function close(e){ e && e.preventDefault();
      document.body.classList.remove('is-search-open');
      modal.setAttribute('aria-hidden', 'true');
      if (lastFocus) lastFocus.focus();
    }

    trigger.addEventListener('click', open);
    closeBtn && closeBtn.addEventListener('click', close);
    modal.addEventListener('click', (e) => { if (e.target === modal) close(e); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(e); });
  }

  // ---- B) Dynamic body padding = actual header height ----
  const header = document.querySelector('.ibt-header-wrap');
  function syncPadding(){
    const adminBar = document.getElementById('wpadminbar');
    const adminH   = adminBar ? adminBar.offsetHeight : 0;
    const h        = header ? header.offsetHeight : 0;
    document.body.style.paddingTop = (h + adminH) + 'px';
  }
  if (header) {
    window.addEventListener('load', syncPadding, { once: true });
    window.addEventListener('resize', () => { requestAnimationFrame(syncPadding); });
  }
})();
