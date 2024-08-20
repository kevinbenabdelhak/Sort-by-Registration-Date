<?php
/**
 * Plugin Name: Sort by Registration Date
 * Plugin URI: https://kevin-benabdelhak.fr/plugins/Sort-by-Registration-Date/
 * Description: Sort by Registration Date est un plugin WordPress qui ajoute une colonne "Date d'inscription" dans l'interface de gestion des utilisateurs, permettant de visualiser et de trier facilement les utilisateurs en fonction de leur date d'inscription. Optimisez votre gestion d'utilisateurs avec un affichage clair et pratique !
 * Version: 1.0
 * Author: Kevin BENABDELHAK
 * Author URI: https://kevin-benabdelhak.fr/a-propos/
 * License: GPL2
 */

// Si ce fichier est appelé directement, aborter.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function my_add_user_registration_date_column($columns) {
    $columns['registration_date'] = __('Date d\'inscription', 'Sort-by-Registration-Date');
    return $columns;
}
add_filter('manage_users_columns', 'my_add_user_registration_date_column');

function my_show_user_registration_date_column_content($value, $column_name, $user_id) {
    if ($column_name == 'registration_date') {
        $user_info = get_userdata($user_id);
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $value = date_i18n($date_format . ' ' . $time_format, strtotime($user_info->user_registered));
    }
    return $value;
}
add_action('manage_users_custom_column', 'my_show_user_registration_date_column_content', 10, 3);

function my_make_user_registration_date_column_sortable($columns) {
    $columns['registration_date'] = 'registration_date';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'my_make_user_registration_date_column_sortable');

function my_orderby_registration_date($query) {
    if (!is_admin())
        return;

    $orderby = $query->get('orderby');

    if ('registration_date' == $orderby) {
        $query->set('orderby', 'registered');
    }
}
add_action('pre_get_users', 'my_orderby_registration_date');