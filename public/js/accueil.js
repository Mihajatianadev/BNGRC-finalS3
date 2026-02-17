(function () {
  const loader = document.getElementById('vitrineLoader');
  if (loader) {
    window.setTimeout(() => {
      loader.style.transition = 'opacity 220ms ease';
      loader.style.opacity = '0';
      window.setTimeout(() => {
        loader.style.display = 'none';
      }, 240);
    }, 320);
  }

  const revealEls = document.querySelectorAll('[data-reveal]');
  const io = new IntersectionObserver(
    (entries) => {
      for (const e of entries) {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          io.unobserve(e.target);
        }
      }
    },
    { threshold: 0.12 }
  );

  revealEls.forEach((el) => io.observe(el));

  const heroMedia = document.querySelector('.vitrine-hero-media');
  if (heroMedia) {
    let raf = 0;
    let mx = 0;
    let my = 0;

    function apply() {
      raf = 0;
      const y = window.scrollY || 0;
      const tY = Math.min(18, y * 0.04);
      const tX = (mx - 0.5) * 12;
      const tM = (my - 0.5) * 10;
      heroMedia.style.transform = `translate3d(${tX}px, ${tY + tM}px, 0) scale(1.04)`;
    }

    window.addEventListener('scroll', () => {
      if (!raf) raf = requestAnimationFrame(apply);
    });

    window.addEventListener('mousemove', (e) => {
      mx = e.clientX / window.innerWidth;
      my = e.clientY / window.innerHeight;
      if (!raf) raf = requestAnimationFrame(apply);
    });

    apply();
  }
})();
