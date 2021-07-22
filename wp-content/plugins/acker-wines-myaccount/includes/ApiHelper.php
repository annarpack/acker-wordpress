<?php


namespace AckerWines;


class ApiHelper
{

    /**
     * ApiHelper constructor.
     */
    public function __construct()
    {
        // Default constructor
    }

    /**
     * Register an AJAX endpoint with WordPress.
     *
     * @param string $endpoint
     * @param bool $nopriv
     */
    public static function registerAjaxEndpoint(string $endpoint, bool $nopriv = NULL) {
        if (!function_exists('add_action')) return;
        add_action('wp_ajax_' . $endpoint, $endpoint);
        if ($nopriv) {
            add_action('wp_ajax_nopriv_' . $endpoint, $endpoint);
        }
    }
}
