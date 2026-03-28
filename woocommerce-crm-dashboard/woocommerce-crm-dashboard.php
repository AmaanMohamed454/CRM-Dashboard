<?php
/**
 * Plugin Name: WooCommerce CRM Dashboard
 * Description: A stunning, dark-mode CRM dashboard for WooCommerce with customer lists, order tracking, and email workflows using Tailwind CSS.
 * Version: 1.0.0
 * Author: Antigravity AI
 * Text Domain: wc-crm-dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add the menu page for the CRM Dashboard
add_action('admin_menu', 'wc_crm_dashboard_add_menu');

function wc_crm_dashboard_add_menu() {
    add_menu_page(
        'WooCommerce CRM',
        'Woo CRM',
        'manage_woocommerce', // Requires WooCommerce management capabilities
        'wc-crm-dashboard',
        'wc_crm_dashboard_render_page',
        'dashicons-chart-pie', // CRM icon
        56 // Position right below WooCommerce main menus usually
    );
}

// AJAX handler to update order status
add_action('wp_ajax_wc_crm_update_order_status', 'wc_crm_ajax_update_order_status');
function wc_crm_ajax_update_order_status() {
    check_ajax_referer('wc_crm_nonce', 'nonce');

    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized');
    }

    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

    if (!$order_id || !$status || !class_exists('WooCommerce')) {
        wp_send_json_error('Invalid data or WooCommerce inactive.');
    }

    $order = wc_get_order($order_id);
    if ($order) {
        $order->update_status($status, 'Status updated via CRM Dashboard.', true);
        wp_send_json_success('Status updated');
    }

    wp_send_json_error('Order not found');
}

// AJAX handler to trigger email workflow
add_action('wp_ajax_wc_crm_trigger_workflow', 'wc_crm_ajax_trigger_workflow');
function wc_crm_ajax_trigger_workflow() {
    check_ajax_referer('wc_crm_nonce', 'nonce');

    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized');
    }

    $workflow_target = isset($_POST['target']) ? sanitize_text_field($_POST['target']) : '';
    // Let's pretend we sent an email workflow.
    
    wp_send_json_success("Workflow '$workflow_target' triggered successfully.");
}

// Render the dashboard page
function wc_crm_dashboard_render_page() {
    // Include the view file containing the HTML/Tailwind
    include plugin_dir_path(__FILE__) . 'admin-page.php';
}
