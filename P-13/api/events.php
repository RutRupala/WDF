<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Sample file-based JSON store for guaranteed instant testing even without MySQL running
$dataFile = __DIR__ . '/events_data.json';
if (!file_exists($dataFile)) {
    $initialData = [
        ['id' => 1, 'title' => 'National Cloud Summit 2026', 'category' => 'Technical', 'date' => '2026-11-05', 'venue' => 'Auditorium B', 'seats' => 150],
        ['id' => 2, 'title' => 'CHARUSAT Hack-a-Fest 4.0', 'category' => 'Hackathon', 'date' => '2026-11-18', 'venue' => 'Computing Lab', 'seats' => 80],
        ['id' => 3, 'title' => 'Spoural 2027 Sports Meet', 'category' => 'Sports', 'date' => '2026-12-02', 'venue' => 'Sports Ground', 'seats' => 400]
    ];
    file_put_contents($dataFile, json_encode($initialData, JSON_PRETTY_PRINT));
}

$events = json_decode(file_get_contents($dataFile), true) ?? [];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Optional search filter
        $q = strtolower(trim($_GET['q'] ?? ''));
        if (!empty($q)) {
            $filtered = array_values(array_filter($events, function ($e) use ($q) {
                return str_contains(strtolower($e['title']), $q) || str_contains(strtolower($e['category']), $q);
            }));
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $filtered, 'count' => count($filtered)]);
        } else {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $events, 'count' => count($events)]);
        }
        break;

    case 'POST':
        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true);

        if (!$payload || empty($payload['title']) || empty($payload['category'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Missing title or category in JSON payload']);
            exit;
        }

        $newId = count($events) ? (max(array_column($events, 'id')) + 1) : 1;
        $newEvent = [
            'id' => $newId,
            'title' => htmlspecialchars($payload['title']),
            'category' => htmlspecialchars($payload['category']),
            'date' => htmlspecialchars($payload['date'] ?? date('Y-m-d')),
            'venue' => htmlspecialchars($payload['venue'] ?? 'FTE Campus'),
            'seats' => intval($payload['seats'] ?? 100)
        ];

        $events[] = $newEvent;
        file_put_contents($dataFile, json_encode($events, JSON_PRETTY_PRINT));

        http_response_code(201); // Created
        echo json_encode(['status' => 'success', 'message' => 'Event created asynchronously', 'data' => $newEvent]);
        break;

    case 'DELETE':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Valid id query parameter required']);
            exit;
        }

        $found = false;
        $updated = [];
        foreach ($events as $ev) {
            if ($ev['id'] == $id) {
                $found = true;
            } else {
                $updated[] = $ev;
            }
        }

        if (!$found) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => "Event #{$id} not found"]);
            exit;
        }

        file_put_contents($dataFile, json_encode($updated, JSON_PRETTY_PRINT));
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => "Event #{$id} deleted successfully via AJAX"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        break;
}
?>
