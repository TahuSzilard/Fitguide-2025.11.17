(function () {
  // =========================================================
  //  CONFIG
  // =========================================================
  const CART_COUNT_URL = (window.FG && window.FG.cartCountUrl) || null;
  const STORE_URL = window.STORE_URL || '/store';
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

  // =========================================================
  //  CART BADGE HELPERS
  // =========================================================
  const cartLink = document.querySelector('.nav-cart');

  function getBadgeEl() {
    return cartLink ? cartLink.querySelector('.cart-badge') : null;
  }
  function getBadgeCount() {
    const b = getBadgeEl();
    const n = b ? parseInt(b.textContent, 10) : 0;
    return Number.isFinite(n) ? n : 0;
  }
  function setBadgeCount(n) {
    if (!cartLink) return;
    let b = getBadgeEl();
    if (!b) {
      b = document.createElement('span');
      b.className = 'cart-badge';
      cartLink.appendChild(b);
    }
    b.textContent = Math.max(0, n);
    b.classList.remove('bump');
    void b.offsetWidth;
    b.classList.add('bump');
  }
  async function refreshCartBadge() {
    if (!CART_COUNT_URL) return;
    try {
      const res = await fetch(CART_COUNT_URL, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      });
      if (!res.ok) return;
      const data = await res.json();
      if (typeof data.count === 'number') setBadgeCount(data.count);
    } catch (_) { /* ignore */ }
  }

  // =========================================================
  //  AJAX FILTERING + PAGINATION (STORE OLDAL)
  // =========================================================
  function wireAjaxFiltering() {
    const list = document.getElementById('product-list');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const filterSelect = document.getElementById('product-filter');

    function setActiveBySlug(slug) {
      filterBtns.forEach(a => {
        const href = a.getAttribute('href') || '';
        const u = new URL(href, window.location.origin);
        const t = u.searchParams.get('type') || 'all';
        a.classList.toggle('active', t === slug);
      });
      if (filterSelect) filterSelect.value = slug;
    }

    function buildUrl(base, params) {
      const url = new URL(base, window.location.origin);
      Object.entries(params).forEach(([k, v]) => {
        if (v == null || v === '' || v === 'all') url.searchParams.delete(k);
        else url.searchParams.set(k, v);
      });
      return url.toString();
    }

    async function loadProducts(url) {
      if (!list) { window.location.href = url; return; }
      try {
        list.classList.add('loading');
        const ajaxUrl = (url.includes('?') ? url + '&' : url + '?') + 'ajax=1';
        const res = await fetch(ajaxUrl, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin'
        });
        if (!res.ok) throw new Error('Bad response');
        const html = await res.text();
        list.innerHTML = html;

        const cleanUrl = ajaxUrl.replace(/([?&])ajax=1(&|$)/, '$1').replace(/[?&]$/, '');
        window.history.pushState({}, '', cleanUrl);

        wirePagination();
        list.classList.remove('loading');
        window.scrollTo({ top: list.offsetTop - 40, behavior: 'smooth' });
      } catch (e) {
        console.error(e);
        list.classList.remove('loading');
        window.location.href = url;
      }
    }

    function wireFilterButtons() {
      filterBtns.forEach(a => {
        a.addEventListener('click', (e) => {
          e.preventDefault();
          const url = a.getAttribute('href');
          const u = new URL(url, window.location.origin);
          const type = u.searchParams.get('type') || 'all';
          setActiveBySlug(type);
          loadProducts(url);
        });
      });
    }

    function wireSelect() {
      if (!filterSelect) return;
      filterSelect.addEventListener('change', () => {
        const type = filterSelect.value;
        const url = buildUrl(STORE_URL, { type });
        setActiveBySlug(type);
        loadProducts(url);
      });
    }

    function wirePagination() {
      const listEl = document.getElementById('product-list');
      if (!listEl) return;
      listEl.querySelectorAll('.pagination a, .fitguide-pagination a').forEach(a => {
        a.addEventListener('click', (e) => {
          e.preventDefault();
          const url = a.getAttribute('href');
          loadProducts(url);
        });
      });
    }

    const currentType = new URL(window.location.href).searchParams.get('type') || 'all';
    setActiveBySlug(currentType);
    wireFilterButtons();
    wireSelect();
    wirePagination();
  }

  // =========================================================
  //  ADD TO CART (AJAX)
  // =========================================================
  let BADGE_VERSION = 0;

  document.addEventListener('submit', async (e) => {
    const form = e.target;
    if (!form.matches('.add-to-cart-form')) return;
    if (!window.fetch) return;

    e.preventDefault();

    const qtyInput = form.querySelector('input[name="qty"]');
    const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value || '1', 10)) : 1;

    const btn = form.querySelector('.add-btn') || form.querySelector('.btn.pill');
    if (btn) { btn.classList.add('adding'); btn.disabled = true; }

    const before = (function(){
      const b = document.querySelector('.nav-cart .cart-badge');
      return b ? parseInt(b.textContent || '0', 10) || 0 : 0;
    })();
    setBadgeCount(before + qty);

    const thisVersion = ++BADGE_VERSION;

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json'
        },
        body: new FormData(form),
        credentials: 'same-origin'
      });

      let data = null;
      try { data = await res.json(); } catch (_) {}

      if (!res.ok || !data || data.ok !== true || typeof data.count !== 'number') {
        throw new Error('Add to cart failed');
      }

      if (thisVersion === BADGE_VERSION) setBadgeCount(data.count);
    } catch (err) {
      setBadgeCount(before);
      console.error(err);
      alert('Sajnos nem sikerült a kosárhoz adni. Próbáld újra.');
    } finally {
      setTimeout(() => {
        if (btn) { btn.classList.remove('adding'); btn.disabled = false; }
      }, 180);
    }
  });

  // =========================================================
  //  CART PAGE: QTY HANDLING, OPTIMISTIC TOTALS
  // =========================================================
  const fmt = (n) => Number(n).toLocaleString('de-DE', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }) + ' €';
  

  function updateRowLineTotal(row){
    if(!row) return;
    const priceEl = row.querySelector('.line-total') || row.querySelector('[data-price]');
    const unitPrice = parseFloat(priceEl?.dataset.price || '0');
    const qty = parseInt(row.querySelector('.qty-input')?.value || '1', 10);
    if(priceEl) priceEl.textContent = fmt(unitPrice * qty);
  }

  function recalcCartTotals(){
    let subtotal = 0;
    document.querySelectorAll('.cart-row').forEach(row=>{
      const priceHolder = row.querySelector('.line-total') || row.querySelector('[data-price]');
      const unitPrice = parseFloat(priceHolder?.dataset.price || '0');
      const qty = parseInt(row.querySelector('.qty-input')?.value || '1', 10);
      subtotal += unitPrice * qty;
    });
    const shipEl = document.getElementById('cart-shipping');
    const shipping = shipEl ? parseFloat(shipEl.textContent.replace(/[^\d.]/g,'') || '0') : 0;
    const total = subtotal + shipping;

    const subEl = document.getElementById('cart-subtotal');
    const totEl = document.getElementById('cart-total');
    if(subEl) subEl.textContent = fmt(subtotal);
    if(totEl) totEl.textContent = fmt(total);
  }

  async function persistQty(form, qty){
    const url = form?.dataset?.updateUrl;
    if(!url) return;
    try{
      const fd = new FormData();
      fd.append('qty', qty);
      const res = await fetch(url, {
        method:'POST',
        headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
        body: fd,
        credentials:'same-origin'
      });
      const data = await res.json().catch(()=>null);
      if(data && typeof data==='object'){
        const row = form.closest('.cart-row');
        if(typeof data.line_total === 'number' && row){
          const lt = row.querySelector('.line-total');
          if(lt) lt.textContent = fmt(data.line_total);
        }
        if(typeof data.subtotal === 'number'){
          const subEl = document.getElementById('cart-subtotal');
          if(subEl) subEl.textContent = fmt(data.subtotal);
        }
        if(typeof data.shipping === 'number'){
          const shipEl = document.getElementById('cart-shipping');
          if(shipEl) shipEl.textContent = fmt(data.shipping);
        }
        if(typeof data.total === 'number'){
          const totEl = document.getElementById('cart-total');
          if(totEl) totEl.textContent = fmt(data.total);
        }
      }
    }catch(e){
      console.warn('cart.update error, keeping optimistic totals', e);
    }
  }

  function wireCartQty(){
    const table = document.querySelector('.cart-table');
    if(!table) return;

    table.addEventListener('click', (e)=>{
      const btn = e.target.closest('.qty-btn');
      if(!btn) return;
      const form = btn.closest('.qty-form');
      const input = form?.querySelector('.qty-input');
      const row = btn.closest('.cart-row');
      if(!input || !row) return;

      const min = parseInt(input.getAttribute('min') || '1', 10);
      let val = parseInt(input.value || '1', 10);
      if(btn.classList.contains('plus')) val += 1;
      if(btn.classList.contains('minus')) val = Math.max(min, val - 1);
      input.value = val;
      updateRowLineTotal(row);
      recalcCartTotals();
      persistQty(form, val);
    });

    table.addEventListener('input', (e)=>{
      const input = e.target.closest('.qty-input');
      if(!input) return;
      const form = input.closest('.qty-form');
      const row = input.closest('.cart-row');
      let val = Math.max(parseInt(input.getAttribute('min')||'1',10), parseInt(input.value||'1',10) || 1);
      input.value = val;
      updateRowLineTotal(row);
      recalcCartTotals();
      clearTimeout(input._t);
      input._t = setTimeout(()=> persistQty(form, val), 300);
    });

    recalcCartTotals();
  }

  // =========================================================
  //  HAMBURGER MENU
  // =========================================================
function wireHamburgerMenu() {
  const toggle  = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');   // csak EZT kezeljük
  if (!toggle || !navMenu) return;

  // ARIA az akadálymentességhez
  toggle.setAttribute('aria-expanded', 'false');

  toggle.addEventListener('click', () => {
    const isOpen = navMenu.classList.toggle('open');
    toggle.classList.toggle('active', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    toggle.innerHTML = isOpen
      ? '<i class="fa-solid fa-xmark"></i>'
      : '<i class="fa-solid fa-bars"></i>';
  });
}

  // =========================================================
  //  STARTUP
  // =========================================================
  document.addEventListener('DOMContentLoaded', () => {
    wireAjaxFiltering();
    refreshCartBadge();
    wireCartQty();
    wireHamburgerMenu(); // 🔥 ez eddig hiányzott
  });
})();
