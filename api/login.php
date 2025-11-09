<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ✅ Include database connection
include_once __DIR__ . '/../apps/connect/db.php';

// ✅ Read JSON input
$input = json_decode(file_get_contents('php://input'), true);

// ✅ Validate input
if (!isset($input['mobno'], $input['password']) || empty($input['mobno']) || empty($input['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$mobno = trim($input['mobno']);
$password = trim($input['password']);

try {
    // ✅ Fetch user
    $stmt = $db->prepare("SELECT U_ID, NAME, MOB_NO, PASSWORD, RANK, UNIT, STATUS FROM user WHERE MOB_NO = :mobno");
    $stmt->bindParam(':mobno', $mobno);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $hashedPassword = $user['PASSWORD'];

        // ✅ Debug check (temporary)
        // file_put_contents('debug_login.txt', "Entered: $password | DB: $hashedPassword\n", FILE_APPEND);

        // ✅ Verify hashed password
        // if (password_verify($password, $hashedPassword)) {
        if ($password === $hashedPassword) {
            $authkey = bin2hex(random_bytes(16));

            // ✅ Update authkey
            $updateStmt = $db->prepare("UPDATE user SET AUTHKEY = :authkey WHERE MOB_NO = :mobno");
            $updateStmt->bindParam(':authkey', $authkey);
            $updateStmt->bindParam(':mobno', $mobno);
            $updateStmt->execute();

            unset($user['PASSWORD']); // remove password from response

            echo json_encode([
                'status' => 'success',
                'authkey' => $authkey,
                'user' => $user
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} catch (Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
}
?>
