<?php
// Comprehensive debugging script
echo "<h1>Fleur System Debug</h1>";

// 1. Basic PHP test
echo "<h2>1. PHP Basic Test</h2>";
echo "<p>PHP is working: " . (function_exists('phpinfo') ? 'YES' : 'NO') . "</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// 2. Server info
echo "<h2>2. Server Information</h2>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// 3. File system test
echo "<h2>3. File System Test</h2>";
$files = [
    '.' => __DIR__,
    'index.php' => __DIR__ . '/index.php',
    'index_simple.php' => __DIR__ . '/index_simple.php',
    'simple.php' => __DIR__ . '/simple.php',
    '.htaccess' => __DIR__ . '/.htaccess',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path) ? 'YES' : 'NO';
    $readable = is_readable($path) ? 'YES' : 'NO';
    echo "<p><strong>$name:</strong> Exists: $exists, Readable: $readable</p>";
}

// 4. Directory listing
echo "<h2>4. Current Directory Contents</h2>";
$files = scandir(__DIR__);
echo "<ul>";
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

// 5. Test database connection
echo "<h2>5. Database Test</h2>";
try {
    $conn = new mysqli('localhost', 'root', '', 'fleur_db');
    if ($conn->connect_error) {
        echo "<p style='color: red;'>Database Connection: FAILED - " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>Database Connection: SUCCESS</p>";
        
        // Test tables
        $tables = ['users', 'products', 'categories'];
        foreach ($tables as $table) {
            $result = $conn->query("SELECT COUNT(*) as count FROM $table");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p>$table table: " . $row['count'] . " records</p>";
            } else {
                echo "<p style='color: orange;'>$table table: Error - " . $conn->error . "</p>";
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database Error: " . $e->getMessage() . "</p>";
}

// 6. Test links
echo "<h2>6. Test Links</h2>";
echo "<p><a href='index.php'>Test index.php</a></p>";
echo "<p><a href='index_simple.php'>Test index_simple.php</a></p>";
echo "<p><a href='simple.php'>Test simple.php</a></p>";
echo "<p><a href='test.php'>Test test.php</a></p>";
echo "<p><a href='info.php'>Test info.php</a></p>";
echo "<p><a href='hello.html'>Test hello.html</a></p>";

// 7. XAMPP specific checks
echo "<h2>7. XAMPP Configuration</h2>";
echo "<p>XAMPP Document Root should be: C:/xampp/htdocs</p>";
echo "<p>Current Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Expected Document Root: C:/xampp/htdocs/FLEUR</p>";

if ($_SERVER['DOCUMENT_ROOT'] !== 'C:/xampp/htdocs/FLEUR') {
    echo "<p style='color: red;'>WARNING: Document root mismatch!</p>";
    echo "<p>You may need to configure Apache to use C:/xampp/htdocs/FLEUR as document root.</p>";
} else {
    echo "<p style='color: green;'>Document root is correct.</p>";
}

echo "<hr>";
echo "<p><strong>Troubleshooting Steps:</strong></p>";
echo "<ol>";
echo "<li>If hello.html works but PHP files don't, PHP is not configured in Apache.</li>";
echo "<li>If nothing works, check XAMPP Apache service is running.</li>";
echo "<li>If Document Root is wrong, configure Apache VirtualHost.</li>";
echo "<li>Check XAMPP Apache error logs: C:/xampp/apache/logs/error.log</li>";
echo "</ol>";
?>
