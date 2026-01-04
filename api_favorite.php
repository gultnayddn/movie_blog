<?php
include 'config.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Giriş yapmalısınız']);
    exit;
}

$action = $_POST['action'] ?? '';
$show_id = intval($_POST['show_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if (empty($action) || $show_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Parametre eksik']);
    exit;
}

if ($action === 'add') {
    $sql = "INSERT IGNORE INTO favorites (user_id, show_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $show_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Favorilere eklendi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hata']);
    }
} elseif ($action === 'remove') {
    $sql = "DELETE FROM favorites WHERE user_id = ? AND show_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $show_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Favorilerden çıkarıldı']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hata']);
    }
} elseif ($action === 'check') {
    $sql = "SELECT id FROM favorites WHERE user_id = ? AND show_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $show_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $is_favorite = $result->num_rows > 0;
    echo json_encode(['success' => true, 'is_favorite' => $is_favorite]);
}
?>
