// Simple logger wrapper. Control logging with window.APP_DEBUG (boolean).
// Default to true for easier debugging during development, but respect any pre-set value.
window.APP_DEBUG = (typeof window.APP_DEBUG !== 'undefined') ? window.APP_DEBUG : true;
window.Logger = {
    debug: function(...args) {
        if (window.APP_DEBUG) console.debug(...args);
    },
    log: function(...args) {
        if (window.APP_DEBUG) console.log(...args);
    },
    error: function(...args) {
        console.error(...args);
    }
};
