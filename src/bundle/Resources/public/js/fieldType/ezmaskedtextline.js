(function (global, doc, eZ, $) {
    const SELECTOR_FIELD = '.ez-field-edit--ezmaskedtextline';
    const MASK_PLACEHOLDERS = {
        '0': {pattern: /[0-9]/},
        '9': {pattern: /[0-9]/, optional: true},
        'L': {pattern: /[A-Za-z]/},
        '?': {pattern: /[A-Za-z]/, optional: true},
        'A': {pattern: /[A-Za-z0-9]/},
        'a': {pattern: /[A-Za-z0-9]/, optional: true},
        'C': {pattern: /[A-Za-z0-9_]/},
        'c': {pattern: /[A-Za-z0-9_]/, optional: true},
        'X': {pattern: /[0-9A-Fa-f]/},
        'x': {pattern: /[0-9A-Fa-f]/, optional: true},
    };

    class EzMaskedTextLineValidator extends eZ.BaseFieldValidator {
        validateInput(event) {
            const isRequired = event.target.required;
            const isEmpty = !event.target.value;
            const isError = (isEmpty && isRequired);
            const label = event.target.closest(SELECTOR_FIELD).querySelector('.ez-field-edit__label').innerHTML;
            const result = {isError};

            if (isEmpty) {
                result.errorMessage = eZ.errors.emptyField.replace('{fieldName}', label);
            }

            return result;
        }
    }

    const validator = new EzMaskedTextLineValidator({
        classInvalid: 'is-invalid',
        fieldSelector: SELECTOR_FIELD,
        eventsMap: [
            {
                selector: '.ez-field-edit--ezmaskedtextline input',
                eventName: 'blur',
                callback: 'validateInput',
                errorNodeSelectors: ['.ez-field-edit__label-wrapper'],
                invalidStateSelectors: ['.ez-data-source__input'],
            },
        ],
    });

    validator.init();

    doc.querySelectorAll(`${SELECTOR_FIELD} input`).forEach((input) => {
        $(input).mask(input.dataset.inputMask, {
            'translation': MASK_PLACEHOLDERS
        })
    });

    eZ.addConfig('fieldTypeValidators', [validator], true);
})(window, window.document, window.eZ, window.jQuery);
