/**
 * FEATHER FITS — LOTTIE ANIMATED ICONS ENGINE
 * High-performance, 100% locally hosted Lottie animations for Dashboard & Storefront.
 * Interactive triggers: hover, click, loop, and auto-pulse.
 */
(function() {
  'use strict';

  const iconCache = {};
  const activeAnimations = new Map();

  // Helper to colorize SVG inside Lottie container
  function applyColorOverrides(container, primaryColor, secondaryColor) {
    if (!primaryColor) return;
    const svgs = container.querySelectorAll('svg path, svg circle, svg rect, svg polygon, svg line, svg polyline');
    svgs.forEach((el, idx) => {
      // If element has a stroke, apply primary or secondary color
      const hasStroke = el.getAttribute('stroke') && el.getAttribute('stroke') !== 'none';
      const hasFill = el.getAttribute('fill') && el.getAttribute('fill') !== 'none';

      if (hasStroke) {
        el.setAttribute('stroke', (idx % 2 === 0 || !secondaryColor) ? primaryColor : secondaryColor);
      }
      if (hasFill && el.getAttribute('fill') !== '#ffffff' && el.getAttribute('fill') !== 'white') {
        el.setAttribute('fill', (idx % 2 === 0 || !secondaryColor) ? primaryColor : secondaryColor);
      }
    });
  }

  function initLottieIcon(el) {
    if (el.dataset.lottieLoaded === 'true') return;
    const iconName = el.dataset.lottieIcon;
    if (!iconName) return;

    const trigger = el.dataset.lottieTrigger || 'hover';
    const primaryColor = el.dataset.lottiePrimary || el.dataset.lottieColor || null;
    const secondaryColor = el.dataset.lottieSecondary || null;
    const loop = trigger === 'loop';

    const jsonPath = 'assets/lottie/' + iconName + '.json';

    const loadAndRender = (animationData) => {
      try {
        // Clear fallback SVG once Lottie is ready, but keep container size
        const anim = window.bodymovin.loadAnimation({
          container: el,
          renderer: 'svg',
          loop: loop,
          autoplay: loop,
          animationData: animationData
        });

        activeAnimations.set(el, anim);
        el.dataset.lottieLoaded = 'true';

        // Hide any fallback SVG inside
        const fallbackSvg = el.querySelector('svg.fallback-svg');
        if (fallbackSvg) fallbackSvg.style.display = 'none';

        anim.addEventListener('DOMLoaded', () => {
          if (primaryColor) {
            applyColorOverrides(el, primaryColor, secondaryColor);
          }
        });

        // Setup interaction triggers
        if (trigger === 'hover') {
          // Listen on parent link/button if present for wider hit area
          const target = el.closest('a, button, .admin-kpi-card, .admin-nav-link') || el;
          target.addEventListener('mouseenter', () => {
            anim.stop();
            anim.play();
          });
        } else if (trigger === 'click') {
          el.addEventListener('click', () => {
            anim.stop();
            anim.play();
          });
        }
      } catch (err) {
        console.warn('Lottie render error for ' + iconName, err);
      }
    };

    if (iconCache[iconName]) {
      loadAndRender(iconCache[iconName]);
    } else {
      fetch(jsonPath)
        .then(res => {
          if (!res.ok) throw new Error('HTTP ' + res.status);
          return res.json();
        })
        .then(data => {
          iconCache[iconName] = data;
          loadAndRender(data);
        })
        .catch(err => {
          console.warn('Failed to load local Lottie icon: ' + iconName, err);
          // Keep fallback SVG visible
        });
    }
  }

  function initAllLottieIcons() {
    if (typeof window.bodymovin === 'undefined') {
      // If bodymovin is not yet loaded, wait and retry
      setTimeout(initAllLottieIcons, 50);
      return;
    }
    const elements = document.querySelectorAll('[data-lottie-icon]');
    elements.forEach(initLottieIcon);
  }

  // Public API
  window.FeatherLottie = {
    init: initAllLottieIcons,
    initElement: initLottieIcon,
    play: function(el) {
      const anim = activeAnimations.get(el);
      if (anim) {
        anim.stop();
        anim.play();
      }
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllLottieIcons);
  } else {
    initAllLottieIcons();
  }
})();
