(() => {
  const openBtn = document.querySelector('.ibt-search-trigger');
  const modal = document.getElementById('ibt-search');
  if (!openBtn || !modal) return;

  const closeBtn = modal.querySelector('.ibt-search-close');
  const input = modal.querySelector('input[type="search"], input[type="text"]');
  let lastFocus = null;

  function open(e){
    if (e) e.preventDefault();               // stop adding #ibt-search to the URL
    lastFocus = document.activeElement;
    document.body.classList.add('is-search-open');
    modal.removeAttribute('aria-hidden');
    setTimeout(() => input && input.focus(), 0);
  }
  function close(e){
    if (e) e.preventDefault();
    document.body.classList.remove('is-search-open');
    modal.setAttribute('aria-hidden','true');
    if (lastFocus) lastFocus.focus();
    // remove any lingering hash without a page jump
    if (location.hash === '#ibt-search') history.replaceState(null, '', location.pathname + location.search);
  }

  openBtn.addEventListener('click', open);
  if (closeBtn) closeBtn.addEventListener('click', close);

  // Click outside inner panel closes modal
  modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
  // ESC to close
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
})();
