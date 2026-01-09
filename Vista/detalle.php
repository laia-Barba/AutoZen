<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Vehículo - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-color:#FF6B35; --dark-color:#2C3E50; --light-bg:#F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); }
        .detail-hero { margin-top: 90px; }
        .detail-card { background:#fff; border-radius:18px; box-shadow:0 12px 30px rgba(0,0,0,.08); overflow:hidden; }
        .detail-title { font-weight: 800; color: var(--dark-color); margin:0; }
        .detail-price { font-weight: 900; color: var(--primary-color); font-size: 2rem; }
        .spec-pill { background: #f6f7f9; border-radius: 12px; padding: 10px 12px; display:flex; gap:10px; align-items:center; }
        .spec-pill i { color: var(--primary-color); }
        .btn-primary-custom { background: var(--primary-color); border: none; color:#fff; font-weight:700; padding: 12px 18px; border-radius: 12px; text-decoration:none; display:inline-block; }
        .btn-primary-custom:hover { background:#f35a22; color:#fff; text-decoration:none; }
        .btn-admin-secondary { border-radius: 12px; font-weight: 700; padding: 12px 18px; }
        .thumbs { display:flex; gap:10px; overflow:auto; padding-bottom:6px; }
        .thumb { width: 90px; height: 64px; border-radius: 10px; background:#f1f3f5; overflow:hidden; flex: 0 0 auto; cursor:pointer; border: 2px solid transparent; }
        .thumb img { width:100%; height:100%; object-fit:contain; background:#fff; }
        .thumb.active { border-color: var(--primary-color); }
        .main-image { width: 100%; height: 420px; background:#f1f3f5; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .main-image img { width:100%; height:100%; object-fit:contain; background:#fff; }
        .section-title { font-weight: 800; color: var(--dark-color); }
    </style>
</head>
<body>
<?php
    // Helper para convertir ruta guardada en BD a URL web
    $toWebUrl = function($path) {
        if (empty($path)) return '';
        if (strpos($path, 'http') === 0) return $path;

        $p = str_replace('\\', '/', (string)$path);
        if (($pos = stripos($p, '/htdocs/')) !== false) {
            $rel = substr($p, $pos + strlen('/htdocs/'));
            $url = '/' . ltrim($rel, '/');
        } elseif (($pos = stripos($p, '/AutoZen/')) !== false) {
            $rel = substr($p, $pos);
            $url = $rel;
        } else {
            $url = '/' . ltrim($p, '/');
        }

        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($base && strpos($url, $base) !== 0) {
            $url = $base . $url;
        }
        return $url;
    };

    $nombre = trim(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? ''));

    $imagenesList = [];
    foreach (($imagenes ?? []) as $img) {
        $r = $img['ruta'] ?? '';
        $u = $toWebUrl($r);
        if ($u) $imagenesList[] = $u;
    }

    if (empty($imagenesList)) {
        $fallback = $vehiculo['imagen'] ?? '';
        $u = $toWebUrl($fallback);
        if ($u) {
            $imagenesList[] = $u;
        } else {
            $imagenesList[] = 'https://via.placeholder.com/900x600/FF6B35/FFFFFF?text=' . urlencode($nombre ?: 'AutoZen');
        }
    }

    $mainImg = $imagenesList[0] ?? '';
?>

<div class="container detail-hero">
    <div class="mb-3">
        <a href="javascript:history.back()" class="btn btn-light" style="border-radius: 12px;">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="detail-card">
        <div class="row g-0">
            <div class="col-lg-7">
                <div class="main-image" id="mainImageWrap">
                    <img id="mainImage" src="<?php echo htmlspecialchars($mainImg); ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
                </div>
                <div class="p-3">
                    <div class="thumbs" id="thumbs">
                        <?php foreach ($imagenesList as $idx => $url): ?>
                            <div class="thumb <?php echo $idx === 0 ? 'active' : ''; ?>" data-src="<?php echo htmlspecialchars($url); ?>">
                                <img src="<?php echo htmlspecialchars($url); ?>" alt="Miniatura">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 p-lg-5">
                    <h1 class="detail-title mb-2"><?php echo htmlspecialchars($nombre ?: 'Vehículo'); ?></h1>
                    <div class="detail-price mb-4"><?php echo number_format((float)($vehiculo['precio'] ?? 0), 0, ',', '.'); ?>€</div>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-calendar"></i><span><?php echo htmlspecialchars((string)($vehiculo['año'] ?? '')); ?></span></div>
                        </div>
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-tachometer-alt"></i><span><?php echo number_format((int)($vehiculo['km'] ?? 0), 0, ',', '.'); ?> km</span></div>
                        </div>
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-bolt"></i><span><?php echo number_format((int)($vehiculo['potencia'] ?? 0), 0, ',', '.'); ?> CV</span></div>
                        </div>
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-gas-pump"></i><span><?php echo htmlspecialchars((string)($vehiculo['combustible'] ?? '')); ?></span></div>
                        </div>
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-cog"></i><span><?php echo htmlspecialchars((string)($vehiculo['cambio'] ?? '')); ?></span></div>
                        </div>
                        <div class="col-6">
                            <div class="spec-pill"><i class="fas fa-palette"></i><span><?php echo htmlspecialchars((string)($vehiculo['color'] ?? '')); ?></span></div>
                        </div>
                        <div class="col-12">
                            <div class="spec-pill"><i class="fas fa-engine-warning"></i><span><?php echo htmlspecialchars((string)($vehiculo['motor'] ?? '')); ?></span></div>
                        </div>
                    </div>

                    <a class="btn-primary-custom w-100 text-center" href="index.php?action=buscar">
                        <i class="fas fa-car me-2"></i>Ver más coches
                    </a>

                    <?php if (isset($esAdmin) && $esAdmin): ?>
                        <div class="mt-3 d-grid gap-2">
                            <a class="btn btn-outline-primary btn-admin-secondary w-100" href="index.php?action=editCar&id=<?php echo (int)($vehiculo['idVehiculo'] ?? 0); ?>">
                                <i class="fas fa-edit me-2"></i>Editar coche
                            </a>

                            <form method="POST" action="index.php?action=deleteCar" onsubmit="return confirm('¿Seguro que quieres eliminar este coche?');">
                                <input type="hidden" name="idVehiculo" value="<?php echo (int)($vehiculo['idVehiculo'] ?? 0); ?>">
                                <button type="submit" class="btn btn-outline-danger btn-admin-secondary w-100">
                                    <i class="fas fa-trash me-2"></i>Eliminar coche
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-4 p-lg-5" style="border-top: 1px solid #eef0f2;">
            <h5 class="section-title mb-2">Descripción</h5>
            <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars((string)($vehiculo['descripcion'] ?? ''))); ?></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const mainImage = document.getElementById('mainImage');
    const thumbs = document.getElementById('thumbs');
    if (!thumbs) return;

    thumbs.addEventListener('click', function(e){
        const t = e.target.closest('.thumb');
        if (!t) return;
        const src = t.getAttribute('data-src');
        if (!src) return;
        mainImage.src = src;
        thumbs.querySelectorAll('.thumb').forEach(x => x.classList.remove('active'));
        t.classList.add('active');
    });
});
</script>
</body>
</html>
