<?php

namespace YieldStudio\NovaGoogleAutocomplete;

use Laravel\Nova\Fields\Field;

class GoogleAutocomplete extends Field
{
    public $component = 'google-autocomplete';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'addressObject' => [],
        ]);

        $this->withMeta([
            'type' => '',
        ]);
    }

    public function countries(string|array $list): self
    {
        return $this->withMeta([
            'countries' => $list,
        ]);
    }

    /**
     * Pass a place type
     * https://developers.google.com/places/supported_types#table3
     */
    public function placeType(string $type): self
    {
        return $this->withMeta([
            'type' => $type,
        ]);
    }

    /**
     * Specify the extra address object fields you need from address response.
     */
    public function withValues(array $data): self
    {
        $currentObject = $this->meta['addressObject'];

        return $this->withMeta([
            'addressObject' => array_merge($currentObject, $data),
        ]);
    }
}
