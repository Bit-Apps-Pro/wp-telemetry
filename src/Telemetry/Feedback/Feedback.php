<?php

namespace BitApps\WPTelemetry\Telemetry\Feedback;

use BitApps\WPTelemetry\Telemetry\Client;

class Feedback
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->init();
    }

    public function init()
    {
        add_action('wp_ajax_' . $this->client->prefix . 'deactivate_feedback', [$this, 'handleDeactivateFeedback']);

        add_action('current_screen', [$this, 'loadAllScripts']);
    }

    public function loadAllScripts()
    {
        if (!$this->is_plugins_screen()) {
            return;
        }

        add_action('admin_enqueue_scripts', [$this, 'enqueueFeedbackDialogScripts']);
    }

    /**
     * Enqueue feedback dialog scripts.
     *
     * Registers the feedback dialog scripts and enqueues them.
     *
     * @since 0.0.1
     */
    public function enqueueFeedbackDialogScripts()
    {
        add_action('admin_footer', [$this, 'printDeactivateFeedbackDialog']);

        $cssFilePath = $this->getAssetPath() . 'resources/css/deactivateModalStyle.css';
        wp_register_style($this->client->prefix . 'deactivate_modal', $cssFilePath, [], $this->client->version);
        wp_enqueue_style($this->client->prefix . 'deactivate_modal');
    }

    public static function getAssetPath()
    {
        return plugin_dir_url(\dirname(__DIR__));
    }

    /**
     * Print deactivate feedback dialog.
     *
     * Display a dialog box to ask the user why he deactivated this plugin.
     *
     * @since 0.0.1
     */
    public function printDeactivateFeedbackDialog()
    {
        $this->client->view('deactivateModal', [
            'slug'    => $this->client->slug,
            'prefix'  => $this->client->prefix,
            'title'   => $this->client->title,
            'logo'    => $this->client->logo,
            'reasons' => $this->getDeactivateReasons(),
        ]);
    }

    public function getDeactivateReasons()
    {
        $reasons = [
            'found_a_better_plugin' => [
                'title'       => esc_html__('Found a better plugin', $this->client->slug),
                'placeholder' => esc_html__('Which plugin?', $this->client->slug),
            ],
            'missing_specific_feature' => [
                'title'       => esc_html__('Missing a specific featureMissing a specific featureMissing a specific feature featureMissing a specific feature', $this->client->slug),
                'placeholder' => esc_html__('Could you tell us more about that feature?', $this->client->slug),
            ],
            'not_working' => [
                'title'       => esc_html__('Not working', $this->client->slug),
                'placeholder' => esc_html__('Could you tell us what is not working?', $this->client->slug),
            ],
            'not_working_as_expected' => [
                'title'       => esc_html__('Not working as expected', $this->client->slug),
                'placeholder' => esc_html__('Could you tell us what do you expect?', $this->client->slug),
            ],
            'temporary_deactivation' => [
                'title'       => esc_html__('It\'s a temporary deactivation', $this->client->slug),
                'placeholder' => '',
            ],
            $this->client->prefix . 'pro' => [
                'title'       => esc_html__('I have ' . $this->client->title . ' Pro', $this->client->slug),
                'placeholder' => '',
                'alert'       => esc_html__('Wait! Don\'t deactivate ' . $this->client->title . '. You have to activate both ' . $this->client->title . ' and ' . $this->client->title . ' Pro in order to work the plugin.', $this->client->slug),
            ],
            'other' => [
                'title'       => esc_html__('Other', $this->client->slug),
                'placeholder' => esc_html__('Please share the reason', $this->client->slug),
            ],
        ];

        return apply_filters($this->client->prefix . 'deactivate_reasons', $reasons, $this->client);
    }

    /**
     * Ajax plugin deactivate feedback.
     *
     * Send the user feedback when plugin is deactivated.
     *
     * @since 0.0.1
     */
    public function handleDeactivateFeedback()
    {
        if (!isset($_POST['_ajax_nonce'])) {
            return;
        }

        if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST['_ajax_nonce'])), $this->client->prefix . 'nonce')) {
            wp_send_json_error('Nonce verification failed');
        }

        if (!current_user_can('activate_plugins')) {
            wp_send_json_error('Permission denied');
        }

        $report = $this->client->report->getTrackingData();
        $report['site_lang'] = get_bloginfo('language');
        $report['feedback_key'] = sanitize_text_field(wp_unslash($_POST['reason_key'])) ?: null;
        $report['feedback'] = sanitize_text_field(wp_unslash($_POST["reason_{$report['feedback_key']}"])) ?: null;

        $this->client->sendReport('deactivate-reason', $report);

        wp_send_json_success();
    }

    /**
     * @since 0.0.1
     */
    private function is_plugins_screen()
    {
        return \in_array(get_current_screen()->id, ['plugins', 'plugins-network']);
    }
}
