# WP Telemetry

A simple telemetry library for WordPress.It allows you to send telemetry data to a remote server. It is designed to be simple and easy to use.

## Usage

### 1. Install the Package

Install the package using composer:

```bash
composer require bitapps/wp-telemetry
```

### 2. Create a Telemetry Client

Initialize the telemetry client in your plugin.

```php
use BitApps\Telemetry\Telemetry;

function initialize_telemetry_client() {
  $telemetryClient = new Client( $name, $slug, $prefix, $version );

  // initialize tracking and reporting
  $telemetryClient->report();

  // initialize deactivation feedback survey
  $telemetryClient->feedback();
}

initialize_telemetry_client();
```

## Configuration

### # Client

Config the server URL

```php
$telemetryClient->setServerUrl( 'https://example.com' );
```

Config the terms URL

```php
$telemetryClient->setTermsUrl( 'https://example.com/terms' );
```

Config the privacy policy URL

```php
$telemetryClient->setPolicyUrl( 'https://example.com/privacy' );
```

Config the plugin logo

```php
$telemetryClient->setLogo( 'https://example.com/logo.svg' );
```

### # Report

Add plugin information in tracking data

```php
$telemetryClient->report()->addPluginData();
```

Add extra information in tracking data

```php
$telemetryClient->report()
                ->addExtraInfo([
                  'my_plugin_logs' => Log::get(),
                ]);
```

### # Feedback

You can customize the feedback survey by adding questions using `add_filter()`

- **title** - The title of the question
- **placeholder** - The input placeholder of the question (optional)

```php
$prefix = 'my_plugin_prefix_';

add_filter($prefix . 'deactivate_reasons', function ($default_reasons) {

  $default_reasons['my_custom_reason'] = [
    'title'       => 'My Custom Reason',
    'placeholder' => 'Please specify the reason',
  ]

  $default_reasons['my_custom_reason_2'] = [
    'title'       => 'My Custom Reason 2',
    'placeholder' => '',
  ]

  return $default_reasons;
});

```
