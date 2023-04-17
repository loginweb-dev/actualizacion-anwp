<?php
/**
* Plugin Name: Actualizacion para anwp 
* Description: This plugin to create custom contact list-tables from database using WP_List_Table class.
* Version:     2.1.3
* Plugin URI: https://labarta.es/wp-basic-crud-plugin-wordpress/
* Author:      Labarta
* Author URI:  https://labarta.es/
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: wpbc
* Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( '¡Sin trampas!' );

require plugin_dir_path( __FILE__ ) . 'includes/metabox-p1.php';
require plugin_dir_path( __FILE__ ) . 'includes/create.php';
require plugin_dir_path( __FILE__ ) . 'includes/print.php';
require plugin_dir_path( __FILE__ ) . 'includes/asiento.php';
//require plugin_dir_path( __FILE__ ) . 'fpdf/main.php';

function wpbc_custom_admin_styles() {
    wp_enqueue_style('custom-styles', plugins_url('/bootstrap4/bootstrap.css', __FILE__ ));
	}
add_action('admin_enqueue_scripts', 'wpbc_custom_admin_styles');

function wpbc_custom_admin_js() {
    wp_enqueue_script('custom-js', plugins_url('/bootstrap4/bootstrap.js', __FILE__ ), array(), false, true);
	}
add_action('admin_enqueue_scripts', 'wpbc_custom_admin_js');

function wpbc_custom_admin_js2() {
    wp_enqueue_script('custom-js', plugins_url('/bootstrap4/bootstrap.bundle.min.js', __FILE__ ), array(), false, true);
	}
add_action('admin_enqueue_scripts', 'wpbc_custom_admin_js2');

function wpbc_mijs() {
    wp_enqueue_script('handle', plugins_url('/js/mijs.js', __FILE__ ), array(), false, true);
	}
add_action('admin_enqueue_scripts', 'wpbc_mijs');


function wpbc_plugin_load_textdomain() {
load_plugin_textdomain( 'wpbc', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'wpbc_plugin_load_textdomain' );


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Custom_Table_Example_List_Table extends WP_List_Table
 { 
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'finanza',
            'plural'   => 'finanzas',
        ));
    }

    function column_default($item, $column_post_title)
    {
        return $item[$column_post_title];
    }

    function column_post_title($item)
    {

        $actions = array(
        'ver' => sprintf('<a href="?page=finanzas_ver&id=%s">%s</a>', $item['ID'], 'Ver'),
            'edit' => sprintf('<a href="?page=contacts_form&id=%s">%s</a>', $item['ID'], __('Edit', 'wpbc')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['ID'], __('Delete', 'wpbc')),
        );

        return sprintf('%s %s',
            $item['post_title'],
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="ID[]" value="%s" />',
            $item['ID']
        );
    }

	
    function column_print($item)
    {
        return sprintf(
            '<a type="buttom" href="/wp-content/plugins/actualizacion-anwp/fpdf/main.php">Imrpimir<a/>',
            $item['ID']
        );
    }
	
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', 
            'ID'      => 'ID',
        	'post_title'      => 'Titulo',
            'print' => 'Acciones'
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'ID'      => array('id', true),
        	'post_title'      => array('title', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        //$table_name = $wpdb->prefix . 'cte'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM wp_posts WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        global $wpdb;
        //$table_name = $wpdb->prefix . 'cte'; 

        $per_page = 15; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
       
        $this->process_bulk_action();

    
		$this->search_box('search', 'search_id');
    
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM wp_posts WHERE post_type='anwp_finanza'");


        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'post_title';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';


        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_posts WHERE post_type='anwp_finanza' ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);


        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page) 
        ));
    }
}

function wpbc_admin_menu()
{
    add_menu_page('Mi Panel', 'Mi Panel', 'manage_options', 'finanzas', 'wpbc_asiento');
    
	add_submenu_page('finanzas', 'Asientos', 'Asientos', 'manage_options', 'finanzas', 'wpbc_asiento');
	add_submenu_page('finanzas', 'Imprimir', 'Imprimir', 'manage_options', 'finanzas_print', 'wpbc_print');
    //add_submenu_page('finanzas', 'Nuevo', 'Nuevo', 'manage_options', 'wpbc_create', 'wpbc_create');
   //add_submenu_page('finanzas', 'Ver', 'Ver', 'manage_options', 'finanzas_ver', 'wpbc_contacts_form_ver_handler');
}

add_action('admin_menu', 'wpbc_admin_menu');


function wpbc_validate_contact($item)
{
    $messages = array();
    //if (empty($item['name'])) $messages[] = __('Name is required', 'wpbc');
    //if (empty($item['lastname'])) $messages[] = __('Last Name is required', 'wpbc');
   // if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'wpbc');
    //if(!empty($item['phone']) && !absint(intval($item['phone'])))  $messages[] = __('Phone can not be less than zero');
    //if(!empty($item['phone']) && !preg_match('/[0-9]+/', $item['phone'])) $messages[] = __('Phone must be number');
    if (empty($messages)) return true;
    return implode('<br />', $messages);
}

function wpbc_languages()
{
    load_plugin_textdomain('wpbc', false, dirname(plugin_basename(__FILE__)));
}
add_action('init', 'wpbc_languages');

include('mijugadores.php');
include('miclubes.php');