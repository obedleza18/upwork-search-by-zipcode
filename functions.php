<?php

add_action( 'admin_menu', 'upwork_franchise_map_import_page' );
add_action( 'admin_init', 'upwork_franchise_map_settings_init' );
add_action( 'wp_enqueue_scripts', 'upwork_franchise_scripts' );
add_action( 'wp_ajax_upwork_get_franchises_options', 'upwork_get_franchises_options' );

function upwork_get_franchises_options() {
    $zip_code = isset( $_POST['zip_code'] ) ? $_POST['zip_code'] : '';

    $county_code = get_county_code( get_option( 'upwork_franchise' )['upwork-franchise-import-data'], $zip_code );

    $franchises_options = get_franchises_options( get_option( 'upwork_franchise' )['upwork-franchise-import-locations'], $county_code );

    shuffle( $franchises_options );

    wp_send_json_success( $franchises_options );
}

function upwork_franchise_scripts() {

    wp_enqueue_style( 'upwork_franchise_custom_style', plugin_dir_url( __FILE__ ) . 'css/style.css' );

    wp_enqueue_style( 'upwork_franchise_confirm_style', plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.css' );

    wp_enqueue_script(
        'upwork_franchise_custom_script',
        plugin_dir_url( __FILE__ ) . 'js/upwork_franchise.js', array( 'jquery' ), '1.0',
        true
    );

    wp_localize_script(
        'upwork_franchise_custom_script',
        'upwork_franchise_custom_object',
        array( 
            'ajax_url'          => admin_url( 'admin-ajax.php' ), 
            'site_url'          => get_site_url(),
            'input_label'       => __( 'Enter your Zip Code', 'upwork-franchise-map' ),
            'sumbit_label'      => __( 'Find', 'upwork-franchise-map' ),
            'franchise_id'      => __( 'Franchise ID', 'upwork-franchise-map' ),
            'franchise_name'    => __( 'Franchise Name', 'upwork-franchise-map' ),
            'phone'             => __( 'Phone', 'upwork-franchise-map' ),
            'website'           => __( 'Website', 'upwork-franchise-map' ),
            'email'             => __( 'Email', 'upwork-franchise-map' ),
        )
    );

    wp_enqueue_script(
        'upwork_franchise_confirm',
        plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.js', array( 'jquery' ), '1.0',
        true
    );
}

function upwork_franchise_map_import_page() {
    add_menu_page(
        __( 'Franchise Map Import', 'upwork-franchise-map' ),
        __( 'Franchise Map', 'upwork-franchise-map' ),
        'manage_options',
        'upwork-franchise-map/upwork-franchise-map-import.php'
    );
}

function upwork_franchise_map_settings_init() {
    register_setting(
        'upwork_franchise_group',
        'upwork_franchise',
        'upwork_franchise_sanitize_fields'
    );

    add_settings_section(
        'upwork_franchise_section',
        __( 'Imports', 'upwork-franchise-map' ),
        'upwork_franchise_section_info',
        'upwork-franchise-map-import'
    );

    add_settings_field(
        'upwork-franchise-import-text',
        __( 'Zip Code to County Code Map CSV', 'upwork-franchise-map' ),
        'upwork_franchise_map_input_field',
        'upwork-franchise-map-import',
        'upwork_franchise_section'
    );

    add_settings_field(
        'upwork-franchise-import-locations',
        __( 'Locations CSV', 'upwork-franchise-map' ),
        'upwork_franchise_locations_input_field',
        'upwork-franchise-map-import',
        'upwork_franchise_section'
    );
}

function upwork_franchise_section_info() {
    _e( 'Copy and paste the CSV contents to this area and save changes to import data.', 'upwork-franchise-map' );
}

function upwork_franchise_map_input_field() {
    $options = get_option( 'upwork_franchise' );
    $map_value = isset( $options['upwork-franchise-import-data'] ) ? $options['upwork-franchise-import-data'] : '';
    echo '<textarea rows="10" id="upwork-franchise-import-data" name="upwork_franchise[upwork-franchise-import-data]">' . $map_value . '</textarea>';
}

function upwork_franchise_locations_input_field() {
    $options = get_option( 'upwork_franchise' );
    $locations_value = isset( $options['upwork-franchise-import-locations'] ) ? $options['upwork-franchise-import-locations'] : '';
    echo '<textarea rows="10" id="upwork-franchise-import-locations" name="upwork_franchise[upwork-franchise-import-locations]">' . $locations_value . '</textarea>';
}

function upwork_franchise_sanitize_fields( $input ) {
    return $input;
}

function get_county_code( $csv, $item ) {
    $csv_keys_values = preg_split( '/\n/', $csv );
    for ( $i = 1; $i < count($csv_keys_values); $i++ ) {
        $csv_values = str_getcsv($csv_keys_values[$i], ",");
        if ( (int)$item == (int)$csv_values[0] ) {
            return $csv_values[1];
        }
    }

    return -1;
}

function get_franchises_options( $csv, $county_code ) {
    $csv_keys_values = preg_split( '/\n/', $csv );
    $csv_keys = preg_split( '/,/', $csv_keys_values[0] );
    $arr = array();
    for ( $i = 1; $i < count($csv_keys_values); $i++ ) {
        $csv_values = str_getcsv($csv_keys_values[$i], ",");
        if ( preg_match( '/' . $county_code . '/', $csv_values[5] ) ) {
            $index = 0;
            foreach ( $csv_values as $csv_value ) {
                $arr[$i-1][$csv_keys[$index]] = $csv_value;
                $index++;
            }
        }
    }

    return $arr;
}