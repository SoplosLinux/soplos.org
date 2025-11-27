/**
 * Gestión de sitios frecuentes para Soplos Linux Startpage
 */

class FrequentSites {
  constructor() {
    // Elementos del DOM
    this.sitesGrid = document.getElementById('sites-grid');
    this.modal = document.getElementById('add-site-modal');
    this.closeModalBtn = document.querySelector('.close-modal');
    this.siteForm = document.getElementById('add-site-form');
    this.siteNameInput = document.getElementById('site-name');
    this.siteUrlInput = document.getElementById('site-url');
    this.siteIconSelect = document.getElementById('site-icon');
    
    // Traducciones
    this.translations = {
      es: {
        'frequent-sites-title': 'Sitios frecuentes',
        'add-site-text': 'Añadir sitio',
        'modal-title': 'Añadir sitio frecuente',
        'site-name-label': 'Nombre:',
        'site-url-label': 'URL:',
        'site-icon-label': 'Icono:',
        'save-site-btn': 'Guardar',
        'add-new-site': 'Añadir nuevo sitio',
        'fields-required': 'Por favor completa todos los campos',
        'site-added-ok': 'Sitio "{name}" añadido correctamente',
        'site-added-error': 'Error al guardar el sitio. Inténtalo de nuevo.',
        'delete-confirmation': '¿Eliminar "{name}" de tus favoritos?',
        'delete-error': 'Error al eliminar el sitio'
      },
      en: {
        'frequent-sites-title': 'Frequent sites',
        'add-site-text': 'Add site',
        'modal-title': 'Add frequent site',
        'site-name-label': 'Name:',
        'site-url-label': 'URL:',
        'site-icon-label': 'Icon:',
        'save-site-btn': 'Save',
        'add-new-site': 'Add new site',
        'fields-required': 'Please complete all fields',
        'site-added-ok': 'Site "{name}" added successfully',
        'site-added-error': 'Error saving the site. Please try again.',
        'delete-confirmation': 'Delete "{name}" from your favorites?',
        'delete-error': 'Error deleting the site'
      }
    };
    
    // Idioma actual
    this.currentLang = localStorage.getItem('soplosStartPageLang') || 'es';
    
    // Iconos de respaldo si el favicon no está disponible
    this.fallbackIcons = {
      'google.com': { icon: 'fab fa-google', color: '#4285F4' },
      'youtube.com': { icon: 'fab fa-youtube', color: '#FF0000' },
      'facebook.com': { icon: 'fab fa-facebook', color: '#1877F2' },
      'twitter.com': { icon: 'fab fa-twitter', color: '#1DA1F2' },
      'instagram.com': { icon: 'fab fa-instagram', color: '#E1306C' },
      'amazon.com': { icon: 'fab fa-amazon', color: '#FF9900' },
      'github.com': { icon: 'fab fa-github', color: '#333333' },
      'linkedin.com': { icon: 'fab fa-linkedin', color: '#0077B5' },
      'netflix.com': { icon: 'fas fa-film', color: '#E50914' },
      'spotify.com': { icon: 'fab fa-spotify', color: '#1DB954' }
    };
    
    // Iconos para la selección
    this.iconOptions = {
      'globe': { icon: 'fas fa-globe', emoji: '🌐' },
      'shopping-cart': { icon: 'fas fa-shopping-cart', emoji: '🛒' },
      'video': { icon: 'fas fa-video', emoji: '📺' },
      'music': { icon: 'fas fa-music', emoji: '🎵' },
      'book': { icon: 'fas fa-book', emoji: '📚' },
      'code': { icon: 'fas fa-code', emoji: '💻' },
      'cloud': { icon: 'fas fa-cloud', emoji: '☁️' },
      'social': { icon: 'fas fa-users', emoji: '👥' }
    };
    
    // Sitios predeterminados (solo se usan si no hay guardados en localStorage)
    this.defaultSites = [
      { name: 'Google', url: 'https://www.google.com', icon: 'google.com' },
      { name: 'YouTube', url: 'https://www.youtube.com', icon: 'youtube.com' },
      { name: 'GitHub', url: 'https://github.com', icon: 'github.com' },
      { name: 'Wikipedia', url: 'https://www.wikipedia.org', icon: 'globe' }
    ];
    
    this.sites = [];
    
    // Límite máximo de sitios mostrados
    this.MAX_SITES = 9;
    
    // Inicializar
    this.init();
  }
  
