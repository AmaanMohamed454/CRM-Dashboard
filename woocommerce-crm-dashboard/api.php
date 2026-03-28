<?php
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Mock Data
$stats = [
    'total_sales' => '$124,500',
    'total_orders' => 1250,
    'total_customers' => 840
];

$customers = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'orders' => 15, 'spend' => 1500, 'is_high_value' => true],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'orders' => 3, 'spend' => 250, 'is_high_value' => false],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'orders' => 25, 'spend' => 3200, 'is_high_value' => true],
    ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'orders' => 1, 'spend' => 50, 'is_high_value' => false],
    ['id' => 5, 'name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'orders' => 5, 'spend' => 450, 'is_high_value' => false],
    ['id' => 6, 'name' => 'Diana Prince', 'email' => 'diana@example.com', 'orders' => 42, 'spend' => 5600, 'is_high_value' => true],
];

$orders = [
    ['id' => '#ORD-001', 'customer' => 'John Doe', 'amount' => '$120.00', 'status' => 'Pending'],
    ['id' => '#ORD-002', 'customer' => 'Jane Smith', 'amount' => '$450.00', 'status' => 'Processing'],
    ['id' => '#ORD-003', 'customer' => 'Bob Johnson', 'amount' => '$80.00', 'status' => 'Completed'],
    ['id' => '#ORD-004', 'customer' => 'Alice Brown', 'amount' => '$900.00', 'status' => 'Cancelled'],
    ['id' => '#ORD-005', 'customer' => 'Charlie Davis', 'amount' => '$150.00', 'status' => 'Pending'],
    ['id' => '#ORD-006', 'customer' => 'Diana Prince', 'amount' => '$2300.00', 'status' => 'Completed'],
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'dashboard_data') {
        // Sleep for 1 sec to show loading skeletons
        sleep(1);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'stats' => $stats,
                'customers' => $customers,
                'orders' => $orders
            ]
        ]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';

    if ($action === 'update_order_status') {
        $orderId = $input['order_id'];
        $status = $input['status'];
        // Simulate update processing time
        usleep(500000); 
        echo json_encode(['status' => 'success', 'message' => "Order $orderId updated to $status."]);
        exit;
    }

    if ($action === 'trigger_workflow') {
        $type = $input['type'];
        $customerId = $input['customer_id'] ?? '';
        $actionName = $type === 'priority' ? 'Marked as priority' : 'Email sent';
        usleep(300000);
        echo json_encode(['status' => 'success', 'message' => "$actionName for customer #$customerId."]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
