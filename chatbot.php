<?php
session_start();
include("config.php");

// Show all errors during dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Step 1: Sanitize the incoming message
$msg = trim($_POST['msg'] ?? '');
if ($msg === '') {
    echo "❌ Empty message.";
    exit;
}

$msg_clean = strtolower($msg);
$msg_safe = mysqli_real_escape_string($con, $msg_clean);

// Step 2: Define bot replies
$messages = [
    "hi" => "Hello! 👋 How can I assist you today?",
    "hello" => "Hi there! 😊",
    "register" => "Click 'Register' at the top of the page to create an account.",
    "login" => "Use the 'Login' button at the top to log in.",
    "forgot password" => "Click 'Forgot Password?' on the login screen.",
    "price" => "Prices vary by location. Explore our Properties section!",
    "property types" => "We offer apartments, villas, plots, and commercial listings.",
    "contact" => "📞 +1 243-765-4321 | ✉️ support@realestatest.com",
    "submit property" => "Use 'Submit Property' at the top to list your own.",
    "feedback" => "You can submit feedback via the form at the bottom of the site.",
    "thanks" => "You're welcome! 😊",
    "bye" => "Goodbye! 👋 Have a great day!"
];

// Step 3: Choose response
$reply = $messages[$msg_clean] ?? "I'm not sure how to respond. An agent will contact you shortly. 🧑‍💼";
echo $reply;

// Step 4: Save to DB only if user is logged in
if (isset($_SESSION['uid']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['uid'];
    $role = strtolower(trim($_SESSION['role'])); // capture from session

    // Debug output
    echo "<br>🧪 Debug: UID = $user_id | ROLE = $role";

    // Prepare full message
    $reply_safe = mysqli_real_escape_string($con, $reply);
    $full_msg = "User: $msg_clean | Bot: $reply_safe";
    $safe_msg = mysqli_real_escape_string($con, $full_msg);

    // Final insert query
    $sql = "INSERT INTO chat (user_id, role, message, timestamp)
            VALUES ('$user_id', '$role', '$safe_msg', NOW())";

    if (mysqli_query($con, $sql)) {
        echo "<br>✅ Chat saved!";
    } else {
        echo "<br>❌ DB Error: " . mysqli_error($con);
    }
} else {
    echo "<br>⚠️ You are not logged in. Chat will not be saved.";
}
?>
