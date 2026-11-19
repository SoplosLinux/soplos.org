/**
 * Main Script for Soplos.org
 * Initializes UI components and handles global logic.
 */

document.addEventListener("DOMContentLoaded", function () {
  // 1. Initialize Scroll Controller (Header, Back to Top, Smooth Scroll)
  new SoplosUI.ScrollController();
  new SoplosUI.BreadcrumbController();

  // 2. Initialize Mobile Carousels
  // Features Carousel
  new SoplosUI.SoplosCarousel(".features-grid", ".feature-card", {
    prevClass: "features-arrow prev",
    nextClass: "features-arrow next",
    dotsClass: "features-dots",
    dotClass: "features-dot",
    minHeight: "300px",
    itemWidth: "100%",
  });

  // Galleries Carousel (Screenshots)
  new SoplosUI.SoplosCarousel(".screenshot-gallery", ".screenshot", {
    prevClass: "gallery-arrow prev",
    nextClass: "gallery-arrow next",
    dotsClass: "gallery-dots",
    dotClass: "gallery-dot",
  });

  // Requirements Carousel
  new SoplosUI.SoplosCarousel(
    "#requirements .requirements-cards",
    ".requirements-card",
    {
      prevClass: "requirements-arrow prev",
      nextClass: "requirements-arrow next",
      dotsClass: "requirements-dots",
      dotClass: "requirements-dot",
    }
  );

  // 3. Initialize Modals

  // Requirements Modals
  const reqModals = [
    { btnId: "requirementsTyronBtn", modalId: "modalRequirementsTyron" },
    { btnId: "requirementsTysonBtn", modalId: "modalRequirementsTyson" },
    { btnId: "requirementsBoroBtn", modalId: "modalRequirementsBoro" },
  ];

  reqModals.forEach((req) => {
    const btn = document.getElementById(req.btnId);
    if (btn) {
      const modal = new SoplosUI.SoplosModal(req.modalId, {
        closeSelector: ".close-modal-requirements",
      });
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        modal.open(btn);
      });
    }
  });

  // Gallery Modal (Screenshots)
  let galleryModal;
  if (document.getElementById("modalGallery")) {
    galleryModal = new SoplosUI.SoplosImageModal("modalGallery", {
      closeSelector: ".close-modal-gallery",
      trackSelector: ".modal-gallery-track",
      captionSelector: ".modal-gallery-caption",
      prevSelector: ".modal-gallery-nav.prev",
      nextSelector: ".modal-gallery-nav.next",
      dotsSelector: ".modal-gallery-dots",
    });
  }

  // Gallery Data
  const galleryImages = {
    tyron: [
      { src: "images/Tyron/tyron/001-GRUB.png", captionKey: "grubCaption" },
      {
        src: "images/Tyron/tyron/002-Plymouth.png",
        captionKey: "plymouthCaption",
      },
      {
        src: "images/Tyron/tyron/003-Greeter.png",
        captionKey: "greeterCaption",
      },
      {
        src: "images/Tyron/tyron/004-Desktop-01.png",
        captionKey: "desktopCaption",
      },
      {
        src: "images/Tyron/tyron/005-Desktop-02.png",
        captionKey: "desktop2Caption",
      },
      {
        src: "images/Tyron/tyron/006-Welcome-Live.png",
        captionKey: "livedesktopCaption",
      },
      {
        src: "images/Tyron/tyron/007-Calamares-01.png",
        captionKey: "calamares1Caption",
      },
      {
        src: "images/Tyron/tyron/008-calamares-02.png",
        captionKey: "calamares2Caption",
      },
    ],
    tyson: [
      { src: "images/Tyson/tyson/001-grub.png", captionKey: "grubCaption" },
      {
        src: "images/Tyson/tyson/002-plymouth.png",
        captionKey: "plymouthCaption",
      },
      {
        src: "images/Tyson/tyson/003-greeter.png",
        captionKey: "greeterCaption",
      },
      {
        src: "images/Tyson/tyson/004-welcome.png",
        captionKey: "welcomeCaption",
      },
      {
        src: "images/Tyson/tyson/005-desktop-01.png",
        captionKey: "desktopCaption",
      },
      {
        src: "images/Tyson/tyson/006-desktop-02.png",
        captionKey: "desktop2Caption",
      },
      {
        src: "images/Tyson/tyson/007-welcome-live.png",
        captionKey: "livedesktopCaption",
      },
      {
        src: "images/Tyson/tyson/008-calamares-01.png",
        captionKey: "calamares1Caption",
      },
      {
        src: "images/Tyson/tyson/009-calamares-02.png",
        captionKey: "calamares2Caption",
      },
    ],
    boro: [
      { src: "images/boro/boro/001-grub.png", captionKey: "grubCaption" },
      {
        src: "images/boro/boro/002-plymouth.png",
        captionKey: "plymouthCaption",
      },
      { src: "images/boro/boro/003-greeter.png", captionKey: "greeterCaption" },
      { src: "images/boro/boro/004-desktop.png", captionKey: "desktopCaption" },
      {
        src: "images/boro/boro/005-welcome-live.png",
        captionKey: "livedesktopCaption",
      },
      {
        src: "images/boro/boro/006-calamares-01.png",
        captionKey: "calamares1Caption",
      },
      {
        src: "images/boro/boro/007-calamares-02.png",
        captionKey: "calamares2Caption",
      },
    ],
  };

  document.querySelectorAll(".gallery-view-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      if (!galleryModal) return;
      const distro = btn.getAttribute("data-distro");
      if (galleryImages[distro]) {
        galleryModal.setImages(galleryImages[distro]);
        galleryModal.open(btn);
      }
    });
  });

  // 3. Presentations that grow to the whole screen: the apps (section #apps,
  // apps/presentation, stopped on its cover) and the logo (the hero card,
  // motion/logo, stopped on its last frame).
  // A click makes that same frame grow from its place to the whole screen,
  // and then tells it to play; closing tells it to stop and shrinks it into
  // its place again. Nothing is loaded twice and nothing changes under the
  // eye. The page inside asks to open and close with postMessage
  // ("<name>:open", "<name>:close") and is told "<name>:play" / "<name>:stop".
  const reduceMotion = matchMedia("(prefers-reduced-motion: reduce)").matches;
  // hidden: the frame is not seen in its place (something else is shown
  // there); it fades in while it grows and out while it shrinks.
  function growingFrame(place, openBtn, name, hidden = false) {
    if (!place) return;
    const frame = place.querySelector("iframe");
    const GROW_MS = 700, SHRINK_MS = 550;
    let lastFocus = null, state = "closed";
    const tellFrame = (type) => frame.contentWindow && frame.contentWindow.postMessage({ type: `${name}:${type}` }, location.origin);

    // Puts the frame, fixed, on a rectangle of the window.
    const setBox = (r) => {
      frame.style.top = r.top + "px";
      frame.style.left = r.left + "px";
      frame.style.width = r.width + "px";
      frame.style.height = r.height + "px";
    };
    const fullBox = () => ({ top: 0, left: 0, width: innerWidth, height: innerHeight });
    const placeInView = () => {
      const r = place.getBoundingClientRect();
      return r.bottom > 0 && r.top < innerHeight;
    };
    const animate = (ms, ease) => {
      frame.style.transition = reduceMotion ? "none"
        : ["top", "left", "width", "height", "opacity"].map((p) => `${p} ${ms}ms ${ease}`).join(", ");
    };

    const open = () => {
      if (state !== "closed") return;
      state = "opening";
      lastFocus = document.activeElement;
      frame.style.transition = "none";
      setBox(place.getBoundingClientRect());
      place.classList.add("is-open");
      if (hidden) frame.style.opacity = "0";
      void frame.offsetWidth;
      animate(GROW_MS, "cubic-bezier(.7, 0, .2, 1)");
      setBox(fullBox());
      if (hidden) frame.style.opacity = "1";
      history.pushState({ frameShow: name }, "");
      setTimeout(() => {
        if (state !== "opening") return;
        state = "open";
        // From here the frame follows the window if it is resized.
        frame.style.transition = "none";
        Object.assign(frame.style, { top: "0", left: "0", width: "100%", height: "100%" });
        document.body.classList.add("frame-show-open");
        tellFrame("play");
        if (frame.contentWindow) frame.contentWindow.focus();
      }, reduceMotion ? 0 : GROW_MS);
    };

    const close = (fromHistory) => {
      if (state === "closed" || state === "closing") return;
      state = "closing";
      tellFrame("stop");
      document.body.classList.remove("frame-show-open");
      frame.style.transition = "none";
      setBox(fullBox());
      void frame.offsetWidth;
      animate(SHRINK_MS, "cubic-bezier(.5, 0, .2, 1)");
      if (placeInView()) setBox(place.getBoundingClientRect());
      if (hidden || !placeInView()) frame.style.opacity = "0";
      setTimeout(() => {
        place.classList.remove("is-open");
        frame.removeAttribute("style");
        state = "closed";
      }, reduceMotion ? 0 : SHRINK_MS);
      if (!fromHistory && history.state && history.state.frameShow === name) history.back();
      if (lastFocus) lastFocus.focus();
    };
    if (openBtn) openBtn.addEventListener("click", open);
    window.addEventListener("message", (e) => {
      if (e.origin !== location.origin || !e.data) return;
      if (e.data.type === `${name}:open`) open();
      else if (e.data.type === `${name}:close`) close(false);
    });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") close(false);
    });
    // The back button of a phone closes the window instead of leaving the page.
    window.addEventListener("popstate", () => close(true));
  }
  growingFrame(document.getElementById("apps"), document.getElementById("appsOpen"), "soplos-apps");
  // The logo is an easter egg: the card shows the wallpaper, and a click on it
  // brings the motion out of it.
  growingFrame(document.querySelector(".hero-motion"), document.getElementById("logoOpen"), "soplos-logo", true);

  // 4. Mobile Menu Logic
  const menuButton = document.querySelector(".mobile-menu-toggle");
  const mobileNav = document.querySelector(".header-right nav");

  if (menuButton && mobileNav) {
    menuButton.addEventListener("click", (e) => {
      e.preventDefault();
      // Close all dropdowns
      document
        .querySelectorAll(".menu-dropdown")
        .forEach((dd) => dd.classList.remove("open"));

      mobileNav.classList.toggle("mobile-active");
      menuButton.style.background = "#ff7d2e";
      setTimeout(() => {
        menuButton.style.background = "";
      }, 300);
    });

    // Close on link click
    mobileNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        if (link.classList.contains("menu-dropdown-toggle")) return;
        mobileNav.classList.remove("mobile-active");
        document
          .querySelectorAll(".menu-dropdown")
          .forEach((dd) => dd.classList.remove("open"));
      });
    });
  }

  // 5. Dropdown Logic
  document.querySelectorAll(".menu-dropdown-toggle").forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
      e.preventDefault();
      const parent = toggle.closest(".menu-dropdown");

      // Close others
      document.querySelectorAll(".menu-dropdown").forEach((dd) => {
        if (dd !== parent) dd.classList.remove("open");
      });

      parent.classList.toggle("open");
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    if (
      !e.target.closest(".menu-dropdown") &&
      !e.target.closest(".mobile-menu-toggle")
    ) {
      document
        .querySelectorAll(".menu-dropdown")
        .forEach((dd) => dd.classList.remove("open"));
    }
  });

  // Desktop Hover Effects
  if (window.innerWidth > 768) {
    document.querySelectorAll(".menu-dropdown").forEach((dd) => {
      let timeout;
      dd.addEventListener("mouseenter", () => {
        clearTimeout(timeout);
        dd.classList.add("open");
      });
      dd.addEventListener("mouseleave", () => {
        timeout = setTimeout(() => dd.classList.remove("open"), 100);
      });
    });
  }
  // 6. Wiki Recent Updates Year Filter
  const filterBtns = document.querySelectorAll(".filter-btn");
  const updateItems = document.querySelectorAll(".update-item");

  if (filterBtns.length > 0 && updateItems.length > 0) {
    function filterByYear(year) {
      updateItems.forEach((item) => {
        if (item.getAttribute("data-year") === year) {
          item.classList.remove("hidden");
        } else {
          item.classList.add("hidden");
        }
      });
    }

    filterBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        // Update active button
        filterBtns.forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");

        // Filter items
        const selectedYear = btn.getAttribute("data-filter");
        filterByYear(selectedYear);
      });
    });

    // Default to 2026 on load
    filterByYear("2026");
  }
});
