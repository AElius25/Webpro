<?php
/**
 * api/projects.php
 * Endpoint AJAX untuk mengambil data proyek
 * 
 * Method: GET
 * Params: filter (semua|web|ui|app), page (int)
 */

// CORS headers (untuk development)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Hanya terima AJAX request
if (
    empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden — AJAX only.']);
    exit;
}

// Parameter
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'semua';
$page   = isset($_GET['page'])   ? max(1, intval($_GET['page'])) : 1;
$limit  = 6;

// Validasi filter
$allowed_filters = ['semua', 'web', 'ui', 'app'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'semua';
}

// Data proyek (simulasi database)
$all_projects = [
    [
        'id'          => 1,
        'title'       => 'SiMarket — E-Commerce Platform',
        'description' => 'Platform belanja online lengkap dengan cart, checkout, dan dashboard admin real-time menggunakan AJAX.',
        'category'    => 'web',
        'emoji'       => '🛒',
        'featured'    => true,
        'stack'       => ['PHP', 'MySQL', 'JavaScript', 'AJAX', 'Bootstrap'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-11-01',
    ],
    [
        'id'          => 2,
        'title'       => 'MedTrack — Health Dashboard',
        'description' => 'Dashboard monitoring kesehatan pribadi dengan visualisasi data interaktif dan notifikasi real-time.',
        'category'    => 'ui',
        'emoji'       => '💊',
        'featured'    => false,
        'stack'       => ['Figma', 'React', 'Chart.js'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-09-15',
    ],
    [
        'id'          => 3,
        'title'       => 'TaskFlow — Project Manager',
        'description' => 'Aplikasi manajemen proyek Kanban dengan fitur kolaborasi tim dan update status real-time.',
        'category'    => 'app',
        'emoji'       => '📋',
        'featured'    => false,
        'stack'       => ['Laravel', 'Vue.js', 'WebSocket', 'MySQL'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-08-20',
    ],
    [
        'id'          => 4,
        'title'       => 'LinguaAI — Language Learning',
        'description' => 'Platform belajar bahasa berbasis AI dengan gamifikasi, quiz adaptif, dan pelacakan kemajuan.',
        'category'    => 'app',
        'emoji'       => '🌍',
        'featured'    => false,
        'stack'       => ['Python', 'FastAPI', 'React Native', 'PostgreSQL'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-07-10',
    ],
    [
        'id'          => 5,
        'title'       => 'UrbanSpace — Arsitektur Studio',
        'description' => 'Website portofolio studio arsitektur premium dengan galeri interaktif dan animasi halaman yang memukau.',
        'category'    => 'web',
        'emoji'       => '🏢',
        'featured'    => false,
        'stack'       => ['HTML5', 'CSS3', 'GSAP', 'Three.js'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-06-05',
    ],
    [
        'id'          => 6,
        'title'       => 'FoodieApp — Restoran UI Kit',
        'description' => 'UI Kit lengkap untuk aplikasi pemesanan makanan dengan 80+ komponen dan panduan desain sistem.',
        'category'    => 'ui',
        'emoji'       => '🍜',
        'featured'    => false,
        'stack'       => ['Figma', 'Adobe XD', 'Storybook'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-05-20',
    ],
    [
        'id'          => 7,
        'title'       => 'SiPOS — Sistem Kasir Digital',
        'description' => 'Sistem point-of-sale untuk UMKM dengan laporan penjualan, manajemen stok, dan cetak struk.',
        'category'    => 'web',
        'emoji'       => '🏪',
        'featured'    => false,
        'stack'       => ['PHP Native', 'MySQL', 'AJAX', 'CSS3'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-04-15',
    ],
    [
        'id'          => 8,
        'title'       => 'TravelMate — Trip Planner',
        'description' => 'Aplikasi perencanaan perjalanan dengan rekomendasi destinasi berbasis preferensi pengguna.',
        'category'    => 'app',
        'emoji'       => '✈️',
        'featured'    => false,
        'stack'       => ['Flutter', 'Dart', 'Firebase', 'Google Maps API'],
        'demo'        => '#',
        'github'      => '#',
        'created_at'  => '2024-03-08',
    ],
];

// Filter berdasarkan kategori
if ($filter !== 'semua') {
    $filtered = array_values(array_filter($all_projects, function ($p) use ($filter) {
        return $p['category'] === $filter;
    }));
} else {
    $filtered = $all_projects;
}

// Pagination
$total    = count($filtered);
$offset   = ($page - 1) * $limit;
$projects = array_slice($filtered, $offset, $limit);
$has_more = ($offset + $limit) < $total;

// Response
$response = [
    'success'  => true,
    'filter'   => $filter,
    'page'     => $page,
    'total'    => $total,
    'hasMore'  => $has_more,
    'projects' => $projects,
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
