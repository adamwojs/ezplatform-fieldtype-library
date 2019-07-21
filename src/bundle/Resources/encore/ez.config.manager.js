const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/vendors/select2/dist/js/select2.js'),
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
