<template>
    <div :class="{ invisible: field.invisible }">
        <DefaultField :field="field">
            <template #field>
                <input
                    :id="field.name"
                    type="text"
                    :disabled="field.disabled"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="value"
                />
                <p v-if="hasError" class="help-text error-text mt-2 text-danger">
                    {{ firstError }}
                </p>
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
         if (this.field.captureResponse) {
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
    },
};
</script>

<style>
.invisible {
    visibility: hidden;
    position: absolute;
}
</style>
