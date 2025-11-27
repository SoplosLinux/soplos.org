// Language selector
document.addEventListener('DOMContentLoaded', function () {
    const selectedLang = document.querySelector('.selected-language');
    const dropdown = document.querySelector('.language-dropdown');
    const languageOptions = document.querySelectorAll('.language-dropdown a');

    // Toggle dropdown
    if (selectedLang && dropdown) {
        selectedLang.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', function () {
            dropdown.classList.remove('show');
        });
    }

    // Language selection
    languageOptions.forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');

            // Update selector visual
            updateLanguageUI(lang);

            dropdown.classList.remove('show');

            // Save language preference
            localStorage.setItem('soplosLanguage', lang);

            // Load and apply language
            loadLanguage(lang);
        });
    });

    // Function to update the visual language selector
    function updateLanguageUI(lang) {
        const flagImg = selectedLang.querySelector('img');
        const langText = selectedLang.querySelector('span');

        if (flagImg && langText) {
            const countryCode = lang === 'en' ? 'gb' : lang;
            flagImg.src = `https://flagcdn.com/w20/${countryCode}.png`;
            langText.textContent = lang.toUpperCase();
        }

        // Remove active class from all options and add to current
        languageOptions.forEach(opt => {
            if (opt.getAttribute('data-lang') === lang) {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });
    }

    // Initialize with saved language or default to English
    const savedLang = localStorage.getItem('soplosLanguage') || 'en';
    updateLanguageUI(savedLang);
    loadLanguage(savedLang);
});
