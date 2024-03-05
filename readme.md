# WP Telemetry

A simple telemetry library for WordPress. It allows you to send telemetry data to a remote server. It is designed to be simple and easy to use.

## Usage

### 1. Installation

Install the package using composer:

```bash
composer require bitapps/wp-telemetry
```

### 2. Create a Telemetry Client

Initialize the telemetry client in your plugin.

```php
function initialize_telemetry_client() {
  TelemetryConfig::setSlug($title);
  TelemetryConfig::setTitle($slug);
  TelemetryConfig::setPrefix($prefix);
  TelemetryConfig::setVersion($version);

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

### # Telemetry Client Config

Set custom server URL

```php
TelemetryConfig::setServerBaseUrl( 'https://example.com' );
```

Set custom terms URL

```php
TelemetryConfig::setTermsUrl( 'https://example.com/terms' );
```

Set custom privacy policy URL

```php
TelemetryConfig::setPolicyUrl( 'https://example.com/privacy' );
```

### # Tracking Report Config

Add plugin information in tracking data

```php
TelemetryConfig::report()
                ->addPluginData()
                ->init();
```

Add extra information in tracking data

```php
TelemetryConfig::report()
                ->addExtraInfo([
                  'my_plugin_logs' => Log::get(),
                ])
                ->init();
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
