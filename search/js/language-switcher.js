/**
 * Controlador del selector de idiomas con banderas
 */

document.addEventListener('DOMContentLoaded', function() {
  // Elementos del selector
  const selectedLang = document.querySelector('.selected-language');
  const dropdown = document.querySelector('.language-dropdown');
  const languageOptions = document.querySelectorAll('.language-dropdown a');
  
  // Mostrar/ocultar menú desplegable
  if (selectedLang && dropdown) {
    selectedLang.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });
    
    // Cerrar al hacer clic fuera
    document.addEventListener('click', function() {
      dropdown.classList.remove('show');
    });
    
    // Evitar cierre al hacer clic en el dropdown
    dropdown.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
  
  // Seleccionar idioma
  languageOptions.forEach(option => {
    option.addEventListener('click', function(e) {
      e.preventDefault();
      
      const lang = this.getAttribute('data-lang');
      if (lang) {
        // Actualizar la UI
        updateLanguageUI(lang);
        
        // Llamar a la función de cambio de idioma del módulo de traducciones
        changeLanguage(lang);
        
        // Cerrar el dropdown
        dropdown.classList.remove('show');
      }
    });
  });
  
  // Función para actualizar la UI del selector
  function updateLanguageUI(lang) {
    // Actualizar selección actual
    const flagImg = selectedLang.querySelector('img');
    const langText = selectedLang.querySelector('span');
    
    // Para inglés usamos GB como código de país
    const countryCode = lang === 'en' ? 'gb' : lang;
    
    flagImg.src = `https://flagcdn.com/w20/${countryCode}.png`;
    flagImg.alt = getLangName(lang);
    langText.textContent = lang.toUpperCase();
    
    // Actualizar estado activo en el menú
    languageOptions.forEach(option => {
      option.classList.remove('active');
      if (option.getAttribute('data-lang') === lang) {
        option.classList.add('active');
      }
    });
  }
  
  // Helper para obtener el nombre del idioma
  function getLangName(code) {
    const names = {
      'es': 'Español',
      'en': 'English',
      'pt': 'Português',
      'fr': 'Français',
      'de': 'Deutsch',
      'it': 'Italiano',
      'ro': 'Română',
      'ru': 'Русский'
    };
    return names[code] || code;
  }
  
  // Al cargar, establecer el idioma guardado o predeterminado
  const savedLang = localStorage.getItem('soplosStartPageLang') || 'es';
  updateLanguageUI(savedLang);
});
