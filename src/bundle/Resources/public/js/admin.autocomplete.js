(function (global, doc, eZ, $) {
    const AUTOCOMPLETE_INPUT = '[data-auto-complete="1"]';

    doc.querySelectorAll(`${AUTOCOMPLETE_INPUT}`).forEach((input) => {
        $(input).select2();
    });
})(window, window.document, window.eZ, window.jQuery);
