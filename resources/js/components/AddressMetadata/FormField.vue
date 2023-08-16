<template>
    <div :class="{ invisible: field.invisible }">
        <DefaultField
            :field="field"
            :errors="errors"
            :show-help-text="showHelpText"
            :full-width-content="fullWidthContent"
        >
            <template #field>
                <input
                    :id="field.name"
                    type="text"
                    :disabled="field.disabled"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.placeholder"
                    v-model="value"
                />
            </template>
        </DefaultField>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    mounted() {
        Nova.$on('address-metadata-clear', () => {
            this.value = '';
        });

        if (this.field.asJson) {
            Nova.$on('address-metadata-update', locationObject => {
                this.value = JSON.stringify(locationObject);
            });
        } else if (this.field.addressValue.indexOf('{{') >= 0) {
            let bracketRegex = /\{\{.*?\}\}/g;
            let match;
            let addressParts = [];

            do {
                match = bracketRegex.exec(this.field.addressValue);
                if (match !== null) {
                    let addressValue = match[0]
                        .replace('{{', '')
                        .replace('}}', '')
                        .trim();
                    addressParts.push(addressValue);
                }
            } while (match !== null);

            Nova.$on('address-metadata-update', locationObject => {
                if (this.checkIfFlexibleField(locationObject.attribute)) {
                    return;
                }

                let addressValue = this.field.addressValue;

                for (let i = 0; i < addressParts.length; i++) {
                    let part = addressParts[i];
                    addressValue = addressValue
                        .replace(`{{ ${part} }}`, locationObject[part])
                        .replace(`{{${part} }}`, locationObject[part] || '')
                        .replace(`{{ ${part}}}`, locationObject[part] || '')
                        .replace(`{{${part}}}`, locationObject[part] || '')
                        .trim();
                }

                this.value = addressValue;
            });
        } else {
            Nova.$on('address-metadata-update', locationObject => {
                if (this.checkIfFlexibleField(locationObject.attribute)) {
                    return;
                }

                this.value = locationObject[this.field.addressValue];
            });
        }
    },
    methods: {
        setInitialValue() {
            this.value = this.field.value || '';
        },
        fill(formData) {
            formData.append(this.field.attribute, this.value || '');
        },
        handleChange(value) {
            this.value = value;
        },
        checkIfFlexibleField(attributeName) {
            const flexibleFieldKey = this.field.attribute.match(/((.{16})__)/);

            // if the flexible field key exists but doesn't match the first 16 characters
            // of the location object attribute, return to not set the value on this field
            return flexibleFieldKey && attributeName.indexOf(flexibleFieldKey[0]) === -1;
        },
    },
};
</script>

<style>
.invisible {
    visibility: hidden;
    position: absolute;
}
</style>
