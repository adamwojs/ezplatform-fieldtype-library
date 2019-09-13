(function (global, doc, eZ, $) {
    const AUTOCOMPLETE_INPUT = '[data-autocomplete-provider]';
    const AUTOCOMPLETE_DELAY = 250;
    const AUTOCOMPLETE_LIMIT = 25;
    const AUTOCOMPLETE_ENDPOINT = '/api/ezp/v2/fieldtypes/{identifier}/autocomplete';

    const token = doc.querySelector('meta[name="CSRF-Token"]').content;
    const siteaccess = doc.querySelector('meta[name="SiteAccess"]').content;

    doc.querySelectorAll(`${AUTOCOMPLETE_INPUT}`).forEach((input) => {
        $(input).select2({
            minimumInputLength: 3,
            ajax: {
                delay: AUTOCOMPLETE_DELAY,
                url: AUTOCOMPLETE_ENDPOINT.replace('{identifier}', input.dataset.autocompleteProvider),
                dataType: 'json',
                cache: true,
                transport: (params, success, failure) => {
                    const page = params.data.page || 1;
                    const term = params.data.term || null;

                    const request = new Request(params.url + `?page=${page}&term=${term}`, {
                        method: params.type,
                        mode: 'same-origin',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/vnd.ez.api.ChoiceList+json',
                            'X-Siteaccess': siteaccess,
                            'X-CSRF-Token': token,
                        }
                    });

                    fetch(request)
                        .then((response) => response.json())
                        .then(success)
                        .catch(failure);
                },
                processResults: (data) => {
                    const items = data.ChoiceList.items.map((item) => {
                        return {'id': item.value, 'text': item.label};
                    });

                    return {
                        results: items,
                        pagination: {
                            more: items.length === AUTOCOMPLETE_LIMIT
                        }
                    };
                }
            }
        });
    });
})(window, window.document, window.eZ, window.jQuery);
