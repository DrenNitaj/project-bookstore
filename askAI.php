<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Initialize or reset daily question count
if (!isset($_SESSION['questions_today']) || !isset($_SESSION['questions_date']) || $_SESSION['questions_date'] !== date('Y-m-d')) {
    $_SESSION['questions_today'] = 0;
    $_SESSION['questions_date'] = date('Y-m-d');
}

// Check daily question limit
if ($_SESSION['questions_today'] >= 3) {
    echo json_encode(["error" => "You have reached your daily question limit (3 questions). Please try again tomorrow."]);
    exit;
}

include_once("config.php");

// Get book details and question
$book_id = $_POST['book_id'] ?? null;
$question = $_POST['question'] ?? null;

if (!$book_id || !$question) {
    echo json_encode(["error" => "Missing data"]);
    exit;
}

// Fetch book info
$sql = $conn->prepare("SELECT title, author_name FROM books WHERE book_id = :book_id");
$sql->bindParam(':book_id', $book_id, PDO::PARAM_INT);
$sql->execute();
$book = $sql->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo json_encode(["error" => "Book not found"]);
    exit;
}

// Cohere API Key
$apiKey = COHERE_API_KEY; // Replace this with your actual key

$prompt = "The user asks a question about the book titled '{$book['title']}' by {$book['author_name']}'. Only respond if the question is directly related to this book's content, author, characters, or themes. If it's unrelated, respond with: 'Sorry, I can only answer questions about the specific book mentioned.' Otherwise, provide exactly 3 short and complete sentences. Make sure the full response fits comfortably within 200 tokens. Question: {$question}";

$ch = curl_init('https://api.cohere.ai/v1/generate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Cohere-Version: 2022-12-06',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'command',
    'prompt' => $prompt,
    'max_tokens' => 200,
    'temperature' => 0.7
]));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}

curl_close($ch);

if (isset($error_msg)) {
    echo json_encode(['error' => 'Curl error: ' . $error_msg]);
    exit;
}

// Decode response
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON response from AI']);
    exit;
}

if (isset($data['generations'][0]['text'])) {
    $answer = trim($data['generations'][0]['text']);

    // Check if AI response is the fallback (off-topic)
    $fallback_response = "Sorry, I can only answer questions about the specific book mentioned.";
    if (stripos($answer, $fallback_response) !== false) {
        echo json_encode(["error" => $fallback_response]);
        exit;
    }

    // Valid answer: increment question count
    $_SESSION['questions_today']++;

    // Initialize chat history array for the current book if not set
    if (!isset($_SESSION['chat_history'][$book_id])) {
        $_SESSION['chat_history'][$book_id] = [];
    }

    // Save Q&A pair under that book
    $_SESSION['chat_history'][$book_id][] = [
        'question' => $question,
        'answer' => $answer,
        'time' => date('H:i:s')
    ];

    // Get only the chat history for this book
    $book_history = $_SESSION['chat_history'][$book_id];

    echo json_encode([
        'answer' => $answer,
        'history' => $book_history
    ]);
} else {
    // Output full API response for debugging
    echo json_encode([
        'error' => 'AI response missing expected data',
        'response' => $data
    ]);
}


