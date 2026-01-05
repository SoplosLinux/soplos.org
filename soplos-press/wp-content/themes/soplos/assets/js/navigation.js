/**
 * Soplos Theme - Navigation Script
 *
 * @package Soplos
 */

(function () {
  "use strict";

  // DOM Ready
  document.addEventListener("DOMContentLoaded", function () {
    initMobileMenu();
    initStickyHeader();
    initDropdownMenus();
    initHeaderSearch();
  });

  /**
   * Mobile Menu Toggle
   */
  function initMobileMenu() {
    const menuToggle = document.querySelector(".mobile-menu-toggle");
    const mainNav = document.querySelector(".main-navigation");

    if (!menuToggle || !mainNav) return;

    menuToggle.addEventListener("click", function () {
      const isExpanded = this.getAttribute("aria-expanded") === "true";

      this.setAttribute("aria-expanded", !isExpanded);
      mainNav.classList.toggle("active");

      // Toggle icon
      const icon = this.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-bars");
        icon.classList.toggle("fa-times");
      }
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (e) {
      if (!menuToggle.contains(e.target) && !mainNav.contains(e.target)) {
        menuToggle.setAttribute("aria-expanded", "false");
        mainNav.classList.remove("active");

        const icon = menuToggle.querySelector("i");
        if (icon) {
          icon.classList.add("fa-bars");
          icon.classList.remove("fa-times");
        }
      }
    });

    // Close menu on escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && mainNav.classList.contains("active")) {
        menuToggle.setAttribute("aria-expanded", "false");
        mainNav.classList.remove("active");
        menuToggle.focus();
      }
    });
  }

  /**
   * Sticky Header on Scroll
   */
  function initStickyHeader() {
    const header = document.querySelector(".site-header");

    if (!header) return;

    // Check features
    const isSticky = header.classList.contains("sticky-enabled");
    const hideOnScroll = header.getAttribute('data-hide-scroll') === 'true';

    // Exit if neither feature is active
    if (!isSticky && !hideOnScroll) return;

    let lastScroll = 0;

    window.addEventListener("scroll", function () {
      const currentScroll = window.pageYOffset;

      // Sticky styling (background change)
      if (isSticky) {
          if (currentScroll > 50) {
            header.classList.add("scrolled");
          } else {
            header.classList.remove("scrolled");
          }
      }

      // Hide on Scroll Logic
      if (hideOnScroll) {
          // Threshold 100px to avoid flickering
          if (currentScroll > 100 && currentScroll > lastScroll) {
              // Scroll Down -> Hide
              header.classList.add('header-hidden');
          } else {
              // Scroll Up -> Show
              header.classList.remove('header-hidden');
          }
      }

      lastScroll = currentScroll <= 0 ? 0 : currentScroll;
    });
  }

  /**
   * Dropdown Menus (for keyboard navigation)
   */
  function initDropdownMenus() {
    const menuItems = document.querySelectorAll(
      ".main-navigation .menu-item-has-children"
    );

    menuItems.forEach(function (item) {
      const link = item.querySelector("a");
      const submenu = item.querySelector(".sub-menu");

      if (!link || !submenu) return;

      // Add dropdown indicator if not present
      if (!link.querySelector(".dropdown-indicator")) {
        const indicator = document.createElement("span");
        indicator.className = "dropdown-indicator";
        indicator.innerHTML = ' <i class="fas fa-chevron-down"></i>';
        link.appendChild(indicator);
      }

      // Keyboard navigation
      link.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          item.classList.toggle("open");
        }
      });

      // Close on focus out
      item.addEventListener("focusout", function (e) {
        setTimeout(function () {
          if (!item.contains(document.activeElement)) {
            item.classList.remove("open");
          }
        }, 100);
      });
    });
  }
  /**
   * Header Search Toggle
   */
  function initHeaderSearch() {
    const searchToggle = document.querySelector(".header-search-toggle");
    const searchDropdown = document.querySelector(".header-search-dropdown");
    
    if (!searchToggle || !searchDropdown) return;

    searchToggle.addEventListener("click", function (e) {
      e.stopPropagation();
      searchDropdown.classList.toggle("active");
      
      const icon = this.querySelector("i");
      if (icon) {
          icon.classList.toggle("fa-search");
          icon.classList.toggle("fa-times");
      }

      // Focus input
      if (searchDropdown.classList.contains("active")) {
          const input = searchDropdown.querySelector("input[type='search']");
          if (input) setTimeout(() => input.focus(), 100);
      }
    });

    // Close outside click
    document.addEventListener("click", function (e) {
      if (!searchToggle.contains(e.target) && !searchDropdown.contains(e.target)) {
        searchDropdown.classList.remove("active");
        const icon = searchToggle.querySelector("i");
        if (icon) {
            icon.classList.add("fa-search");
            icon.classList.remove("fa-times");
        }
      }
    });
  }
})();
