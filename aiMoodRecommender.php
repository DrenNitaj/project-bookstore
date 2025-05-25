<?php
session_start();
include_once("config.php");

// Mood request limit: max 3 per day per session
if (!isset($_SESSION['mood_requests_date']) || $_SESSION['mood_requests_date'] !== date('Y-m-d')) {
    $_SESSION['mood_requests_date'] = date('Y-m-d');
    $_SESSION['mood_requests'] = 0;
}
if ($_SESSION['mood_requests'] >= 3) {
    echo json_encode(["error" => "Daily AI limit reached. Try again tomorrow."]);
    exit;
}
$_SESSION['mood_requests']++;

$data = json_decode(file_get_contents('php://input'), true);
$mood = $data['mood'] ?? '';

if (!$mood) {
    http_response_code(400);
    echo json_encode(["error" => "Mood not specified"]);
    exit;
}

// Fetch book data
$stmt = $conn->query("SELECT book_id, title, author_name, category_id, price, cover_image_url FROM books LIMIT 50");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map category IDs to names
$categoryMap = [];
$catStmt = $conn->query("SELECT category_id, name FROM categories");
while ($cat = $catStmt->fetch(PDO::FETCH_ASSOC)) {
    $categoryMap[$cat['category_id']] = $cat['name'];
}
foreach ($books as &$book) {
    $book['category_name'] = $categoryMap[$book['category_id']] ?? 'Unknown';
}

// Build prompt for Cohere
$bookSummaries = array_map(function($b) {
    return "{$b['title']} by {$b['author_name']} (Category: {$b['category_name']})";
}, $books);

$prompt = "I am feeling \"$mood\". From the list below, which 4 books match my mood the best?\n\n";
$prompt .= implode("\n", $bookSummaries);
$prompt .= "\n\nFirst, write one short sentence matching my mood (max 15 words), then list 4 book titles only on new lines with no explanation.";

// Call Cohere API
$ch = curl_init('https://api.cohere.ai/v1/generate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Cohere-Version: 2022-12-06',
    'Authorization: Bearer ' . COHERE_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'command',
    'prompt' => $prompt,
    'max_tokens' => 150,
    'temperature' => 0.7
]));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    curl_close($ch);
    shuffle($books);
    echo json_encode([
        "sentence" => "Here are some picks to match your mood!",
        "books" => array_slice($books, 0, 4)
    ]);
    exit;
}
curl_close($ch);

$data = json_decode($response, true);
$aiReply = trim($data['generations'][0]['text'] ?? '');
$lines = array_filter(array_map('trim', explode("\n", $aiReply)));

$moodSentence = array_shift($lines); // First line is the sentence
$selectedTitles = $lines;

if (empty($selectedTitles)) {
    shuffle($books);
    echo json_encode([
        "sentence" => $moodSentence ?: "Here are some picks to match your mood!",
        "books" => array_slice($books, 0, 4)
    ]);
    exit;
}

// Match AI-suggested titles to actual book records
$selectedBooks = [];
$authorsSeen = [];

foreach ($selectedTitles as $titleLine) {
    foreach ($books as $book) {
        if (stripos($titleLine, $book['title']) !== false) {
            if (!in_array($book['author_name'], $authorsSeen)) {
                $selectedBooks[] = $book;
                $authorsSeen[] = $book['author_name'];
                break;
            }
        }
    }
}

// Fill with additional diverse-author books if fewer than 4
if (count($selectedBooks) < 4) {
    foreach ($books as $book) {
        if (!in_array($book['author_name'], $authorsSeen)) {
            $selectedBooks[] = $book;
            $authorsSeen[] = $book['author_name'];
        }
        if (count($selectedBooks) >= 4) break;
    }
}

// Final response with sentence and books
echo json_encode([
    "sentence" => $moodSentence ?: "Here are some picks to match your mood!",
    "books" => array_slice($selectedBooks, 0, 4)
]);
