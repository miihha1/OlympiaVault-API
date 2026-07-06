<?php
require_once __DIR__ . '/../../app/api.php';
require_once __DIR__ . '/../../app/olympians.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Povolená je iba metóda POST.', 405);
}

api_require_jwt_user();
$pdo = db();

try {
    $items = null;

    if (isset($_FILES['json_file'])) {
        $file = $_FILES['json_file'];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            api_error('JSON súbor sa nepodarilo nahrať.', 400);
        }

        $raw = file_get_contents((string)$file['tmp_name']);
        $items = json_decode((string)$raw, true);
    } else {
        $payload = api_read_json_body();
        $items = $payload['items'] ?? $payload;
    }

    if (!is_array($items)) {
        api_error('Očakáva sa JSON pole olympionikov.', 400);
    }

    $result = olympians_import_json($pdo, $items);
    $status = empty($result['errors']) ? 201 : 207;

    api_success([
        'created' => $result['created'],
        'errors' => $result['errors'],
    ], $status);
} catch (Throwable $e) {
    api_error('Import zlyhal: ' . $e->getMessage(), 500);
}
