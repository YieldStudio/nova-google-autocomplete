<?php

namespace YieldStudio\NovaGoogleAutocomplete;

use Laravel\Nova\Fields\Text;

class AddressMetadata extends Text
{
    public $component = 'address-metadata';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'captureResponse' => false,
            'disabled' => false,
            'invisible' => false,
        ]);
    }

    public function fromResponse(bool $captureResponse = true): self
    {
        return $this->withMeta(['captureResponse' => $captureResponse]);
    }

    public function fromValue(string $addressValue): self
    {
        return $this->withMeta(['addressValue' => $addressValue]);
    }

    public function disabled(): self
    {
        return $this->withMeta(['disabled' => true]);
    }

    public function invisible(): self
    {
        return $this->withMeta(['invisible' => true]);
    }
}
