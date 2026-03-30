<?php
/**
 * api/skills.php
 * Endpoint AJAX untuk mengambil data keahlian
 * 
 * Method: GET
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Data keahlian (simulasi database)
$skills = [
    // Frontend
    [
        'id'       => 1,
        'name'     => 'HTML & CSS',
        'level'    => 95,
        'category' => 'Frontend',
    ],
    [
        'id'       => 2,
        'name'     => 'JavaScript (ES6+)',
        'level'    => 85,
        'category' => 'Frontend',
    ],
    [
        'id'       => 3,
        'name'     => 'UI/UX Design',
        'level'    => 88,
        'category' => 'Design',
    ],
    // Backend
    [
        'id'       => 4,
        'name'     => 'PHP',
        'level'    => 80,
        'category' => 'Backend',
    ],
    [
        'id'       => 5,
        'name'     => 'MySQL & Database',
        'level'    => 78,
        'category' => 'Backend',
    ],
    [
        'id'       => 6,
        'name'     => 'Laravel Framework',
        'level'    => 75,
        'category' => 'Backend',
    ],
    // Other
    [
        'id'       => 7,
        'name'     => 'React.js',
        'level'    => 72,
        'category' => 'Frontend',
    ],
    [
        'id'       => 8,
        'name'     => 'Git & Version Control',
        'level'    => 82,
        'category' => 'DevOps',
    ],
];

$response = [
    'success' => true,
    'count'   => count($skills),
    'skills'  => $skills,
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
