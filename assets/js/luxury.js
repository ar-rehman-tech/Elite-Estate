/* =============================================
   ELITE ESTATES — LUXURY GSAP ANIMATIONS
   ============================================= */

document.addEventListener('DOMContentLoaded', function() {

  // ---- CUSTOM CURSOR ----
  const dot = document.querySelector('.cursor-dot');
  const ring = document.querySelector('.cursor-ring');

  if (dot && ring) {
    let mouseX = 0, mouseY = 0;
    let ringX = 0, ringY = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      gsap.to(dot, { x: mouseX, y: mouseY, duration: 0.1 });
    });

    (function animateRing() {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      gsap.set(ring, { x: ringX, y: ringY });
      requestAnimationFrame(animateRing);
    })();

    document.querySelectorAll('a, button, .property-card, [data-hover]').forEach(el => {
      el.addEventListener('mouseenter', () => ring.classList.add('hover'));
      el.addEventListener('mouseleave', () => ring.classList.remove('hover'));
    });
  }

  // ---- LOADING SCREEN ----
  const overlay = document.querySelector('.loading-overlay');
  if (overlay) {
    // Hide overlay immediately if page was restored from bfcache (back/forward)
    overlay.style.display = 'flex';
    overlay.style.opacity = '1';

    const tl = gsap.timeline({
      onComplete: () => {
        gsap.to(overlay, {
          opacity: 0, duration: 0.8,
          onComplete: () => { overlay.style.display = 'none'; }
        });
      }
    });
    tl.to('.loading-logo', { opacity: 1, duration: 0.6, ease: 'power2.out' })
      .to('.loading-bar', { width: '100%', duration: 1.2, ease: 'power1.inOut' }, '-=0.2')
      .to('.loading-num', {
        textContent: '100',
        snap: { textContent: 1 },
        duration: 1.2,
        ease: 'power1.inOut'
      }, '<')
      .to(overlay, { delay: 0.3 });
  }

  // ---- FIX BLANK PAGE ON BACK BUTTON (bfcache restore) ----
  window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
      // Page restored from back/forward cache — hide any leftover curtain/overlay
      const curtains = document.querySelectorAll('[data-transition-curtain]');
      curtains.forEach(c => c.remove());
      const overlay = document.querySelector('.loading-overlay');
      if (overlay) {
        overlay.style.display = 'none';
        overlay.style.opacity = '0';
      }
      // Re-show body content
      document.body.style.visibility = 'visible';
      document.body.style.opacity = '1';
    }
  });

  // ---- NAVBAR SCROLL ----
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    ScrollTrigger.create({
      start: 'top -80',
      onUpdate: (self) => {
        if (self.progress > 0) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
      }
    });
  }

  // ---- HERO ENTRANCE ----
  const heroBadge = document.querySelector('.hero-badge');
  const heroH1 = document.querySelector('.hero h1');
  const heroSub = document.querySelector('.hero-sub');
  const heroActions = document.querySelector('.hero-actions');
  const scrollIndicator = document.querySelector('.scroll-indicator');

  if (heroH1) {
    const heroTl = gsap.timeline({ delay: overlay ? 2.2 : 0.3 });
    heroTl
      .to(heroBadge, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' })
      .to(heroH1, { opacity: 1, y: 0, duration: 1, ease: 'power3.out' }, '-=0.4')
      .to(heroSub, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.6')
      .to(heroActions, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
      .to(scrollIndicator, { opacity: 1, duration: 0.8 }, '-=0.3');

    const heroBg = document.querySelector('.hero-bg');
    if (heroBg) {
      gsap.to(heroBg, {
        y: '30%',
        ease: 'none',
        scrollTrigger: {
          trigger: '.hero',
          start: 'top top',
          end: 'bottom top',
          scrub: true
        }
      });
    }
  }

  // ---- STATS COUNTER ANIMATION ----
  document.querySelectorAll('.stat-number[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        gsap.to({ val: 0 }, {
          val: target,
          duration: 2,
          ease: 'power2.out',
          onUpdate: function() {
            el.textContent = Math.round(this.targets()[0].val).toLocaleString();
          }
        });
      }
    });
  });

  // ---- SCROLL REVEAL ----
  gsap.utils.toArray('.reveal').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0, y: 50,
      duration: 0.9,
      ease: 'power3.out',
      delay: (i % 3) * 0.1,
      scrollTrigger: {
        trigger: el, start: 'top 88%', once: true
      }
    });
  });

  gsap.utils.toArray('.reveal-left').forEach(el => {
    gsap.from(el, {
      opacity: 0, x: -50,
      duration: 0.9, ease: 'power3.out',
      scrollTrigger: { trigger: el, start: 'top 88%', once: true }
    });
  });

  // ---- PROPERTY CARDS STAGGER ----
  gsap.utils.toArray('.property-card').forEach((card, i) => {
    gsap.from(card, {
      opacity: 0, y: 60,
      duration: 0.8,
      delay: i * 0.1,
      ease: 'power3.out',
      scrollTrigger: { trigger: card, start: 'top 90%', once: true }
    });
  });

  // ---- SERVICE ITEMS STAGGER ----
  gsap.utils.toArray('.service-item').forEach((item, i) => {
    gsap.from(item, {
      opacity: 0, y: 40,
      duration: 0.7,
      delay: i * 0.08,
      ease: 'power3.out',
      scrollTrigger: { trigger: item, start: 'top 88%', once: true }
    });
  });

  // ---- TESTIMONIALS ----
  gsap.utils.toArray('.testimonial-card').forEach((card, i) => {
    gsap.from(card, {
      opacity: 0, scale: 0.95,
      duration: 0.7,
      delay: i * 0.12,
      ease: 'power2.out',
      scrollTrigger: { trigger: card, start: 'top 88%', once: true }
    });
  });

  // ---- PARALLAX BANNER ----
  const parallaxBg = document.querySelector('.parallax-banner-bg');
  if (parallaxBg) {
    gsap.to(parallaxBg, {
      y: 120,
      ease: 'none',
      scrollTrigger: {
        trigger: '.parallax-banner',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });
  }

  // ---- SECTION LABELS SLIDE IN ----
  gsap.utils.toArray('.section-label').forEach(el => {
    gsap.from(el, {
      opacity: 0, x: -20,
      duration: 0.6, ease: 'power2.out',
      scrollTrigger: { trigger: el, start: 'top 90%', once: true }
    });
  });

  // ---- GOLD DIVIDER EXPAND ----
  gsap.utils.toArray('.section-divider, .gold-line').forEach(el => {
    gsap.from(el, {
      scaleX: 0, transformOrigin: 'left',
      duration: 0.8, ease: 'power2.out',
      scrollTrigger: { trigger: el, start: 'top 90%', once: true }
    });
  });

  // ---- RESULT CARDS ----
  gsap.utils.toArray('.result-card').forEach((card, i) => {
    gsap.from(card, {
      opacity: 0, y: 40,
      duration: 0.7,
      delay: i * 0.08,
      ease: 'power3.out',
      scrollTrigger: { trigger: card, start: 'top 92%', once: true }
    });
  });

  // ---- ADMIN STAT CARDS ----
  gsap.utils.toArray('.admin-stat-card').forEach((card, i) => {
    gsap.from(card, {
      opacity: 0, y: 30,
      duration: 0.6,
      delay: i * 0.1,
      ease: 'power2.out',
      scrollTrigger: { trigger: card, start: 'top 92%', once: true }
    });
  });

  // ---- INQUIRY CARD FLOAT ----
  const inquiryCard = document.querySelector('.inquiry-card');
  if (inquiryCard) {
    gsap.to(inquiryCard, {
      y: -8,
      duration: 2.5,
      ease: 'sine.inOut',
      yoyo: true,
      repeat: -1
    });
  }

  // ---- SMOOTH HOVER ON PROPERTY CARDS ----
  document.querySelectorAll('.property-card').forEach(card => {
    const img = card.querySelector('.card-image');
    if (!img) return;
    card.addEventListener('mouseenter', () => {
      gsap.to(img, { scale: 1.07, duration: 0.8, ease: 'power2.out' });
    });
    card.addEventListener('mouseleave', () => {
      gsap.to(img, { scale: 1, duration: 0.8, ease: 'power2.out' });
    });
  });

  // ---- BUTTON MAGNETIC EFFECT ----
  document.querySelectorAll('.btn-luxury').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const r = btn.getBoundingClientRect();
      const x = e.clientX - r.left - r.width / 2;
      const y = e.clientY - r.top - r.height / 2;
      gsap.to(btn, { x: x * 0.25, y: y * 0.2, duration: 0.4, ease: 'power2.out' });
    });
    btn.addEventListener('mouseleave', () => {
      gsap.to(btn, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
    });
  });

  // ---- PAGE TRANSITION (FIXED — no blank page on back) ----
  document.querySelectorAll('a[href]').forEach(link => {
    const href = link.getAttribute('href');
    if (!href) return;
    // Skip hash links, external links, target="_blank", and non-http links
    if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    if (link.getAttribute('target') === '_blank') return;
    try {
      const url = new URL(href, window.location.origin);
      if (url.origin !== window.location.origin) return;
    } catch(e) { return; }

    link.addEventListener('click', (e) => {
      const dest = link.href;
      e.preventDefault();
      const curtain = document.createElement('div');
      curtain.setAttribute('data-transition-curtain', '1');
      curtain.style.cssText = `
        position:fixed;inset:0;background:var(--midnight);
        z-index:9999;transform:translateY(100%);
      `;
      document.body.appendChild(curtain);
      gsap.to(curtain, {
        translateY: '0%', duration: 0.5, ease: 'power3.in',
        onComplete: () => { window.location.href = dest; }
      });
    });
  });

  // ---- FORM FIELD FOCUS ANIMATION ----
  document.querySelectorAll('.form-group input, .form-group textarea, .form-group select').forEach(input => {
    const label = input.previousElementSibling;
    input.addEventListener('focus', () => {
      if (label) gsap.to(label, { color: 'var(--platinum)', duration: 0.3 });
      gsap.to(input, { borderColor: 'rgba(184,196,212,0.8)', duration: 0.3 });
    });
    input.addEventListener('blur', () => {
      if (label) gsap.to(label, { color: 'var(--platinum)', duration: 0.3 });
      gsap.to(input, { borderColor: 'var(--border)', duration: 0.3 });
    });
  });

  // ---- FAVORITES HEART ANIMATION ----
  document.querySelectorAll('.fav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const heart = this.querySelector('.heart-icon');
      if (heart) {
        gsap.to(heart, { scale: 1.4, duration: 0.15, ease: 'power1.out', yoyo: true, repeat: 1 });
      }
    });
  });

  // REFRESH SCROLL TRIGGERS
  ScrollTrigger.refresh();
});

// ---- SHARE BUTTON WITH FALLBACK + TOAST ----
function showToast(msg) {
  const t = document.createElement('div');
  t.textContent = msg;
  t.style.cssText = 'position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);background:var(--panel);border:1px solid var(--border-bright);color:var(--platinum-bright);padding:.8rem 1.8rem;font-size:.75rem;letter-spacing:.15em;text-transform:uppercase;z-index:9999;opacity:0;font-family:"DM Sans",sans-serif;';
  document.body.appendChild(t);
  gsap.to(t, {opacity:1, y:-10, duration:0.4, ease:'power2.out'});
  setTimeout(() => {
    gsap.to(t, {opacity:0, duration:0.4, onComplete:()=>t.remove()});
  }, 2800);
}

window.shareProperty = function(title, url) {
  if (navigator.share) {
    navigator.share({ title: title, url: url }).catch(() => {});
  } else if (navigator.clipboard) {
    navigator.clipboard.writeText(url).then(() => showToast('Link copied to clipboard'));
  } else {
    const ta = document.createElement('textarea');
    ta.value = url;
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    ta.remove();
    showToast('Link copied to clipboard');
  }
};
