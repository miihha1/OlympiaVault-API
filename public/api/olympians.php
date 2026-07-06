<?php
require_once __DIR__ . '/../../app/api.php';
require_once __DIR__ . '/../../app/olympians.php';

$pdo = db();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        api_require_jwt_user();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $athlete = olympians_get_full($pdo, $id);
            if (!$athlete) {
                api_error('Olympionik sa nenašiel.', 404);
            }

            api_success(['data' => $athlete]);
        }

        $filters = [
            'type' => $_GET['type'] ?? null,
            'year' => $_GET['year'] ?? null,
            'placing' => $_GET['placing'] ?? null,
            'sport' => $_GET['sport'] ?? null,
            'q' => $_GET['q'] ?? null,
        ];
        $items = olympians_list($pdo, $filters);

        api_success([
            'filters' => $filters,
            'count' => count($items),
            'data' => $items,
        ]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        api_require_jwt_user();
        $created = olympians_create($pdo, api_read_json_body());
        api_success(['data' => $created], 201);
    }

    if (in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'], true)) {
        api_require_jwt_user();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            api_error('Chýba ID olympionika.', 400);
        }

        $updated = olympians_update($pdo, $id, api_read_json_body());
        api_success(['data' => $updated]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        api_require_jwt_user();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            api_error('Chýba ID olympionika.', 400);
        }

        if (!olympians_delete($pdo, $id)) {
            api_error('Olympionik sa nenašiel.', 404);
        }

        api_success(['deleted_id' => $id]);
    }

    api_error('Nepodporovaná HTTP metóda.', 405);
} catch (InvalidArgumentException $e) {
    api_error($e->getMessage(), 422);
} catch (RuntimeException $e) {
    api_error($e->getMessage(), 404);
} catch (PDOException $e) {
    api_error('Databázová chyba: ' . $e->getMessage(), 500);
} catch (Throwable $e) {
    api_error('Interná chyba servera: ' . $e->getMessage(), 500);
}
