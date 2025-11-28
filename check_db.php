<?php
require_once 'app/Core/Database.php';

use App\Core\Database;

$db_status = 'error';
$db_message = '';
$db_details = [];

try {
    $pdo = Database::getInstance();
    $db_status = 'connected';
    $db_message = 'Conexión exitosa a la base de datos';
    
    // Obtener información de la conexión
    $db_details = [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'name' => getenv('DB_NAME') ?: '',
        'user' => getenv('DB_USER') ?: 'root',
        'port' => getenv('DB_PORT') ?: '3306',
        'driver' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
        'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
    ];
    
    // Probar una consulta simple
    $stmt = $pdo->query('SELECT 1 as test');
    $result = $stmt->fetch();
    
} catch (Exception $e) {
    $db_status = 'error';
    $db_message = 'Error de conexión: ' . $e->getMessage();
    $db_details = [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'name' => getenv('DB_NAME') ?: '',
        'user' => getenv('DB_USER') ?: 'root',
        'port' => getenv('DB_PORT') ?: '3306'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de la Base de Datos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        .status-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .status-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        
        .status-icon.connected {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .status-icon.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .status-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .status-message {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .status-title.connected {
            color: #059669;
        }
        
        .status-title.error {
            color: #dc2626;
        }
        
        .details {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .details h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #374151;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 500;
            color: #6b7280;
        }
        
        .detail-value {
            font-family: 'Courier New', monospace;
            color: #111827;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        
        .refresh-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .refresh-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .refresh-btn:active {
            transform: translateY(0);
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            .status-title {
                font-size: 24px;
            }
            
            .detail-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-header">
            <div class="status-icon <?php echo $db_status; ?>">
                <?php echo $db_status === 'connected' ? '✓' : '✗'; ?>
            </div>
            <h1 class="status-title <?php echo $db_status; ?>">
                <?php echo $db_status === 'connected' ? 'Conectado' : 'Error de Conexión'; ?>
            </h1>
            <p class="status-message"><?php echo htmlspecialchars($db_message); ?></p>
        </div>
        
        <div class="details">
            <h3>Detalles de la Conexión</h3>
            <div class="detail-item">
                <span class="detail-label">Host:</span>
                <span class="detail-value"><?php echo htmlspecialchars($db_details['host']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Base de Datos:</span>
                <span class="detail-value"><?php echo htmlspecialchars($db_details['name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Usuario:</span>
                <span class="detail-value"><?php echo htmlspecialchars($db_details['user']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Puerto:</span>
                <span class="detail-value"><?php echo htmlspecialchars($db_details['port']); ?></span>
            </div>
            <?php if ($db_status === 'connected'): ?>
                <div class="detail-item">
                    <span class="detail-label">Driver:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($db_details['driver']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Versión:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($db_details['version']); ?></span>
                </div>
            <?php endif; ?>
        </div>
        
        <button class="refresh-btn" onclick="location.reload()">
            Verificar Nuevamente
        </button>
    </div>
</body>
</html>
