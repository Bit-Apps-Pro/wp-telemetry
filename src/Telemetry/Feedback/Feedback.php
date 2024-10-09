<?php

namespace BitApps\WPTelemetry\Telemetry\Feedback;

use BitApps\WPTelemetry\Telemetry\Telemetry;
use BitApps\WPTelemetry\Telemetry\TelemetryConfig;

class Feedback
{
    public function init()
    {
        add_action('wp_ajax_' . TelemetryConfig::getPrefix() . 'deactivate_feedback', [$this, 'handleDeactivateFeedback']);

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
        wp_register_style(TelemetryConfig::getPrefix() . 'deactivate_modal', $cssFilePath, [], TelemetryConfig::getVersion());
        wp_enqueue_style(TelemetryConfig::getPrefix() . 'deactivate_modal');
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
        Telemetry::view('deactivateModal', [
            'slug'    => TelemetryConfig::getSlug(),
            'prefix'  => TelemetryConfig::getPrefix(),
            'title'   => TelemetryConfig::getTitle(),
            'reasons' => $this->getDeactivateReasons(),
        ]);
    }

    public function getDeactivateReasons()
    {
        $reasons = [
            'found_a_better_plugin' => [
                'title'       => esc_html__('Found a better plugin', TelemetryConfig::getSlug()),
                'placeholder' => esc_html__('Which plugin?', TelemetryConfig::getSlug()),
            ],
            'missing_specific_feature' => [
                'title'       => esc_html__('Missing a specific feature', TelemetryConfig::getSlug()),
                'placeholder' => esc_html__('Could you tell us more about that feature?', TelemetryConfig::getSlug()),
            ],
            'not_working' => [
                'title'       => esc_html__('Not working', TelemetryConfig::getSlug()),
                'placeholder' => esc_html__('Could you tell us what is not working?', TelemetryConfig::getSlug()),
            ],
            'not_working_as_expected' => [
                'title'       => esc_html__('Not working as expected', TelemetryConfig::getSlug()),
                'placeholder' => esc_html__('Could you tell us what do you expect?', TelemetryConfig::getSlug()),
            ],
            'temporary_deactivation' => [
                'title'       => esc_html__('It\'s a temporary deactivation', TelemetryConfig::getSlug()),
                'placeholder' => '',
            ],
            TelemetryConfig::getPrefix() . 'pro' => [
                'title'       => esc_html__('I have ' . TelemetryConfig::getTitle() . ' Pro', TelemetryConfig::getSlug()),
                'placeholder' => '',
                'alert'       => esc_html__('Wait! Don\'t deactivate ' . TelemetryConfig::getTitle() . '. You have to activate both ' . TelemetryConfig::getTitle() . ' and ' . TelemetryConfig::getTitle() . ' Pro in order to work the plugin.', TelemetryConfig::getSlug()),
            ],
            'other' => [
                'title'       => esc_html__('Other', TelemetryConfig::getSlug()),
                'placeholder' => esc_html__('Please share the reason', TelemetryConfig::getSlug()),
            ],
        ];

        return apply_filters(TelemetryConfig::getPrefix() . 'deactivate_reasons', $reasons);
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

        if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST['_ajax_nonce'])), TelemetryConfig::getPrefix() . 'nonce')) {
            wp_send_json_error('Nonce verification failed');
        }

        if (!current_user_can('activate_plugins')) {
            wp_send_json_error('Permission denied');
        }

        $report = [];
        $report['url'] = esc_url(home_url());
        $report['site_lang'] = get_bloginfo('language');
        $report['feedback_key'] = sanitize_text_field(wp_unslash($_POST['reason_key'])) ?: null;
        $report['feedback'] = sanitize_text_field(wp_unslash($_POST["reason_{$report['feedback_key']}"])) ?: null;
        $report['plugin_slug'] = TelemetryConfig::getPrefix();
        $report['plugin_version'] = TelemetryConfig::getversion();

        Telemetry::sendReport('deactivate-reason', $report);

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
