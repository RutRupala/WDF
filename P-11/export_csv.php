<?php
require_once __DIR__ . '/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=charusat_students_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Roll No', 'Full Name', 'Email', 'Mobile', 'Department', 'Semester', 'CGPA', 'Status']);

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, roll_no, full_name, email, mobile, department, semester, cgpa, status FROM students ORDER BY id ASC");
        while ($row = $stmt->fetch()) {
            fputcsv($output, $row);
        }
    } catch (Exception $e) {}
} else {
    // Fallback sample export if DB offline
    fputcsv($output, [1, '24IT001', 'Aarav Sharma', 'aarav.s@charusat.edu.in', '9876543210', 'Information Technology', 3, 9.12, 'Active']);
    fputcsv($output, [2, '24CSE015', 'Diya Joshi', 'diya.j@charusat.edu.in', '9825012345', 'Computer Science', 3, 8.94, 'Active']);
}

fclose($output);
exit;
?>
