# Soplos.org — Official Website

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![GitHub Pages](https://img.shields.io/badge/GitHub%20Pages-Live-brightgreen)](https://soploslinux.github.io/soplos.org/)

This repository contains the official website and documentation for **Soplos Linux**, a modern, user-friendly Linux distribution based on Debian Testing.

🌐 **Live Site**: [soploslinux.github.io/soplos.org](https://soploslinux.github.io/soplos.org/)

---

## 📖 Table of Contents

- [About](#about)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
  - [Translation Contributions](#-translation-contributions)
  - [Reporting Issues](#-reporting-issues)
  - [Code Contributions](#code-contributions)
- [Development](#development)
- [License](#license)

---

## About

Soplos.org serves as the central hub for:
- Product information about Soplos Linux distributions (Tyron, Tyson, Boro)
- Comprehensive wiki documentation
- Download links and installation guides
- Community resources and support

The website is fully static (HTML/CSS/JavaScript) and supports **8 languages**: English, Spanish, German, French, Italian, Portuguese, Romanian, and Russian.

---

## 📁 Project Structure

```
soplos.org/
├── index.html              # Homepage
├── wiki/                   # Documentation pages
│   ├── install/           # Installation guide
│   ├── start/             # Getting started guide
│   ├── faq/               # Frequently asked questions
│   └── tyron/             # Tyron (XFCE) guide
├── js/
│   ├── lang/              # Translation files
│   │   ├── lang-en.js    # English (main site)
│   │   ├── lang-es.js    # Spanish (main site)
│   │   ├── lang-wiki-en.js  # English (wiki)
│   │   ├── lang-wiki-es.js  # Spanish (wiki)
│   │   └── ...           # Other languages
│   └── language-switcher.js  # Translation system
├── styles/                # CSS files
├── images/                # Screenshots and graphics
└── legal/                 # Privacy policy and terms

```

---

## Contributing

We welcome contributions from the community! Whether you want to add a new language, fix typos, or improve documentation, your help is appreciated.

### 🌍 Translation Contributions

**Currently Supported Languages:**
- 🇬🇧 English (en)
- 🇪🇸 Spanish (es)
- 🇩🇪 German (de)
- 🇫🇷 French (fr)
- 🇮🇹 Italian (it)
- 🇵🇹 Portuguese (pt)
- 🇷🇴 Romanian (ro)
- 🇷🇺 Russian (ru)

#### Adding a New Language

1. **Fork this repository** and clone it locally
2. **Create language files** in `js/lang/`:
   - `lang-XX.js` (for main site translations)
   - `lang-wiki-XX.js` (for wiki translations)
3. **Use English as reference**:
   - Copy `js/lang/lang-en.js` → `js/lang/lang-XX.js`
   - Copy `js/lang/lang-wiki-en.js` → `js/lang/lang-wiki-XX.js`
4. **Translate all keys** while keeping the same structure:
   ```javascript
   Object.assign(window.LANG_XX, {
       "key-name": "Translated text",
       "another-key": "More translated text",
       // ...
   });
   ```
5. **Important**: Ensure your files have **exactly the same number of lines** as the English reference files
6. **Test locally** (see [Development](#development))
7. **Submit a Pull Request** with your translations

#### Improving Existing Translations

Found a typo or want to improve a translation?

1. Navigate to `js/lang/lang-XX.js` or `js/lang/lang-wiki-XX.js`
2. Make your changes
3. Submit a Pull Request with a clear description

**Translation Guidelines:**
- Maintain consistent terminology throughout
- Keep HTML tags and special characters (`<strong>`, `<code>`, etc.) intact
- Preserve line breaks (`\n`) and formatting
- Do not translate:
  - Product names (Soplos, Tyron, Tyson, Boro)
  - Technical commands (e.g., `sudo apt install`)
  - File paths and URLs

### 🐛 Reporting Issues

Found a bug, broken link, or incorrect documentation? Please [open an issue](https://github.com/SoplosLinux/soplos.org/issues/new) with:

- **Clear title** describing the problem
- **URL** or page where the issue occurs
- **Screenshots** (if applicable)
- **Expected vs actual behavior**
- **Browser and language** you're using

**Common issue types:**
- Translation errors or typos
- Broken links
- Outdated documentation
- Display/layout problems
- Accessibility concerns

### Code Contributions

For non-translation code contributions:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/improvement-name`)
3. Make your changes
4. Test thoroughly
5. Commit with clear messages (`git commit -m "Fix: navigation menu on mobile"`)
6. Push to your fork (`git push origin feature/improvement-name`)
7. Open a Pull Request

---

## Development

### Local Preview

No build step required! Just serve the files:

```bash
cd soplos.org
python3 -m http.server 8000
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

### File Consistency Checks

Before submitting translations, verify line counts match:

```bash
wc -l js/lang/lang-*.js
wc -l js/lang/lang-wiki-*.js
```

All main site files should have the same line count, and all wiki files should have the same line count.

### Testing Translations

1. Open the local site
2. Use the language selector in the top navigation
3. Navigate through different pages to ensure all text appears correctly
4. Check for:
   - Missing translations (English text in other languages)
   - Broken layouts due to long text
   - Proper character encoding (accents, special characters)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🔗 Links

- **Official Website**: [soplos.org](https://soploslinux.github.io/soplos.org/)
- **GitHub Organization**: [@SoplosLinux](https://github.com/SoplosLinux)
- **Issue Tracker**: [Report Issues](https://github.com/SoplosLinux/soplos.org/issues)
- **Pull Requests**: [Contribute](https://github.com/SoplosLinux/soplos.org/pulls)

---

## 💬 Support

Need help or have questions?

- 📧 Open an [issue](https://github.com/SoplosLinux/soplos.org/issues)
- 💬 Join our community forums (link in website)
- 🐦 Follow us on social media (links on website)

---

**Thank you for contributing to Soplos Linux!** 🎉
