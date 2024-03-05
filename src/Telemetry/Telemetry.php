<?php

namespace BitApps\WPTelemetry\Telemetry;

use BitApps\WPTelemetry\Telemetry\Feedback\Feedback;
use BitApps\WPTelemetry\Telemetry\Report\Report;

class Telemetry
{
    private static $report;

    private static $feedback;

    private static $version = '0.0.1';

    public static function getVersion()
    {
        return self::$version;
    }

    public static function report()
    {
        if (!self::$report) {
            self::$report = new Report();
        }

        return self::$report;
    }

    public static function feedback()
    {
        if (!self::$feedback) {
            self::$feedback = new Feedback();
        }

        return self::$feedback;
    }

    public static function view($fileName, $args)
    {
        load_template(\dirname(\dirname(__DIR__)) . '/src/views/' . $fileName . '.php', false, $args);
    }

    public static function sendReport($route, $data, $blocking = false)
    {
        $apiUrl = trailingslashit(TelemetryConfig::getServerBaseUrl()) . $route;

        $headers = [
            'host-user'    => 'BitApps/' . md5(esc_url(home_url())),
            'Content-Type' => 'application/json',
        ];

        return wp_remote_post(
            $apiUrl,
            [
                'method'      => 'POST',
                'timeout'     => 30,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => $blocking,
                'headers'     => $headers,
                'body'        => wp_json_encode(array_merge($data, ['wp_telemetry' => self::$version])),
                'cookies'     => [],
            ]
        );
    }
}