  init() {
    console.log('Inicializando sistema de sitios frecuentes automáticos...');
    
    // Intentar cargar sitios del historial del navegador
    this.loadTopSitesFromBrowser().then(gotTopSites => {
      if (!gotTopSites) {
        // Si no se pueden obtener del navegador, cargar los guardados o predeterminados
        this.loadSites();
      }
      
      // Renderizar sitios
      this.renderSites();
      
      // Configurar eventos
      this.setupEventListeners();
      
      // Integrar con el sistema de traducciones
      this.setupTranslations();
    });
  }
  
  // Nueva función: Intentar cargar sitios más visitados directamente del navegador
  async loadTopSitesFromBrowser() {
    try {
      // Intentar usar la API de Chrome
      if (typeof chrome !== 'undefined' && chrome.topSites) {
        console.log('Detectado Chrome/Chromium. Usando chrome.topSites API...');
        const topSites = await new Promise(resolve => {
          chrome.topSites.get(sites => resolve(sites));
        });
        
        if (topSites && topSites.length > 0) {
          this.processTopSites(topSites);
          return true;
        }
      }
      
      // Intentar usar la API de Firefox
      if (typeof browser !== 'undefined' && browser.topSites) {
        console.log('Detectado Firefox. Usando browser.topSites API...');
        const topSites = await browser.topSites.get();
        
        if (topSites && topSites.length > 0) {
          this.processTopSites(topSites);
          return true;
        }
      }
      
      // Intentar usar la API del historial si está disponible
      if (typeof chrome !== 'undefined' && chrome.history) {
        console.log('Intentando acceder al historial de Chrome...');
        const oneWeekAgo = new Date().getTime() - (7 * 24 * 60 * 60 * 1000);
        
        const history = await new Promise(resolve => {
          chrome.history.search({
            text: '',
            startTime: oneWeekAgo,
            maxResults: 100
          }, items => resolve(items));
        });
        
        if (history && history.length > 0) {
          // Procesar y agrupar por dominio para encontrar los más visitados
          const domainCounts = {};
          
          history.forEach(item => {
            try {
              const domain = new URL(item.url).hostname;
              if (!domainCounts[domain]) {
                domainCounts[domain] = {
                  count: 0,
                  title: item.title || domain,
                  url: item.url
                };
              }
              domainCounts[domain].count += 1;
            } catch (e) {
              // Ignorar URLs no válidas
            }
          });
          
          // Convertir a array y ordenar
          const topDomains = Object.values(domainCounts)
            .sort((a, b) => b.count - a.count)
            .slice(0, this.MAX_SITES);
          
          // Crear sitios a partir de dominios principales
          this.sites = topDomains.map(domain => ({
            name: domain.title,
            url: domain.url,
            visits: domain.count
          }));
          
          this.saveSites();
          return true;
        }
      }
      
      console.log('No se pudo acceder a la API de sitios frecuentes del navegador');
      return false;
    } catch (error) {
      console.error('Error al cargar sitios del navegador:', error);
      return false;
    }
  }
  
  // Procesar sitios obtenidos de API del navegador
  processTopSites(topSites) {
    console.log(`Obtenidos ${topSites.length} sitios frecuentes del navegador`);
    
    // Convertir al formato interno
    this.sites = topSites.map(site => ({
      name: site.title || this.extractDomain(site.url),
      url: site.url,
      visits: 10, // Valor arbitrario alto para que sean prioritarios
      lastVisit: new Date().getTime() // Considerarlos como visitados recientemente
    })).slice(0, this.MAX_SITES);
    
    // Guardar estos sitios para futuras visitas
    this.saveSites();
  }
  
  loadSites() {
    const savedSites = localStorage.getItem('soplosFrequentSites');
    console.log('Cargando sitios guardados:', savedSites ? 'Encontrados' : 'No encontrados');
    
    if (savedSites) {
      try {
        this.sites = JSON.parse(savedSites);
        console.log(`${this.sites.length} sitios cargados correctamente`);
      } catch (e) {
        console.error('Error al cargar sitios guardados:', e);
        this.sites = this.getDefaultSites();
      }
    } else {
      // Usar sitios predeterminados
      console.log('Usando sitios predeterminados');
      this.sites = this.getDefaultSites();
      this.saveSites();
    }
  }
  
