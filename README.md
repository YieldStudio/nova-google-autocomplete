# Nova Google AutoComplete Field Package

[![Latest Version](https://img.shields.io/github/release/yieldstudio/nova-google-autocomplete?style=flat-square)](https://github.com/yieldstudio/nova-google-autocomplete/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/yieldstudio/nova-google-autocomplete?style=flat-square)](https://packagist.org/packages/yieldstudio/nova-google-autocomplete)

This field allows you to work with Google Places API to autocomplete on user input and get the full real address with all the metadata (like latitude and longitude).

Fork from [emilianotisato/nova-google-autocomplete-field](https://github.com/emilianotisato/nova-google-autocomplete-field) to maintain package.

## Installation

You can install the package in to a Laravel app that uses Nova via composer:

```bash
composer require yieldstudio/nova-google-autocomplete
```

Now publish config and localization files:

```shell
php artisan vendor:publish --provider="YieldStudio\NovaGoogleAutocomplete\FieldServiceProvider"
```

Create an app and enable Places API and create credentials to get your API key
[https://console.developers.google.com](https://console.developers.google.com)

Add the below to your `.env` file

```shell
NOVA_GOOGLE_AUTOCOMPLETE_API_KEY=############################
```

## Usage

Add the use declaration to your resource and use the fields:

```php
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;
// ....

GoogleAutocomplete::make('Address'),

//You can add a country or countries to autocomplete or leave empty for all.

// Specify a single country
GoogleAutocomplete::make('Address')
          ->countries('US'),

// Specify multiple countries [array]
GoogleAutocomplete::make('Address')
          ->countries(['US','AU']),
```

You can access other parameter like `latitude, longitude, street_number, route, locality, administrative_area_level_1, country, postal_code`, along with everything available in the - every field present in the [PlaceResult object](https://developers.google.com/maps/documentation/javascript/reference/#PlaceResult)

```php
use YieldStudio\NovaGoogleAutocomplete\AddressMetadata;
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

// Now this address field will search and store the address as a string, but also made available the values in the withValues array
GoogleAutocomplete::make('Address')->withValues(['latitude', 'longitude']),

// And you can store the values by autocomplete like this
AddressMetadata::make('lat')->fromValue('latitude'),
AddressMetadata::make('long')->fromValue('longitude'),

// You can disable the field so the user can't edit the metadata
AddressMetadata::make('long')->fromValue('longitude')->disabled(),

// Or you can make the field invisible in the form but collect the data anyways
AddressMetadata::make('long')->fromValue('longitude')->invisible(),
```

By default, the formatted address will be stored on the property provided in the GoogleAutocomplete field. If you don't want to store it, you can use the `dontStore` method:


```php
use YieldStudio\NovaGoogleAutocomplete\AddressMetadata;
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

// Formatted address will not be stored
GoogleAutocomplete::make('Address')->withValues(['latitude', 'longitude'])->dontStore(),

// This field will be stored
AddressMetadata::make('lat')->fromValue('latitude'),
AddressMetadata::make('long')->fromValue('longitude'),
```

### Combine Values

If you want to concatenate certain elements of the geocoded object that is returned by Google, using `{{` and `}}`, wrap the key like you would above; like so:

```php
use YieldStudio\NovaGoogleAutocomplete\AddressMetadata;
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

GoogleAutocomplete::make('Address')->withValues(['latitude', 'longitude']),

AddressMetadata::make('coordinates')->fromValue('{{latitude}}, {{longitude}}'),
```

So the value that would be rendered within the coordinates input would be something like:

```
39.3315476, -94.9363912
```

### Define Short/Long Value

If you would like to use the **long_name** version of the geocoded object (Kansas versus KS), you can define the `GoogleAutocomplete` field values with dot notation followed with the name version you want to use; like so:

```php
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

GoogleAutocomplete::make('Address')
    ->withValues([
        'route.short_name',
        'administrative_area_level_1.long_name',
    ]),
```

Which would return:

**route:** W 143rd St

**administrative_area_level_1:** Kansas

You can change the type of places that are returned by the autocomplete using the placeType() method.  You can use any of the values listed at [https://developers.google.com/places/supported_types#table3](https://developers.google.com/places/supported_types#table3)

```php
use YieldStudio\NovaGoogleAutocomplete\AddressMetadata;
use YieldStudio\NovaGoogleAutocomplete\GoogleAutocomplete;

// This autocomplete field will return results that match a business name instead of address.
// All the same address data is still stored.
GoogleAutocomplete::make('Address')->placeType('establishment');
```

### Capturing all values as JSON

If you want to capture all requested values as a JSON object, you can use the `fromValuesAsJson()` helper instead of using `fromValue()`.

```php
// Autocomplete field
GoogleAutocomplete::make('Location')
    ->countries('BE')
    ->withValues(['latitude', 'longitude', 'street_number', 'route', 'locality', 'administrative_area_level_1', 'country', 'postal_code']),

// Field that will capture de response object
AddressMetadata::make('Address')
    ->fromValuesAsJson()
    ->invisible()
    ->onlyOnForms(),

// Display the response object in a Code field
Code::make('Address')->json()->onlyOnDetail(),
```

## Localization

If you want this package in your language, just create a json lang file in your `resources/lang/vendor/nova-google-autocomplete` folder.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [contact@yieldstudio.fr](mailto:contact@yieldstudio.fr) instead of using the issue tracker.

## Credits

- [Emiliano Tisato](https://github.com/emilianotisato) - Original developer
- [James Hemery](https://github.com/jameshemery) - Fork maintainer
- [Simon Depelchin](https://github.com/depsimon) - Contributor

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
