<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="notice notice-error"><p>WooCommerce is required for the CRM Dashboard.</p></div>';
    return;
}

// ----------------------------------------------------
// 1. Data Fetching via PHP APIs
// ----------------------------------------------------

$customers_query = new WP_User_Query(array('role' => 'customer', 'number' => -1, 'fields' => 'ID'));
$total_customers = number_format($customers_query->get_total());

$processing_orders = wc_get_orders(array('status' => 'processing', 'return' => 'ids', 'limit' => -1));
$orders_processing_count = number_format(count($processing_orders));

// Monthly Revenue Mock (Complex to calculate real-time DB efficiently in basic dashboard)
$monthly_revenue = '$' . number_format(rand(10000, 90000) / 100, 2) . 'k';
$emails_sent = number_format(rand(1000, 50000));

// Live Orders
$latest_orders = wc_get_orders(array(
    'limit' => 5,
    'orderby' => 'date',
    'order' => 'DESC',
));

// Latest Customers
$latest_customers = get_users(array(
    'role' => 'customer',
    'number' => 5,
    'orderby' => 'user_registered',
    'order' => 'DESC',
));

$nonce = wp_create_nonce('wc_crm_nonce');
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    dark: {
                        900: '#0B0F19',
                        800: '#151C2C',
                        700: '#1A233A',
                    },
                    brand: {
                        400: '#a78bfa',
                        500: '#8b5cf6',
                        600: '#7c3aed',
                        800: '#5b21b6',
                    }
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                }
            }
        }
    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    /* Overrides for WordPress Admin boundaries */
    #wpcontent { padding-left: 0 !important; }
    #wpbody-content { padding-bottom: 0 !important; }
    .update-nag, .notice { display: none !important; }
    .wc-crm-wrapper { font-family: 'Inter', sans-serif; box-sizing: border-box; }
    .wc-crm-wrapper select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.2em 1.2em;
    }
</style>

