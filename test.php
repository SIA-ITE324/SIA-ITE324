<?php
// Simple test file to verify PHP and database connection
echo "<h1>Fleur System Test</h1>";

// Test PHP version
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Test database connection
try {
    $conn = new mysqli('localhost', 'root', '', 'fleur_db');
    if ($conn->connect_error) {
        echo "<p style='color: red;'><strong>Database Connection:</strong> Failed - " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'><strong>Database Connection:</strong> Success!</p>";
        
        // Test if users table exists and has data
        $result = $conn->query("SELECT COUNT(*) as count FROM users");
        $row = $result->fetch_assoc();
        echo "<p><strong>Users in Database:</strong> " . $row['count'] . "</p>";
        
        // Test if products table exists
        $result = $conn->query("SELECT COUNT(*) as count FROM products");
        $row = $result->fetch_assoc();
        echo "<p><strong>Products in Database:</strong> " . $row['count'] . "</p>";
    }
    $conn->close();
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Database Error:</strong> " . $e->getMessage() . "</p>";
}

// Test session
session_start();
$_SESSION['test'] = 'working';
echo "<p><strong>Session Support:</strong> " . (isset($_SESSION['test']) ? 'Working' : 'Not Working') . "</p>";

// Test file paths
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>App Directory:</strong> " . (__DIR__ . '/app') . "</p>";

// Test if required files exist
$requiredFiles = [
    'app/Config/SimpleDatabase.php',
    'app/Controllers/SimpleBaseController.php',
    'app/Controllers/SimpleHome.php',
    'app/Controllers/SimpleAuth.php',
    'app/Models/SimpleUserModel.php'
];

echo "<h3>Required Files:</h3>";
foreach ($requiredFiles as $file) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path) ? '✓' : '✗';
    $color = file_exists($path) ? 'green' : 'red';
    echo "<p style='color: $color;'><strong>$file:</strong> $exists</p>";
}

// Test links
echo "<h3>Test Links:</h3>";
echo "<p><a href='index_simple.php'>Test Simple Index</a></p>";
echo "<p><a href='simple.php'>Test Standalone Version</a></p>";
echo "<p><a href='index.php'>Test Original Index</a></p>";

echo "<hr>";
echo "<p><small>If this page loads correctly, PHP is working. Try the links above to test different versions.</small></p>";
?>
