// Language loader with better error handling

// Determine base path relative to this script
const loaderScriptSrc = document.currentScript.src;
const basePath = loaderScriptSrc.substring(0, loaderScriptSrc.lastIndexOf('/') + 1); // ends with js/

// Track current language
window.currentLanguage = localStorage.getItem('soplosLanguage') || 'en';

// Helper function to get translated text for dynamic content (like modals)
window.getTranslatedText = function (key) {
    const lang = window.currentLanguage;
    const dict = window['LANG_' + lang.toUpperCase()];

    if (dict && dict[key]) {
        return dict[key];
    }

    // Fallback to English if available
    if (window.LANG_EN && window.LANG_EN[key]) {
        return window.LANG_EN[key];
    }

    return key; // Return key if no translation found
};

function loadLanguage(lang) {
    // Update current language
    window.currentLanguage = lang;

    // Remove previous language scripts if they exist to prevent duplicates
    document.querySelectorAll(`script[src*="lang-${lang}.js"]`).forEach(el => el.remove());
    document.querySelectorAll(`script[src*="lang-wiki-${lang}.js"]`).forEach(el => el.remove());

    const script = document.createElement('script');
    // Use dynamic base path to find lang folder
    script.src = basePath + `lang/lang-${lang}.js`;

    script.onload = function () {
        console.log(`Language file loaded: ${lang}`);

        // Check if we are in the wiki section and load wiki-specific translations
        if (window.location.href.includes('/wiki/')) {
            const wikiScript = document.createElement('script');
            wikiScript.src = basePath + `lang/lang-wiki-${lang}.js`;

            wikiScript.onload = function () {
                console.log(`Wiki language file loaded: ${lang}`);
                setTimeout(() => applyLanguage(lang), 100);
            };

            wikiScript.onerror = function () {
                console.warn(`Wiki language file not found for: ${lang}, applying main translations only`);
                setTimeout(() => applyLanguage(lang), 100);
            };

            document.head.appendChild(wikiScript);
        } else {
            // Give more time for the script to fully load
            setTimeout(() => applyLanguage(lang), 100);
        }
    };

    script.onerror = function () {
        console.error(`Failed to load language file: ${lang}`);
        // Fallback to English if the language fails to load
        if (lang !== 'en') {
            loadLanguage('en');
        }
    };

    document.head.appendChild(script);
}

function applyLanguage(lang) {
    const dict = window['LANG_' + lang.toUpperCase()];

    if (!dict) {
        console.error(`Language dictionary not found for: ${lang}`);
        return;
    }

    console.log(`Applying language: ${lang}, Dictionary keys: ${Object.keys(dict).length}`);

    let translatedCount = 0;

    for (let key in dict) {
        const element = document.getElementById(key);
        if (element) {
            // Check if the translation string contains HTML tags
            const hasTags = /<[a-z][\s\S]*>/i.test(dict[key]);

            if (hasTags) {
                // If translation contains HTML (like <strong>, <code>), we MUST use innerHTML
                // This assumes the translation provides the full structure needed
                element.innerHTML = dict[key];
            } else {
                // If translation is plain text, try to preserve existing structure (like icons)
                if (element.children.length > 0) {
                    // Find and replace only text nodes to preserve icons
                    let textNode = null;
                    for (let child of element.childNodes) {
                        if (child.nodeType === Node.TEXT_NODE && child.nodeValue.trim()) {
                            textNode = child;
                            break;
                        }
                    }

                    if (textNode) {
                        textNode.nodeValue = dict[key];
                    } else {
                        // Fallback: if no text node found, just append? 
                        // Or force innerHTML if we can't find where to put text?
                        // For now, let's use textContent which might wipe icons but ensures text is seen
                        // element.textContent = dict[key]; 
                        // Better: do nothing or log warning? 
                        // Let's try setting textContent as fallback, user will report if icons disappear
                        // But better to be safe for Main UI:
                        // If we can't find a text node, maybe it's an empty button waiting for text?
                        element.textContent = dict[key];
                    }
                } else {
                    // No children, safe to use innerHTML (or textContent)
                    element.innerHTML = dict[key];
                }
            }

            // Handle common attributes
            if (element.hasAttribute('alt')) {
                element.setAttribute('alt', dict[key]);
            }
            if (element.hasAttribute('placeholder')) {
                element.setAttribute('placeholder', dict[key]);
            }
            if (element.hasAttribute('title')) {
                element.setAttribute('title', dict[key]);
            }

            translatedCount++;
        } else {
            // Only warn for non-special keys
            if (!key.endsWith('Alt') && !key.endsWith('Caption') && !key.startsWith('_')) {
                console.warn(`Element not found for key: ${key}`);
            }
        }
    }

    console.log(`Translated ${translatedCount} elements for language: ${lang}`);
}
