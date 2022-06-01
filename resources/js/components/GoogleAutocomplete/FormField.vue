<template>
    <DefaultField :field="field">
        <template #field>
            <div class="form-group">
                <vue-google-autocomplete
                    ref="address"
                    :id="field.attribute"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :country="field.countries"
                    :types="field.type"
                    :placeholder="value.trim().length === 0 ? field.name : __('update_address')"
                    v-model="value"
                    v-on:keypress.enter.prevent=""
                    v-on:placechanged="getAddressData"
                />
            </div>
            <p v-if="hasError" class="help-text error-text mt-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import VueGoogleAutocomplete from 'vue-google-autocomplete';

export default {
    components: { VueGoogleAutocomplete },
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],

    data: function() {
        return {
            address: '',
        };
    },
    methods: {
        getAddressData(addressData, placeResultData) {
            // Save current data address as a string
            this.handleChange(placeResultData.formatted_address);

            const retrievedAddress = {};

            // Emmit events to by catch up for the other AddressMetadata fields
            this.field.addressObject.forEach(element => {
                if (element.indexOf('.') < 0) {
                    if (addressData.hasOwnProperty(element)) {
                        retrievedAddress[element] = addressData[element];
                    }
                    if (placeResultData.hasOwnProperty(element)) {
                        retrievedAddress[element] = placeResultData[element];
                    }
                } else {
                    // Separates the type
                    let value = element.split('.')[0];
                    let type = element.split('.')[1]; // long_name or short_name

                    for (let i = 0; i < placeResultData.address_components.length; i++) {
                        let target = placeResultData.address_components[i];

                        if (target.types.includes(value)) {
                            retrievedAddress[value] = target[type];
                        }
                    }
                }
            });

            Nova.$emit('address-metadata-update', { ...retrievedAddress });
        },
        setInitialValue() {
            this.value = this.field.value || '';
        },
        fill(formData) {
            formData.append(this.field.attribute, this.value || '');
        },
        handleChange(value) {
            this.value = value;
        },
    },
};
</script>