  getDefaultSites() {
    return this.defaultSites.map(site => ({
      name: site.name,
      url: site.url,
      visits: 0
    }));
  }
  
  saveSites() {
    try {
      localStorage.setItem('soplosFrequentSites', JSON.stringify(this.sites));
      console.log(`${this.sites.length} sitios guardados correctamente`);
      return true;
    } catch (e) {
      console.error('Error al guardar sitios:', e);
      return false;
    }
  }
  
  renderSites() {
    // Limpiar contenido existente
    this.sitesGrid.innerHTML = '';
    
    // Ordenar por visitas antes de mostrar
    this.sites.sort((a, b) => (b.visits || 0) - (a.visits || 0));
    
    // Mostrar solo hasta el límite máximo de sitios
    const sitesToShow = this.sites.slice(0, this.MAX_SITES);
    
    console.log(`Renderizando ${sitesToShow.length} sitios`);
    
    // Renderizar cada sitio
    sitesToShow.forEach((site, index) => {
      const tile = document.createElement('div');
      tile.className = 'site-tile';
      tile.dataset.url = site.url;
      tile.dataset.index = index;
      
      // Crear el contenido inicial
      tile.innerHTML = `
        <div class="site-icon">
          <img class="favicon" src="" alt="${site.name}" onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
          <i style="display:none;"></i>
        </div>
        <div class="site-name">${site.name}</div>
        <button class="site-delete" title="Eliminar"><i class="fas fa-times"></i></button>
      `;
      
      // Cargar el favicon
      this.loadFavicon(site.url, tile);
      
      // Evento para abrir el sitio
      tile.addEventListener('click', (e) => {
        if (!e.target.closest('.site-delete')) {
          window.open(site.url, '_blank');
          this.updateSiteVisits(index);
        }
      });
      
      // Evento para eliminar el sitio
      const deleteBtn = tile.querySelector('.site-delete');
      deleteBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.deleteSite(index);
      });
      
      this.sitesGrid.appendChild(tile);
    });
    
    // Completar con espacios vacíos si hay menos de MAX_SITES sitios
    const emptySlots = this.MAX_SITES - sitesToShow.length;
    for (let i = 0; i < emptySlots; i++) {
      const emptyTile = document.createElement('div');
      emptyTile.className = 'site-tile empty';
      emptyTile.title = this.getTranslation("add-new-site");
      
      // Asegurar que el evento click funcione correctamente
      emptyTile.addEventListener('click', () => {
        console.log('Abriendo modal para añadir sitio');
        this.openAddSiteModal();
      });
      
      this.sitesGrid.appendChild(emptyTile);
    }
  }
  
  updateSiteVisits(index) {
    // Incrementar contador de visitas
    if (!this.sites[index].visits) {
      this.sites[index].visits = 0;
    }
    this.sites[index].visits++;
    
    // Guardar y reordenar
    this.saveSites();
  }
  
  openAddSiteModal() {
    // Resetear formulario antes de abrir
    this.siteForm.reset();
    // Mostrar modal
    this.modal.classList.add('show');
    // Enfocar el primer campo
    setTimeout(() => this.siteNameInput.focus(), 100);
  }
  
  loadFavicon(url, tileElement) {
    try {
      const domain = this.extractDomain(url);
      const faviconUrl = `https://www.google.com/s2/favicons?domain=${domain}&sz=64`;
      const imgElement = tileElement.querySelector('.favicon');
      const fallbackIcon = tileElement.querySelector('.site-icon i');
      
      // Establecer el favicon
      imgElement.src = faviconUrl;
      
      // Configurar icono de respaldo si el favicon falla
      if (this.fallbackIcons[domain]) {
        const iconInfo = this.fallbackIcons[domain];
        fallbackIcon.className = iconInfo.icon;
        fallbackIcon.style.color = iconInfo.color;
      } else {
        fallbackIcon.className = 'fas fa-globe';
      }
    } catch (e) {
      console.error('Error al cargar favicon:', e);
    }
  }
  
  setupEventListeners() {
    // Cerrar modal
    this.closeModalBtn.addEventListener('click', () => {
      this.modal.classList.remove('show');
    });
    
    // Clic fuera del modal para cerrar
    this.modal.addEventListener('click', (e) => {
      if (e.target === this.modal) {
        this.modal.classList.remove('show');
      }
    });
    
    // Guardar nuevo sitio
    this.siteForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const newSiteName = this.siteNameInput.value.trim();
      const newSiteUrl = this.siteUrlInput.value.trim();
      
      if (!newSiteName || !newSiteUrl) {
        alert(this.getTranslation("fields-required"));
        return;
      }
      
      const newSite = {
        name: newSiteName,
        url: newSiteUrl,
        visits: 0
      };
      
      // Añadir protocolo si falta
      if (!newSite.url.startsWith('http')) {
        newSite.url = 'https://' + newSite.url;
      }
      
      console.log('Añadiendo nuevo sitio:', newSite);
      
      // Añadir el sitio
      this.sites.push(newSite);
      
      // Guardar en localStorage
      if (this.saveSites()) {
        // Mostrar confirmación
        alert(this.getTranslation("site-added-ok", {name: newSite.name}));
        
        // Limpiar formulario
        this.siteForm.reset();
        
        // Cerrar modal
        this.modal.classList.remove('show');
        
        // Redibujar sitios
        this.renderSites();
      } else {
        alert(this.getTranslation("site-added-error"));
      }
    });
    
    // Añadir soporte para tecla Escape para cerrar el modal
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.modal.classList.contains('show')) {
        this.modal.classList.remove('show');
      }
    });
    
    // Comprobar cambios en el historial periódicamente
    setInterval(() => {
      this.checkForNewTopSites();
    }, 15 * 60 * 1000); // Cada 15 minutos
    
    // Escuchar cambios de idioma
    document.addEventListener('languageChanged', (e) => {
      this.currentLang = e.detail.lang;
      this.updateModalTexts();
    });
  }
  
  // Nueva función para comprobar cambios en el historial periódicamente
  checkForNewTopSites() {
    console.log('Comprobando nuevos sitios frecuentes...');
    this.loadTopSitesFromBrowser().then(updated => {
      if (updated) {
        console.log('Sitios frecuentes actualizados desde el navegador');
        this.renderSites();
      }
    });
  }
  
  deleteSite(index) {
    const siteName = this.sites[index].name;
    if (confirm(this.getTranslation("delete-confirmation", {name: siteName}))) {
      this.sites.splice(index, 1);
      if (this.saveSites()) {
        console.log(`Sitio "${siteName}" eliminado`);
        this.renderSites();
      } else {
        alert(this.getTranslation("delete-error"));
      }
    }
  }
  
  extractDomain(url) {
    try {
      const hostname = new URL(url).hostname;
      return hostname.replace('www.', '');
    } catch (e) {
      return '';
    }
  }
  
  setupTranslations() {
    // Si existe el sistema de traducciones global, integrar nuevas traducciones
    if (window.translations) {
      // Agregar nuestras traducciones al sistema global
      for (const lang in this.translations) {
        if (!window.translations[lang]) {
          window.translations[lang] = {};
        }
        
        for (const key in this.translations[lang]) {
          window.translations[lang][key] = this.translations[lang][key];
        }
      }
    }
  }
  
  updateModalTexts() {
    // Estos elementos ya se actualizan en la función changeLanguage global
  }
  
  getTranslation(key, replacements) {
    // Usar la función global si está disponible
    if (typeof getTranslatedString === 'function') {
      return getTranslatedString(key, this.currentLang, replacements);
    }
    
    // Fallback: usar traducciones locales o la clave
    const lang = this.currentLang;
    let text = '';
    
    if (window.translations && window.translations[lang] && window.translations[lang][key]) {
      text = window.translations[lang][key];
    } else if (this.translations[lang] && this.translations[lang][key]) {
      text = this.translations[lang][key];
    } else {
      return key; // Si no hay traducción, devolver la clave
    }
    
    // Realizar reemplazos si existen
    if (replacements) {
      for (const [placeholder, value] of Object.entries(replacements)) {
        text = text.replace(`{${placeholder}}`, value);
      }
    }
    
    return text;
  }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
  window.frequentSites = new FrequentSites();
});
