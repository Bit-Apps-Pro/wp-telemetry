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
function initialize_telemetry_client()
{
  TelemetryConfig::setSlug($title);
  TelemetryConfig::setTitle($slug);
  TelemetryConfig::setPrefix($prefix);
  TelemetryConfig::setVersion($version);

  TelemetryConfig::setServerBaseUrl( 'https://api.example.com/' );
  TelemetryConfig::setTermsUrl( 'https://example.com/terms/' ); // (optional)
  TelemetryConfig::setPolicyUrl( 'https://example.com/privacy/' ); // (optional)

  // initialize tracking and reporting
  Telemetry::report()->init();

  // initialize deactivation feedback survey
  Telemetry::feedback()->init();
}

initialize_telemetry_client();
```

**You are good to go! 👍️**

The telemetry client will start sending data to your configured api base url.

## Configuration

### # Activate/Deactivate Telemetry Tracking

You can add a setting in your plugin settings page to allow users to opt-in or opt-out of telemetry tracking. You can use the following methods to change the opt-in/opt-out status.

**⚡️ Opt In :**

```php
Telemetry::report()->trackingOptIn();
```

**⚡️ Opt Out :**

```php
Telemetry::report()->trackingOptOut();
```

### # Tracking Report Modify

**⚡️ Filter Hook to Add Additional Data :**

This filter allows adding additional data to track information used by the plugin. You can modify the `additional_data` array to include any custom data needed.

```php
apply_filters($plugin_prefix . 'telemetry_additional_data', $additional_data);
```

**Usage**

```php
add_filter($plugin_prefix . 'telemetry_additional_data', 'customize_additional_data', 10, 1);

function customize_additional_data($additional_data)
{
  // Do your stuffs here
  return $additional_data;
}
```

**⚡️ Filter Hook To Modify Telemetry Data :**

This filter allows modification of the telemetry data array before it is sent.

```php
apply_filters($plugin_prefix . 'telemetry_data', $telemetry_data);
```

**Usage**

```php
add_filter($plugin_prefix . 'telemetry_data', 'customize_telemetry_data', 10, 1);

function customize_telemetry_data($telemetry_data)
{
  // Do your stuffs here
  return $telemetry_data;
}
```

**⚡️ Add plugin information in tracking data**

```php
TelemetryConfig::report()
                ->addPluginData()
                ->init();
```

### # Deactivation Feedback Survey

**⚡️ Filter Hook to Add Deactivate Reasons :**

This filter allows adding additional deactivate reasons to the feedback survey. You can modify the `deactivate_reasons` array to include any custom reasons needed.

```php
apply_filters($plugin_prefix . 'deactivate_reasons', $deactivate_reasons);
```

**Usage**

```php

add_filter($plugin_prefix . 'deactivate_reasons', 'add_deactivate_reasons', 10, 1);

function add_deactivate_reasons($deactivate_reasons)
{
  // example of adding a custom deactivate reason
  $deactivate_reasons[] = [
    'title' => 'What could we have done to improve your experience?',
    'placeholder' => 'Please provide your feedback here',
  ];

  return $deactivate_reasons;
}
```
