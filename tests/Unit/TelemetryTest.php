<?php

use BitApps\WPTelemetry\Telemetry\Telemetry;

test('Should return a Report class instance', function () {
    $report = Telemetry::report();

    expect($report)->toBeInstanceOf(BitApps\WPTelemetry\Telemetry\Report\Report::class);
});

test('Should return a Feedback class instance', function () {
    $feedback = Telemetry::feedback();

    expect($feedback)->toBeInstanceOf(BitApps\WPTelemetry\Telemetry\Feedback\Feedback::class);
});

test('Should return a Telemetry version', function () {
    $version = Telemetry::getVersion();

    expect($version)->toMatch('/^\d+\.\d+\.\d+$/');
});
