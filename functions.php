<?php
function addAuditLog($userId, $actionType, $description) {
    global $con;
    $actionDate = date('Y-m-d H:i:s');
    $stmt = $con->prepare("INSERT INTO audit_log (USER_ID, ACTION_TYPE, ACTION_DATE, DESCRIPTION) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $actionType, $actionDate, $description);
    $stmt->execute();
}
?>
