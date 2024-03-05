<?php

use BitApps\WPTelemetry\Telemetry\TelemetryConfig;

test('Telemetry config setter and getter', function () {
    TelemetryConfig::setTitle('Test title');
    TelemetryConfig::setSlug('test-slug');
    TelemetryConfig::setPrefix('test_prefix_');
    TelemetryConfig::setVersion('0.0.1');
    TelemetryConfig::setServerBaseUrl('http://localhost:8000');
    TelemetryConfig::setTermsUrl('http://localhost:8000/terms');
    TelemetryConfig::setPolicyUrl('http://localhost:8000/policy');

    expect(TelemetryConfig::getTitle())->toBe('Test title');
    expect(TelemetryConfig::getSlug())->toBe('test-slug');
    expect(TelemetryConfig::getPrefix())->toBe('test_prefix_');
    expect(TelemetryConfig::getVersion())->toBe('0.0.1');
    expect(TelemetryConfig::getServerBaseUrl())->toBe('http://localhost:8000');
    expect(TelemetryConfig::getTermsUrl())->toBe('http://localhost:8000/terms');
    expect(TelemetryConfig::getPolicyUrl())->toBe('http://localhost:8000/policy');
});
