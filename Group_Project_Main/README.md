One last color simple changes and table fix for

🎨 Color Theme (Ocean) Breakdown
Element	Color Value	Description
Background	#0f2027 → #2c5364	Dark ocean gradient, deep and moody blues
Card Background	rgba(255,255,255,0.08)	Translucent white with 8% opacity (very subtle)
Text	#e8f1f2	Very light teal/white for high contrast text
Button BG	#4dd0e1	Bright cyan (teal) for strong call-to-action
Button Hover	#33c5d9	Slightly darker cyan
Footer Text	#cccccc	Muted light gray

Images Used:
portal - https://unsplash.com/photos/shallow-focus-photography-of-bookshelfs-ggeZ9oyI-PE

login - https://unsplash.com/photos/assorted-books-PBNbMX6jtBM
news - https://unsplash.com/photos/business-newspaper-article-WYd_PkCa1BY
Home - https://unsplash.com/photos/photo-of-open-book-XCwsOj_RUjE

Library used:
Chart.js is a JavaScript charting library
https://cdn.jsdelivr.net/npm/chart.js


icon used:

Student - graduation hat
person - person
email - email
user - user
term - calander
credit - trophie
search - search
content - book
announcement - horn
grad - barchart
submission - upload
location - location
time - clock
participant - group of people
events - calander red
lecturer - lecturer icon
add_material - file
edit - pencil
deadline - alarm
assignment - create
news - newspaper
notice - notice
content  - content
add - add
permission - permission
action - action
remove - delete
creator - person new
catagory - catagory




<?php
require_once __DIR__ . '/../db.php';

// Set JSON response header
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST method is allowed']);
    exit;
}

// Get raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($input['username']) || empty($input['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Username and password required']);
    exit;
}

$username = $input['username'];
$password = $input['password'];

// Fetch user from DB
$user = fetchOne("SELECT * FROM users WHERE username = ?", [$username]);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password']);
    exit;
}

// Create a token or session here (simplified example: JWT recommended)
// For demo, just send back user info (never send password hashes in real apps)
$response = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'is_admin' => (bool)$user['check_admin'],
    'is_student' => (bool)$user['check_student'],
    'is_lecturer' => (bool)$user['check_lecturer'],
    'message' => 'Login successful'
];

// Return JSON response
echo json_encode($response);
