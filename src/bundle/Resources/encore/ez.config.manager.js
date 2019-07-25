const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/vendors/select2/dist/js/select2.js'),
            path.resolve(__dirname, '../public/vendors/jquery-mask-plugin/dist/jquery.mask.js'),
            path.resolve(__dirname, '../public/js/admin.autocomplete.js'),
            path.resolve(__dirname, '../public/js/fieldType/ezmaskedtextline.js'),
        ]
    });

    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-css',
        newItems: [
            path.resolve(__dirname, '../public/vendors/select2/dist/css/select2.css'),
            path.resolve(__dirname, '../public/vendors/select2-bootstrap4-theme/dist/select2-bootstrap4.css'),
        ]
    });
};
