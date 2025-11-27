/**
 * Script principal para Soplos Linux Startpage
 */

document.addEventListener('DOMContentLoaded', function() {
  // Hacer variables globales disponibles para sincronización
  window.searchEngines = searchEngines;
  window.currentEngine = currentEngine;

  // Aplicar idioma guardado o predeterminado del navegador
  const savedLang = localStorage.getItem('soplosStartPageLang');
  const browserLang = navigator.language.split('-')[0];
  const supportedLangs = ['es', 'en', 'pt', 'fr', 'de', 'it', 'ro', 'ru'];
  const defaultLang = 'es';
  
  // Determinar qué idioma usar
  let selectedLang = defaultLang;
  if (savedLang && supportedLangs.includes(savedLang)) {
    selectedLang = savedLang;
  } else if (supportedLangs.includes(browserLang)) {
    selectedLang = browserLang;
  }
  
  // Aplicar el idioma
  if (typeof changeLanguage === 'function') {
    changeLanguage(selectedLang);
  }
  
  // Efecto de desplazamiento al cargar
  setTimeout(() => {
    document.querySelector('.search-container').classList.add('loaded');
  }, 100);
  
  // Enfocar automáticamente la caja de búsqueda
  setTimeout(() => {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
      searchInput.focus();
    }
  }, 800);
  
  // Configurar eventos para botones de motor de búsqueda
  setupSearchEngineButtons();
  
  console.log('Soplos Linux Startpage iniciada correctamente');
});

// Configurar eventos para los botones de motor de búsqueda
function setupSearchEngineButtons() {
  const searchButtons = document.querySelectorAll('.search-engines button[data-engine]');
  
  searchButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const engine = this.getAttribute('data-engine');
      if (engine && typeof setSearchEngine === 'function') {
        console.log(`Botón clickeado: ${engine}`);
        setSearchEngine(engine);
        // Actualizar variable global
        window.currentEngine = engine;
      }
    });
  });
  
  console.log(`Configurados ${searchButtons.length} botones de motor de búsqueda`);
}
