import IndexField from './components/GoogleAutocomplete/IndexField';
import DetailField from './components/GoogleAutocomplete/DetailField';
import FormField from './components/GoogleAutocomplete/FormField';

import MetaDataIndexField from './components/AddressMetadata/IndexField';
import MetaDataDetailField from './components/AddressMetadata/DetailField';
import MetaDataFormField from './components/AddressMetadata/FormField';

Nova.booting(app => {
    app.component('index-google-autocomplete', IndexField);
    app.component('detail-google-autocomplete', DetailField);
    app.component('form-google-autocomplete', FormField);
    app.component('index-address-metadata', MetaDataIndexField);
    app.component('detail-address-metadata', MetaDataDetailField);
    app.component('form-address-metadata', MetaDataFormField);
});
