<?php

namespace BitApps\WPTelemetry\Telemetry;

use BitApps\WPTelemetry\Telemetry\Feedback\Feedback;
use BitApps\WPTelemetry\Telemetry\Report\Report;

class Client
{
    public $report;

    public $feedback;

    public $title;

    public $slug;

    public $prefix;

    public $version;

    public $telemetryVersion = '0.1.0';

    public $termsUrl = 'https://bitapps.pro/terms';

    public $policyUrl = 'https://bitapps.pro/privacy-policy';

    public $apiBaseUrl = 'https://wp-api.bitapps.pro/public/';

    public function __construct($title, $slug, $prefix, $version)
    {
        $this->title = $title;

        $this->slug = $slug;

        $this->prefix = $prefix;

        $this->version = $version;
    }

    public function report()
    {
        if (!$this->report) {
            $this->report = new Report($this);
        }

        return $this->report;
    }

    public function feedback()
    {
        if (!$this->feedback) {
            $this->feedback = new Feedback($this);
        }

        return $this->feedback;
    }

    public function setTermsUrl($url)
    {
        $this->termsUrl = $url;
    }

    public function setPolicyUrl($url)
    {
        $this->policyUrl = $url;
    }

    public function setServerUrl($url)
    {
        $this->apiBaseUrl = trailingslashit($url);
    }

    public function view($fileName, $args)
    {
        load_template(\dirname(\dirname(__DIR__)) . '/src/views/' . $fileName . '.php', false, $args);
    }

    public function sendReport($route, $data, $blocking = false)
    {
        $apiUrl = $this->apiBaseUrl . $route;

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
                'body'        => wp_json_encode(array_merge($data, ['wp_telemetry' => $this->telemetryVersion])),
                'cookies'     => [],
            ]
        );
    }
}