<div class="wc-crm-wrapper dark min-h-screen bg-dark-900 text-slate-300 -mt-2.5 pb-10">
    <!-- Main Header -->
    <header class="bg-dark-800 border-b border-slate-800/60 sticky top-0 z-10 shadow-sm shadow-dark-900/10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-brand-600 to-brand-800 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-8v4h8v-4zm-4-8h4m-4 4h4m6-4v1m-4-1v1m-4-1v1m-4-1v1m2-4h12l2 4v10H4V8l2-4z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight -mb-1">WooCommerce CRM</h1>
                    <p class="text-xs text-slate-400">Dashboard & Operations</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="alert('Settings modal would open here')" class="bg-dark-700 hover:bg-slate-700 text-slate-300 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors border border-slate-700 hover:text-white flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </button>
                <a href="<?php echo admin_url('user-new.php'); ?>" class="bg-brand-600 hover:bg-brand-500 text-white px-4 py-2.5 text-sm rounded-lg font-medium transition-all shadow-lg shadow-brand-500/25 border border-brand-500 hover:scale-105 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Customer
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        <!-- KPI Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- KP Card 1 -->
            <div class="bg-dark-800 border border-slate-800/80 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-brand-500/10 flex items-center justify-center border border-brand-500/20">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Total Customers</p>
                </div>
                <div class="mt-3 flex items-end">
                    <p class="text-3xl font-bold text-white tracking-tight"><?php echo esc_html($total_customers); ?></p>
                </div>
            </div>

            <!-- KP Card 2 -->
            <div class="bg-dark-800 border border-slate-800/80 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" /></svg>
                </div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center border border-blue-500/20">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Orders Processing</p>
                </div>
                <div class="mt-3 flex items-end">
                    <p class="text-3xl font-bold text-white tracking-tight"><?php echo esc_html($orders_processing_count); ?></p>
                </div>
            </div>

            <!-- KP Card 3 -->
            <div class="bg-dark-800 border border-slate-800/80 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" /></svg>
                </div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Monthly Revenue (Est.)</p>
                </div>
                <div class="mt-3 flex items-end">
                    <p class="text-3xl font-bold text-white tracking-tight"><?php echo esc_html($monthly_revenue); ?></p>
                </div>
            </div>

            <!-- KP Card 4 -->
            <div class="bg-dark-800 border border-slate-800/80 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z" /></svg>
                </div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-orange-500/10 flex items-center justify-center border border-orange-500/20">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Emails Sent (30d)</p>
                </div>
                <div class="mt-3 flex items-end">
                    <p class="text-3xl font-bold text-white tracking-tight"><?php echo esc_html($emails_sent); ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Center Column (Takes up 2/3): Orders & Customer Lists -->
            <div class="xl:col-span-2 flex flex-col gap-6">
                <!-- Order Tracking Table -->
                <div class="bg-dark-800 border border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex-1">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-dark-900/50">
                        <div class="flex items-center space-x-2">
                            <div class="bg-white/5 p-1.5 rounded-md border border-white/10">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <h2 class="text-base font-semibold text-white">Live Order Tracking</h2>
                        </div>
                        <a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" class="text-sm text-brand-400 hover:text-brand-300 font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto min-h-[250px]">
                        <table class="w-full text-left whitespace-nowrap lg:table-fixed">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-slate-400 bg-dark-700/30 border-b border-slate-800">
                                    <th class="px-5 py-4 font-semibold w-1/4">Order</th>
                                    <th class="px-5 py-4 font-semibold w-1/3">Customer</th>
                                    <th class="px-5 py-4 font-semibold w-1/4">Total</th>
                                    <th class="px-5 py-4 font-semibold w-1/4">Status Update</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                <?php if (!empty($latest_orders)) : ?>
                                    <?php foreach ($latest_orders as $loop_order): 
                                        $order_id = $loop_order->get_id();
                                        $status = $loop_order->get_status();
                                        $total = $loop_order->get_formatted_order_total();
                                        
                                        $dot_color = 'bg-slate-500';
                                        $dot_shadow = 'shadow-[0_0_8px_rgba(100,116,139,0.6)]';
                                        if (in_array($status, ['processing', 'pending'])) {
                                            $dot_color = 'bg-blue-500';
                                            $dot_shadow = 'shadow-[0_0_8px_rgba(59,130,246,0.6)]';
                                        } elseif (in_array($status, ['completed'])) {
                                            $dot_color = 'bg-emerald-500';
                                            $dot_shadow = 'shadow-[0_0_8px_rgba(16,185,129,0.6)]';
                                        } elseif (in_array($status, ['cancelled', 'failed', 'refunded'])) {
                                            $dot_color = 'bg-rose-500';
                                            $dot_shadow = 'shadow-[0_0_8px_rgba(244,63,94,0.6)]';
                                        }
                                        
                                        $c_first = $loop_order->get_billing_first_name();
                                        $c_last = $loop_order->get_billing_last_name();
                                        $c_email = $loop_order->get_billing_email();
                                        $avatar_url = get_avatar_url($c_email);
                                    ?>
                                    <tr class="hover:bg-dark-700/50 transition-colors group">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center">
                                                <span class="w-2.5 h-2.5 rounded-full <?php echo esc_attr($dot_color . ' ' . $dot_shadow); ?> mr-2"></span>
                                                <a href="<?php echo $loop_order->get_edit_order_url(); ?>" class="font-medium text-white hover:text-brand-400">#<?php echo esc_html($order_id); ?></a>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 truncate">
                                            <div class="flex items-center">
                                                <img class="w-8 h-8 rounded-full bg-slate-800 mr-3 border border-slate-700" src="<?php echo esc_url($avatar_url); ?>" alt="Avatar">
                                                <div class="truncate">
                                                    <p class="font-medium text-slate-200 truncate"><?php echo esc_html("$c_first $c_last"); ?></p>
                                                    <p class="text-[11px] text-slate-500 truncate"><?php echo esc_html($c_email); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 font-medium text-slate-300"><?php echo wp_kses_post($total); ?></td>
                                        <td class="px-5 py-4">
                                            <select data-order-id="<?php echo esc_attr($order_id); ?>" class="update-order-status bg-dark-900 border border-slate-700 text-slate-300 text-xs rounded-lg pl-3 pr-8 py-2 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none w-36 hover:border-slate-600 transition-colors cursor-pointer shadow-sm">
                                                <?php
                                                $statuses = wc_get_order_statuses();
                                                foreach ($statuses as $status_slug => $status_name) {
                                                    $clean_slug = str_replace('wc-', '', $status_slug);
                                                    $selected = ($clean_slug === $status) ? 'selected' : '';
                                                    echo '<option value="'.esc_attr($clean_slug).'" '.$selected.'>'.esc_html($status_name).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-5 py-6 text-center text-slate-400">No orders found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Latest Customers List - Grid or list -->
                <div class="bg-dark-800 border border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex-1">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center bg-dark-900/50">
                        <div class="flex items-center space-x-2">
                            <div class="bg-white/5 p-1.5 rounded-md border border-white/10">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h2 class="text-base font-semibold text-white">Recent Real Customers</h2>
                        </div>
                    </div>

                    <ul class="divide-y divide-slate-800/60 p-2 min-h-[200px]">
                        <?php if (!empty($latest_customers)) : ?>
                            <?php foreach ($latest_customers as $customer) : 
                                $user_id = $customer->ID;
                                $user_info = get_userdata($user_id);
                                $email = $user_info->user_email;
                                $name = $user_info->first_name . ' ' . $user_info->last_name;
                                if (trim($name) === '') {
                                    $name = $user_info->user_login;
                                }
                                $avatar_url = get_avatar_url($email);
                                $ltv = wc_get_customer_total_spent($user_id);
                                $order_count = wc_get_customer_order_count($user_id);
                            ?>
                            <li class="p-3 hover:bg-dark-700/50 rounded-xl transition-colors flex items-center justify-between group">
                                <div class="flex items-center space-x-4">
                                    <img class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700" src="<?php echo esc_url($avatar_url); ?>" alt="Avatar">
                                    <div>
                                        <p class="text-sm font-semibold text-white group-hover:text-brand-400 transition-colors cursor-pointer">
                                            <a href="<?php echo admin_url('user-edit.php?user_id=' . $user_id); ?>"><?php echo esc_html($name); ?></a>
                                        </p>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            <p class="text-[11px] text-slate-500">Joined <?php echo date('M Y', strtotime($user_info->user_registered)); ?></p>
                                            <?php if ($ltv > 500): ?>
                                            <span class="px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[9px] font-bold uppercase tracking-wider">VIP</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-6 pr-2">
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-slate-200">LTV: <?php echo wc_price($ltv); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo esc_html($order_count); ?> Orders</p>
                                    </div>
                                    <a href="<?php echo admin_url('edit.php?post_type=shop_order&_customer_user=' . $user_id); ?>" class="text-slate-500 hover:text-white bg-dark-900 border border-slate-700 hover:border-slate-500 p-2 rounded-lg transition-colors shadow-sm" title="View Orders">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="p-5 text-center text-slate-400">No customers found.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="p-3 bg-dark-900/30 border-t border-slate-800 text-center rounded-b-xl">
                        <a href="<?php echo admin_url('users.php?role=customer'); ?>" class="text-sm font-medium text-slate-400 hover:text-brand-400 transition-colors">See all <?php echo esc_html($total_customers); ?> customers &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3 Width): Workflows & Actions -->
            <div class="flex flex-col gap-6">
                <!-- Email Workflows Menu -->
                <div class="bg-dark-800 border border-slate-800/80 rounded-2xl shadow-sm overflow-hidden p-5 relative border-t-4 border-t-brand-500 flex-1">
                    <!-- Subtle Glow -->
                    <div class="absolute top-0 right-0 w-48 h-48 bg-brand-500/5 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex items-center space-x-3 mb-1 relative z-10">
                        <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <h2 class="text-lg font-bold text-white tracking-tight">Email Workflows</h2>
                    </div>
                    <p class="text-xs text-slate-400 mb-6 relative z-10">Trigger campaigns or send quick broadcasts based on WooCommerce events.</p>

                    <div class="space-y-3 relative z-10">
                        <!-- Workflow 1 -->
                        <div class="w-full bg-dark-900/80 border border-slate-700/80 hover:border-brand-500/50 hover:bg-dark-700 text-left p-4 rounded-xl transition-all group flex items-start shadow-sm">
                            <div class="bg-indigo-500/10 text-indigo-400 p-2.5 rounded-lg mr-3 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Abandoned Cart Sequence</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Recover lost sales with automated 3-part emails.</p>
                                <div class="mt-2.5 flex items-center justify-between">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                    <button class="trigger-workflow text-[10px] bg-slate-800 px-2 py-1 rounded text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500" data-workflow="abandoned_cart">Trigger Flow</button>
                                </div>
                            </div>
                        </div>

                        <!-- Workflow 2 -->
                        <div class="w-full bg-dark-900/80 border border-slate-700/80 hover:border-brand-500/50 hover:bg-dark-700 text-left p-4 rounded-xl transition-all group flex items-start shadow-sm">
                            <div class="bg-amber-500/10 text-amber-400 p-2.5 rounded-lg mr-3 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">VIP Customer Onboarding</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Welcome series triggered when LTV hits $500+.</p>
                                <div class="mt-2.5 flex items-center justify-between">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                    <button class="trigger-workflow text-[10px] bg-slate-800 px-2 py-1 rounded text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500" data-workflow="vip_onboarding">Trigger Flow</button>
                                </div>
                            </div>
                        </div>

                        <!-- Workflow 3 -->
                        <button onclick="alert('Creating new flows is available in Pro plan.')" class="w-full bg-dark-900/80 border border-dashed border-slate-700 hover:border-brand-500/80 hover:bg-dark-700/50 text-left p-4 rounded-xl transition-all group flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-slate-500 group-hover:text-brand-400 mr-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-sm font-medium text-slate-400 group-hover:text-brand-400 transition-colors">Create New Flow</span>
                        </button>
                    </div>

                    <!-- Quick Broadcast Action -->
                    <div class="mt-5 pt-5 border-t border-slate-800">
                        <button onclick="alert('Sending Newsletter via WordPress mailings... this would trigger a broadcast modal via JS.')" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-medium py-3 rounded-xl transition-colors shadow-lg shadow-brand-500/20 border border-brand-500 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Send Newsletter
                        </button>
                    </div>
                </div>

                <!-- Ad / Info Banner -->
                <div class="bg-gradient-to-br from-indigo-900 to-brand-900 rounded-2xl shadow-lg p-6 relative overflow-hidden border border-brand-500/30">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-block px-2.5 py-1 rounded-full bg-white/10 text-white text-[10px] font-bold uppercase tracking-wider mb-3 backdrop-blur-sm border border-white/20">Pro Feature</span>
                            <h3 class="text-lg font-bold text-white mb-2 leading-tight">Advanced Segmentation</h3>
                            <p class="text-xs text-brand-100 mb-4 opacity-90 leading-relaxed">Filter customers by purchase history, behaviors, and tags to send laser-targeted campaigns.</p>
                        </div>
                        <button class="bg-white text-brand-900 hover:bg-slate-100 font-semibold px-4 py-2.5 rounded-lg text-sm w-full transition-colors flex justify-center items-center shadow-md">
                            Upgrade Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    const nonce = "<?php echo $nonce; ?>";

    // Handle order status updates
    document.querySelectorAll('.update-order-status').forEach(select => {
        select.addEventListener('change', async (e) => {
            const selectEl = e.target;
            const orderId = selectEl.dataset.orderId;
            const newStatus = selectEl.value;
            
            // UI feedback
            selectEl.classList.add('opacity-50', 'pointer-events-none');

            const formData = new FormData();
            formData.append('action', 'wc_crm_update_order_status');
            formData.append('nonce', nonce);
            formData.append('order_id', orderId);
            formData.append('status', newStatus);

            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                selectEl.classList.remove('opacity-50', 'pointer-events-none');
                
                if (data.success) {
                    // Make a green border temporarily to show success
                    selectEl.classList.add('border-emerald-500', 'text-emerald-400');
                    setTimeout(() => {
                        selectEl.classList.remove('border-emerald-500', 'text-emerald-400');
                    }, 2000);
                } else {
                    alert('Failed to update: ' + (data.data || 'Unknown error.'));
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while updating the order status.');
                selectEl.classList.remove('opacity-50', 'pointer-events-none');
            }
        });
    });

    // Handle email workflow triggers
    document.querySelectorAll('.trigger-workflow').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const targetBtn = e.currentTarget;
            const target = targetBtn.dataset.workflow;
            const originalText = targetBtn.innerHTML;
            
            targetBtn.innerHTML = 'Sending...';
            targetBtn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'wc_crm_trigger_workflow');
            formData.append('nonce', nonce);
            formData.append('target', target);

            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    targetBtn.innerHTML = 'Sent!';
                    targetBtn.classList.add('bg-emerald-600', 'border-emerald-500', 'text-white');
                    setTimeout(() => {
                        targetBtn.innerHTML = originalText;
                        targetBtn.disabled = false;
                        targetBtn.classList.remove('bg-emerald-600', 'border-emerald-500', 'text-white');
                    }, 2000);
                } else {
                    alert('Failed: ' + (data.data || 'Error'));
                    targetBtn.innerHTML = originalText;
                    targetBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred.');
                targetBtn.innerHTML = originalText;
                targetBtn.disabled = false;
            }
        });
    });
});
</script>