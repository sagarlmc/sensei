<?php
if ( ! defined( 'ABSPATH' ) ) exit; // security check, don't load file outside WP
/**
 * Sensei Template Class
 *
 * Handles all Template loading and redirecting functionality.
 *
 * @package Sensei
 * @category Templates
 * @since 1.9.0
 */
class Sensei_Templates {

    /**
     *  Load the template files from within sensei/templates/ or the the theme if overrided within the theme.
     *
     * @since 1.9.0
     * @param string $slug
     * @param string $name default: ''
     *
     * @return void
     */
    public static function get_part(  $slug, $name = ''  ){

        $template = '';
        $plugin_template_url = Sensei()->template_url;
        $plugin_template_path = Sensei()->plugin_path() . "/templates/";

        // Look in yourtheme/slug-name.php and yourtheme/sensei/slug-name.php
        if ( $name ){

            $template = locate_template( array ( "{$slug}-{$name}.php", "{$plugin_template_url}{$slug}-{$name}.php" ) );

        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( $plugin_template_path . "{$slug}-{$name}.php" ) ){

            $template = $plugin_template_path . "{$slug}-{$name}.php";

        }


        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/sensei/slug.php
        if ( !$template ){

            $template = locate_template( array ( "{$slug}.php", "{$plugin_template_url}{$slug}.php" ) );

        }


        if ( $template ){

            load_template( $template, false );

        }

    } // end get part

    /**
     * Get the template.
     *
     * @since 1.9.0
     *
     * @param $template_name
     * @param array $args
     * @param string $template_path
     * @param string $default_path
     */
    public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

        if ( $args && is_array($args) )
            extract( $args );

        $located = self::locate_template( $template_name, $template_path, $default_path );

        do_action( 'sensei_before_template_part', $template_name, $template_path, $located );

        include( $located );

        do_action( 'sensei_after_template_part', $template_name, $template_path, $located );


    } // end get template

    /**
     * Check if the template file exists. A wrapper for WP locate_template.
     *
     * @since 1.9.0
     *
     * @param $template_name
     * @param string $template_path
     * @param string $default_path
     *
     * @return mixed|void
     */
    public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {

        if ( ! $template_path ) $template_path = Sensei()->template_url;
        if ( ! $default_path ) $default_path = Sensei()->plugin_path() . '/templates/';

        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );

        // Get default template
        if ( ! $template ){

            $template = $default_path . $template_name;

        }

        // Return what we found
        return apply_filters( 'sensei_locate_template', $template, $template_name, $template_path );

    } // end locate

} // end class