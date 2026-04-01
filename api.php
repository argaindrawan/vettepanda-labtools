<?php
header('Content-Type: application/json');

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'research_inventory';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'list') {
    $search = $conn->real_escape_string($_GET['search'] ?? '');
    $category = $conn->real_escape_string($_GET['category'] ?? '');

    $where = [];
    if ($search !== '') {
        $escaped = $conn->real_escape_string('%' . $search . '%');
        $where[] = "(item_name LIKE '$escaped' OR location LIKE '$escaped' OR notes LIKE '$escaped')";
    }
    if ($category !== '') {
        $where[] = "category = '$category'";
    }

    $sql = 'SELECT * FROM inventory';
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY last_updated DESC';

    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) { $items[] = $row; }

    echo json_encode(['items' => $items]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST' && $action === 'add') {
    $name = $conn->real_escape_string(trim($input['item_name'] ?? ''));
    $category = $conn->real_escape_string(trim($input['category'] ?? ''));
    $quantity = intval($input['quantity'] ?? 0);
    $unit = $conn->real_escape_string(trim($input['unit'] ?? ''));
    $location = $conn->real_escape_string(trim($input['location'] ?? ''));
    $notes = $conn->real_escape_string(trim($input['notes'] ?? ''));

    if ($name === '' || $unit === '' || ($category !== 'equipment' && $category !== 'material')) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $sql = "INSERT INTO inventory (item_name, category, quantity, unit, location, notes) VALUES ('$name', '$category', $quantity, '$unit', '$location', '$notes')";
    if (!$conn->query($sql)) {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
        exit;
    }

    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    exit;
}

if ($method === 'PUT' && $action === 'update') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        exit;
    }

    $name = $conn->real_escape_string(trim($input['item_name'] ?? ''));
    $category = $conn->real_escape_string(trim($input['category'] ?? ''));
    $quantity = intval($input['quantity'] ?? 0);
    $unit = $conn->real_escape_string(trim($input['unit'] ?? ''));
    $location = $conn->real_escape_string(trim($input['location'] ?? ''));
    $notes = $conn->real_escape_string(trim($input['notes'] ?? ''));

    $sql = "UPDATE inventory SET item_name='$name', category='$category', quantity=$quantity, unit='$unit', location='$location', notes='$notes' WHERE id=$id";
    if (!$conn->query($sql)) {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'DELETE' && $action === 'delete') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        exit;
    }

    $sql = "DELETE FROM inventory WHERE id = $id";
    if (!$conn->query($sql)) {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
