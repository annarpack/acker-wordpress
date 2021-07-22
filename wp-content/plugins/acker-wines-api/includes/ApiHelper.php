<?php


namespace AckerWines\Api;


class ApiHelper
{
    const AW_MINDATE = '1970-01-01T00:00:00.000';
    const AW_MAXDATE = '2038-01-01T00:00:00.000';

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

    public static function zero2null($value) {
        if ( !$value ) {
            return null;
        } else {
            return $value;
        }
    }

    public static function null2EmptyString($value) {
        if ( $value === null) {
            return '';
        } else {
            return $value;
        }
    }

    public static function null2ZeroInt($value) {
        if ( $value === null) {
            return 0;
        } else {
            return $value;
        }
    }

    public static function null2ZeroDecimal($value) {
        if ( $value === null) {
            return 0.00;
        } else {
            return $value;
        }
    }

    public static function null2Bool($value) {
        if ( $value === null ) {
            return false;
        } else {
            return (bool)$value;
        }
    }

    public static function null2MinDate($value) {
        if ( $value === null) {
            return ApiHelper::getMinDate();
        } else {
            return $value;
        }
    }

    public static function null2MaxDate($value) {
        if ( $value === null) {
            return ApiHelper::getMaxDate();
        } else {
            return $value;
        }
    }

    public static function getDataValue(array $row, string $key) {
        if ( isset($row[$key]) ) {
            return $row[$key];
        } else {
            return null;
        }
    }

    public static function getMinDate() {
        return new \DateTime(self::AW_MINDATE);
    }

    public static function getMaxDate() {
        return new \DateTime(self::AW_MAXDATE);
    }
}
