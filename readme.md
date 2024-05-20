# WP Telemetry

A simple telemetry library for WordPress. It allows you to send telemetry data to a remote server. It is designed to be simple and easy to use.

## Usage

### 1. Installation

Install the package using composer:

```bash
composer require bitapps/wp-telemetry
```

### 2. Create a Telemetry Client

Initialize the telemetry client in your plugin's bootstrap file.

```php
function initialize_telemetry_client() {
  TelemetryConfig::setSlug($title);
  TelemetryConfig::setTitle($slug);
  TelemetryConfig::setPrefix($prefix);
  TelemetryConfig::setVersion($version);
  TelemetryConfig::setServerBaseUrl( 'https://api.example.com/' );

  TelemetryConfig::setTermsUrl( 'https://example.com/terms' ); // optional
  TelemetryConfig::setPolicyUrl( 'https://example.com/privacy' ); // optional

  // initialize tracking and reporting
  Telemetry::report()->init();

  // initialize deactivation feedback survey
  Telemetry::feedback()->init();
}

initialize_telemetry_client();
```

You are good to go! The telemetry client will start sending data to the default server.

## Configuration

All the configuration should be done in the `initialize_telemetry_client()` function.

### # Tracking Report Modify

Add plugin information in tracking data

```php
TelemetryConfig::report()
                ->addPluginData()
                ->init();
```

**Filter Hook to Add Additional Data :**

This filter allows adding additional data to track information used by the plugin. You can modify the `additional_data` array to include any custom data needed.

```php
$plugin_prefix = 'my_plugin_prefix_';

add_filter($plugin_prefix . 'telemetry_additional_data', function($additional_data) {

  // example: add custom data
  $additional_data['my_custom_data'] = 'My Custom Data';

  return $additional_data;
});
```

**Filter Hook To Modify Telemetry Data :**

This filter allows modification of the telemetry data array before it is sent.

```php
$plugin_prefix = 'my_plugin_prefix_';

add_filter($plugin_prefix . 'telemetry_data', function($telemetry_data) {

  // example: remove some data
  unset($telemetry_data['some_data']);

  // example: add custom data
  $telemetry_data['my_custom_data'] = 'My Custom Data';

  return $telemetry_data;
});
```

### # Deactivate Feedback Config

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
