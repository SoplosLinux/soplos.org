/**
 * Gestión de motores de búsqueda para Soplos Linux Startpage
 */

// Motores de búsqueda soportados
const searchEngines = {
  google: {
    url: "https://www.google.com/search",
    name: "Google",
    param: "q"
  },
  duckduckgo: {
    url: "https://duckduckgo.com/",
    name: "DuckDuckGo",
    param: "q"
  },
  bing: {
    url: "https://www.bing.com/search",
    name: "Bing",
    param: "q"
  },
  ecosia: {
    url: "https://www.ecosia.org/search",
    name: "Ecosia",
    param: "q"
  }
};

// Motor de búsqueda actual
let currentEngine = localStorage.getItem('soplosSearchEngine') || "google";

// Cambiar motor de búsqueda
function setSearchEngine(engine) {
  if (!searchEngines[engine]) {
    console.error(`Motor de búsqueda no válido: ${engine}`);
    return;
  }

  currentEngine = engine;
  const searchForm = document.getElementById("search-form");
  const searchInput = document.getElementById("search-input");
  
  if (searchForm) {
    searchForm.action = searchEngines[engine].url;
    // Actualizar el atributo name del input para el parámetro correcto
    searchInput.name = searchEngines[engine].param;
  }
  
  // Actualizar placeholder inmediatamente
  if (searchInput) {
    const currentLang = localStorage.getItem('soplosStartPageLang') || 'es';
    let placeholderText;
    
    if (window.translations && window.translations[currentLang] && window.translations[currentLang].searchPlaceholder) {
      // Usar la traducción base y reemplazar "Google" con el motor actual
      placeholderText = window.translations[currentLang].searchPlaceholder.replace("Google", searchEngines[engine].name);
    } else {
      // Fallback si no hay traducciones disponibles
      const fallbacks = {
        'es': `Buscar en ${searchEngines[engine].name}`,
        'en': `Search on ${searchEngines[engine].name}`,
        'pt': `Pesquisar no ${searchEngines[engine].name}`,
        'fr': `Rechercher sur ${searchEngines[engine].name}`,
        'de': `Suchen auf ${searchEngines[engine].name}`,
        'it': `Cerca su ${searchEngines[engine].name}`,
        'ro': `Caută pe ${searchEngines[engine].name}`,
        'ru': `Искать в ${searchEngines[engine].name}`
      };
      placeholderText = fallbacks[currentLang] || `Buscar en ${searchEngines[engine].name}`;
    }
    
    searchInput.placeholder = placeholderText;
  }
  
  // Actualizar estado visual de los botones
  updateSearchEngineButtons();
  
  // Guardar preferencia en localStorage
  localStorage.setItem('soplosSearchEngine', engine);
  
  console.log(`Motor de búsqueda cambiado a: ${searchEngines[engine].name}`);
  console.log(`Placeholder actualizado a: ${searchInput ? searchInput.placeholder : 'N/A'}`);
}

// Actualizar estado visual de los botones
function updateSearchEngineButtons() {
  const buttons = document.querySelectorAll('.search-engines button');
  buttons.forEach(button => {
    // Resetear estilo
    button.style.border = "1px solid #444";
    button.style.backgroundColor = "transparent";
    
    // Obtener el motor del botón basado en data-engine
    const buttonEngine = button.getAttribute('data-engine');
    
    if (buttonEngine === currentEngine) {
      button.style.border = "2px solid #ff9800";
      button.style.backgroundColor = "rgba(255, 152, 0, 0.2)";
    }
  });
}

// Inicializar motor de búsqueda al cargar
document.addEventListener('DOMContentLoaded', function() {
  // Aplicar motor guardado inmediatamente
  setTimeout(() => {
    setSearchEngine(currentEngine);
  }, 50);
});

// Escuchar cambios de idioma para actualizar placeholders
document.addEventListener('languageChanged', function(e) {
  // Actualizar placeholder cuando cambie el idioma
  setTimeout(() => {
    setSearchEngine(currentEngine);
  }, 50);
});
