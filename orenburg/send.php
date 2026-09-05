<?php
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name']) || !isset($data['phone'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Заполните все обязательные поля'
    ]);
    exit;
}

$name = htmlspecialchars(trim($data['name'] ?? ""));
$phone = htmlspecialchars(trim($data['phone'] ?? ""));
$email = htmlspecialchars(trim($data['email'] ?? ""));
$message = htmlspecialchars(trim($data['message'] ?? ""));

if (strlen($name) < 2) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Имя слишком короткое']);
    exit;
}

// ============================================
// 📧 КРАСИВОЕ HTML-ПИСЬМО ЧЕРЕЗ MAIL()
// ============================================

$to = 'info@mediator-yurist.ru';
$subject = '=?UTF-8?B?' . base64_encode('📬 Новая заявка с сайта mediator-yurist.ru') . '?=';

// HTML-тело письма
$htmlBody = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background: #f7f9fc;
        }
        .email-wrapper {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: 20px;
        }
        .email-header {
            background: linear-gradient(135deg, #1E3A8A, #2563EB);
            padding: 30px 40px;
            color: #ffffff;
            text-align: center;
        }
        .email-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .email-header .sub {
            font-size: 14px;
            opacity: 0.85;
            margin-top: 6px;
            display: block;
        }
        .email-body {
            padding: 35px 40px 25px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 25px;
        }
        .field {
            margin-bottom: 20px;
            padding: 16px 20px;
            background: #F8FAFC;
            border-radius: 12px;
            border-left: 4px solid #2563EB;
        }
        .field-label {
            font-size: 12px;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .field-value {
            font-size: 16px;
            color: #1F2937;
            font-weight: 500;
            word-break: break-word;
        }
        .field-value a {
            color: #1E40AF;
            text-decoration: none;
        }
        .field-value a:hover {
            text-decoration: underline;
        }
        .field-value .message-text {
            white-space: pre-wrap;
            font-weight: 400;
            line-height: 1.7;
            background: #ffffff;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 6px;
            border: 1px solid #E5E7EB;
            display: block;
        }
        .email-footer {
            padding: 20px 40px 30px;
            border-top: 2px solid #F3F4F6;
            text-align: center;
        }
        .email-footer p {
            font-size: 13px;
            color: #9CA3AF;
            margin: 4px 0;
        }
        .email-footer .brand {
            color: #1E3A8A;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            background: #10B981;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 30px;
            margin-top: 6px;
            letter-spacing: 0.3px;
        }
        .btn-reply {
            display: inline-block;
            background: #1E40AF;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
        }
        .btn-reply:hover {
            background: #1E3A8A;
        }
        @media (max-width: 480px) {
            .email-header { padding: 25px 20px; }
            .email-body { padding: 25px 20px; }
            .email-footer { padding: 20px; }
            .field { padding: 14px 16px; }
            .email-header h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>📬 Новая заявка с сайта</h1>
            <span class="sub">mediator-yurist.ru</span>
        </div>
        <div class="email-body">
            <div class="greeting">👋 Поступила новая заявка</div>
            
            <div class="field">
                <div class="field-label">👤 Имя</div>
                <div class="field-value">' . $name . '</div>
            </div>
            
            <div class="field" style="border-left-color: #F59E0B;">
                <div class="field-label">📞 Телефон</div>
                <div class="field-value"><a href="tel:' . $phone . '">' . $phone . '</a></div>
            </div>
            
            <div class="field" style="border-left-color: #8B5CF6;">
                <div class="field-label">✉️ Email</div>
                <div class="field-value">' . ($email ? '<a href="mailto:' . $email . '">' . $email . '</a>' : 'не указан') . '</div>
            </div>
            
            <div class="field" style="border-left-color: #EF4444;">
                <div class="field-label">📝 Сообщение</div>
                <div class="field-value">
                    <span class="message-text">' . ($message ?: 'не указано') . '</span>
                </div>
            </div>
            
            ' . ($email ? '
            <div style="text-align:center; margin: 25px 0 10px;">
                <a href="mailto:' . $email . '?subject=Ответ на заявку с сайта" class="btn-reply">
                    💬 Ответить клиенту
                </a>
            </div>
            ' : '') . '
        </div>
        <div class="email-footer">
            <p>📅 ' . date('d.m.Y в H:i') . '</p>
            <p>🌐 <span class="brand">mediator-yurist.ru</span></p>
            <span class="badge">✅ Новая заявка</span>
        </div>
    </div>
</body>
</html>
';

// Текстовая версия для старых почтовых клиентов
$textBody = "📬 Новая заявка с сайта mediator-yurist.ru\n\n"
    . "👤 Имя: {$name}\n"
    . "📞 Телефон: {$phone}\n"
    . "✉️ Email: {$email}\n"
    . "📝 Сообщение: {$message}\n\n"
    . "---\n"
    . "📅 " . date('d.m.Y H:i') . "\n"
    . "🌐 mediator-yurist.ru";

// ============================================
// 📧 ЗАГОЛОВКИ ДЛЯ HTML-ПИСЬМА
// ============================================
$boundary = '----=_NextPart_' . md5(time());

$headers = "From: info@mediator-yurist.ru\r\n";
$headers .= "Reply-To: " . ($email ?: 'info@mediator-yurist.ru') . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// Собираем письмо с HTML и текстовой версией
$body = "--{$boundary}\r\n";
$body .= "Content-Type: text/plain; charset=utf-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $textBody . "\r\n\r\n";

$body .= "--{$boundary}\r\n";
$body .= "Content-Type: text/html; charset=utf-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $htmlBody . "\r\n\r\n";

$body .= "--{$boundary}--";

// ============================================
// 📤 ОТПРАВКА
// ============================================
if (mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'success' => true,
        'message' => 'Заявка успешно отправлена'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Не удалось отправить письмо. Попробуйте позже.'
    ]);
}