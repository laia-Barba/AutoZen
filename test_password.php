<?php
// Simple password test script
require_once 'app/Core/Database.php';
require_once 'Modelo/UserModel.php';

$testEmail = 'edgarluaces@gmail.com';
$testPassword = 'edgar12345';

echo "<h2>Password Verification Test</h2>";

try {
    $userModel = new UserModel();
    
    echo "<p>Testing with email: $testEmail</p>";
    echo "<p>Testing password: $testPassword</p>";
    
    // Find user
    $user = $userModel->buscarPorCorreo($testEmail);
    
    if ($user) {
        echo "<h3>User Found:</h3>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        echo "<h3>Password Test:</h3>";
        echo "<p>Stored password hash: " . $user['Contraseña'] . "</p>";
        echo "<p>Password verify result: " . (password_verify($testPassword, $user['Contraseña']) ? 'SUCCESS' : 'FAILED') . "</p>";
        
        // Test with wrong password
        echo "<p>Wrong password test: " . (password_verify('wrongpassword', $user['Contraseña']) ? 'UNEXPECTED SUCCESS' : 'EXPECTED FAILURE') . "</p>";
        
        // Test creating a new hash
        echo "<h3>New Hash Test:</h3>";
        $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
        echo "<p>New hash: $newHash</p>";
        echo "<p>New hash verify: " . (password_verify($testPassword, $newHash) ? 'SUCCESS' : 'FAILED') . "</p>";
        
    } else {
        echo "<p style='color: red;'>User not found in database!</p>";
        
        // Check if user exists with different query
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE Correo = ? LIMIT 1");
        $stmt->execute([$testEmail]);
        $rawUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($rawUser) {
            echo "<h3>Raw Query Found User:</h3>";
            echo "<pre>";
            print_r($rawUser);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>No user found with raw query either!</p>";
            
            // List all users
            $stmt = $db->prepare("SELECT idUsuario, Nombre, Correo FROM usuarios LIMIT 5");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Sample Users in Database:</h3>";
            echo "<pre>";
            print_r($users);
            echo "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
