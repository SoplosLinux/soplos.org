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

  // Apps Carousel
  new SoplosUI.SoplosCarousel(".apps-versions-grid", ".apps-version-card", {
    prevClass: "apps-arrow prev",
    nextClass: "apps-arrow next",
    dotsClass: "apps-dots",
    dotClass: "apps-dot",
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

  // App Modal
  let appModal;
  if (document.getElementById("modalApp")) {
    appModal = new SoplosUI.SoplosImageModal("modalApp", {
      closeSelector: ".close-modal-app",
      trackSelector: ".modal-app-track", // Updated to point to inner track
      captionSelector: ".modal-app-caption",
      prevSelector: ".modal-app-nav.prev",
      nextSelector: ".modal-app-nav.next",
      dotsSelector: ".modal-app-dots",
    });
  }

  const appData = {
    tyron: {
      "theme-manager": {
        titleKey: "appThemeManagerTitle",
        descKey: "appThemeManagerDesc",
        images: [
          "images/Tyron/theme-manager/001-theme-manager.png",
          "images/Tyron/theme-manager/002-theme-selector.png",
          "images/Tyron/theme-manager/003-remove-themes.png",
          "images/Tyron/theme-manager/004-create-theme.png",
        ],
      },
      "plymouth-manager": {
        titleKey: "appPlymouthManagerTitle",
        descKey: "appPlymouthManagerDesc",
        images: [
          "images/Tyron/plymouth-manager/001-plymouth-manager.png",
          "images/Tyron/plymouth-manager/002-apply-changes.png",
          "images/Tyron/plymouth-manager/003-Plymouth.png",
        ],
      },
      docklike: {
        titleKey: "appDocklikeTitle",
        descKey: "appDocklikeDesc",
        images: [
          "images/Tyron/docklike/001-docklike-interface.png",
          "images/Tyron/docklike/002-select-items.png",
          "images/Tyron/docklike/003-docklike-panel.png",
        ],
      },
      "grub-editor": {
        titleKey: "appGrubEditorTitle",
        descKey: "appGrubEditorDesc",
        images: [
          "images/Tyron/grub-editor/001-grub-editor.png",
          "images/Tyron/grub-editor/002-boot-entries.png",
          "images/Tyron/grub-editor/003-themes.png",
          "images/Tyron/grub-editor/004-fonts.png",
        ],
      },
      "repo-selector": {
        titleKey: "appRepoSelectorTitle",
        descKey: "appRepoSelectorDesc",
        images: [
          "images/Tyron/repo-selector/001-reposelector.png",
          "images/Tyron/repo-selector/002-speed-test.png",
          "images/Tyron/repo-selector/003-repo-list.png",
          "images/Tyron/repo-selector/004-gpg-keys.png",
        ],
      },
      welcome: {
        titleKey: "appWelcomeTitle",
        descKey: "appWelcomeDesc",
        images: [
          "images/Tyron/welcome/001-welcome.png",
          "images/Tyron/welcome/002-software.png",
          "images/Tyron/welcome/003-drives-01.png",
          "images/Tyron/welcome/004-drivers-02.png",
          "images/Tyron/welcome/005-kernels-01.png",
          "images/Tyron/welcome/006-kernels-02.png",
          "images/Tyron/welcome/007-security.png",
          "images/Tyron/welcome/008-recomendations.png",
          "images/Tyron/welcome/009-customization-01.png",
          "images/Tyron/welcome/010-customization-02.png",
        ],
      },
      "welcome-live": {
        titleKey: "appWelcomeLiveTitle",
        descKey: "appWelcomeLiveDesc",
        images: [
          "images/Tyron/welcome-live/001-welcome-live.png",
          "images/Tyron/welcome-live/002-chroot.png",
          "images/Tyron/welcome-live/003-partitions.png",
          "images/Tyron/welcome-live/004-terminal.png",
        ],
      },
    },
    tyson: {
      "plymouth-manager": {
        titleKey: "appPlymouthManagerTitle",
        descKey: "appPlymouthManagerDesc",
        images: [
          "images/Tyson/plymouth-manager/001-plymouth-manager.png",
          "images/Tyson/plymouth-manager/002-apply-theme.png",
          "images/Tyson/plymouth-manager/003-Plymouth.png",
        ],
      },
      docklike: {
        // Tyson uses Tyron's docklike images or doesn't have it?
        // Index.html doesn't show docklike for Tyson. Removing it.
        titleKey: "appDocklikeTitle",
        descKey: "appDocklikeDesc",
        images: [],
      },
      "grub-editor": {
        titleKey: "appGrubEditorTitle",
        descKey: "appGrubEditorDesc",
        images: [
          "images/Tyson/grub-editor/001-grub-editor.png",
          "images/Tyson/grub-editor/002-boot-entries.png",
          "images/Tyson/grub-editor/003-select-theme.png",
          "images/Tyson/grub-editor/004-select-font.png",
        ],
      },
      "repo-selector": {
        titleKey: "appRepoSelectorTitle",
        descKey: "appRepoSelectorDesc",
        images: [
          "images/Tyson/repo-selector/001-reposelector.png",
          "images/Tyson/repo-selector/002-speed-test.png",
          "images/Tyson/repo-selector/003-repo-list.png",
          "images/Tyson/repo-selector/004-gpg-keys.png",
        ],
      },
      welcome: {
        titleKey: "appWelcomeTitle",
        descKey: "appWelcomeDesc",
        images: [
          "images/Tyson/welcome/001-welcome.png",
          "images/Tyson/welcome/002-software.png",
          "images/Tyson/welcome/003-drives-01.png",
          "images/Tyson/welcome/004-drivers-02.png",
          "images/Tyson/welcome/005-kernels-01.png",
          "images/Tyson/welcome/006-kernels-02.png",
          "images/Tyson/welcome/007-security.png",
          "images/Tyson/welcome/008-recomendations.png",
          "images/Tyson/welcome/009-customization-01.png",
          "images/Tyson/welcome/010-customization-02.png",
        ],
      },
      "welcome-live": {
        titleKey: "appWelcomeLiveTitle",
        descKey: "appWelcomeLiveDesc",
        images: [
          "images/Tyson/welcome-live/001-welcome-live.png",
          "images/Tyson/welcome-live/002-chroot.png",
          "images/Tyson/welcome-live/003-partitions.png",
          "images/Tyson/welcome-live/004-terminal.png",
        ],
      },
    },
    boro: {
      "grub-editor": {
        titleKey: "appGrubEditorTitle",
        descKey: "appGrubEditorDesc",
        images: [
          "images/boro/grub-editor/001-grub-editor.png",
          "images/boro/grub-editor/002-boot-entries.png",
          "images/boro/grub-editor/003-themes.png",
          "images/boro/grub-editor/004-fonts.png",
        ],
      },
      "plymouth-manager": {
        titleKey: "appPlymouthManagerTitle",
        descKey: "appPlymouthManagerDesc",
        images: [
          "images/boro/plymouth-manager/001-plymouth-manager.png",
          "images/boro/plymouth-manager/002-appy-theme.png",
          "images/boro/plymouth-manager/003-Plymouth.png",
        ],
      },
      "repo-selector": {
        titleKey: "appRepoSelectorTitle",
        descKey: "appRepoSelectorDesc",
        images: [
          "images/boro/repo-selector/001-repo-selector.png",
          "images/boro/repo-selector/002-speed-test.png",
          "images/boro/repo-selector/003-repo-list.png",
          "images/boro/repo-selector/004-gpg-keys.png",
        ],
      },
      welcome: {
        titleKey: "appWelcomeTitle",
        descKey: "appWelcomeDesc",
        images: [
          "images/boro/welcome/001-soplos-welcome.png",
          "images/boro/welcome/002-software-tab.png",
          "images/boro/welcome/003-drivers-tab-01.png",
          "images/boro/welcome/004-drivers-tab-02.png",
          "images/boro/welcome/005-kernels-tab-01.png",
          "images/boro/welcome/005-kernels-tab-02.png",
          "images/boro/welcome/006-security-tab-01.png",
          "images/boro/welcome/007-recommends-tab.png",
          "images/boro/welcome/008-customization-tab-01.png",
          "images/boro/welcome/009-customization-tab-02.png",
        ],
      },
      "welcome-live": {
        titleKey: "appWelcomeLiveTitle",
        descKey: "appWelcomeLiveDesc",
        images: [
          "images/boro/welcome-live/001-welcome-live.png",
          "images/boro/welcome-live/002-chroot.png",
          "images/boro/welcome-live/003-partitions.png",
          "images/boro/welcome-live/004-terminal.png",
        ],
      },
    },
  };

  document.querySelectorAll(".app-icon-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      if (!appModal) return;
      const app = btn.getAttribute("data-app");
      const version = btn.getAttribute("data-version");
      const data = appData[version] && appData[version][app];

      if (data) {
        // Update Modal Header
        const modalIcon = document.getElementById("modal-app-icon");
        const modalTitle = document.getElementById("modal-app-title");
        const modalDesc = document.getElementById("modal-app-desc");

        if (modalIcon) modalIcon.src = btn.getAttribute("data-icon");
        if (modalTitle)
          modalTitle.textContent = window.getTranslatedText(data.titleKey);
        if (modalDesc)
          modalDesc.textContent = window.getTranslatedText(data.descKey);

        // Set Images
        // Use data.images directly
        const images = data.images.map((src) => ({ src: src }));
        appModal.setImages(images);
        appModal.open(btn);
      }
    });
  });

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
