<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Coche - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-color:#FF6B35; --light-bg:#F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); }
        .container-card { background:#fff; border-radius:15px; padding:25px; box-shadow:0 10px 30px rgba(0,0,0,.08); margin-top:30px; }
        .form-title { font-weight:700; margin-bottom:20px; }
        .btn-primary-custom { background: var(--primary-color); color:#fff; border:none; font-weight:600; padding:10px 18px; border-radius:8px; }
        .btn-primary-custom:hover { background:#f35a22; }
        .btn-secondary-custom { background:#6c757d; color:#fff; border:none; font-weight:600; padding:10px 18px; border-radius:8px; }
        .btn-secondary-custom:hover { background:#555; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <a href="index.php?action=admin" class="btn btn-secondary-custom"><i class="fas fa-arrow-left me-2"></i>Volver al Panel</a>
    </div>

    <div class="container">
        <?php if (!empty($_SESSION['errores'])): ?>
            <div class="alert alert-danger mt-4">
                <?php foreach($_SESSION['errores'] as $e): ?>
                    <div><?php echo htmlspecialchars($e); ?></div>
                <?php endforeach; unset($_SESSION['errores']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="alert alert-success mt-4">
                <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>

        <div class="container-card">
            <h2 class="form-title">Añadir Nuevo Coche</h2>
            <form action="index.php?action=addCar" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Marca</label>
                        <select name="idMarca" id="idMarca" class="form-select" required>
                            <option value="">Seleccione marca</option>
                            <?php foreach ($marcas as $m): ?>
                                <option value="<?php echo $m['id']; ?>" <?php echo (($_SESSION['datos_formulario']['idMarca'] ?? '')==$m['id'])?'selected':''; ?>>
                                    <?php echo htmlspecialchars($m['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Modelo</label>
                        <select name="idModelo" id="idModelo" class="form-select" required>
                            <option value="">Seleccione modelo</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Año</label>
                        <input type="number" name="anio" class="form-control" min="1900" max="2100" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['anio'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kilómetros</label>
                        <input type="number" name="km" class="form-control" min="0" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['km'] ?? '0'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" min="0" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['precio'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Combustible</label>
                        <select name="combustible" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php foreach (['Gasolina','Diesel','Eléctrico'] as $c): ?>
                                <option value="<?php echo $c; ?>" <?php echo (($_SESSION['datos_formulario']['combustible'] ?? '')==$c)?'selected':''; ?>><?php echo $c; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Color</label>
                        <select name="color" class="form-select" required>
                            <?php foreach (['Negro','Blanco','Rojo','Gris','Azul','Beige','Morado','Verde','Amarillo'] as $color): ?>
                                <option value="<?php echo $color; ?>" <?php echo (($_SESSION['datos_formulario']['color'] ?? '')==$color)?'selected':''; ?>><?php echo $color; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cambio</label>
                        <select name="cambio" class="form-select" required>
                            <?php foreach (['Manual','Auto'] as $cambio): ?>
                                <option value="<?php echo $cambio; ?>" <?php echo (($_SESSION['datos_formulario']['cambio'] ?? '')==$cambio)?'selected':''; ?>><?php echo $cambio; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Consumo (l/100km)</label>
                        <input type="number" step="0.01" name="consumo" class="form-control" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['consumo'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Potencia (CV)</label>
                        <input type="number" name="potencia" class="form-control" min="0" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['potencia'] ?? '0'); ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Motor</label>
                        <input type="text" name="motor" class="form-control" value="<?php echo htmlspecialchars($_SESSION['datos_formulario']['motor'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="4" class="form-control"><?php echo htmlspecialchars($_SESSION['datos_formulario']['descripcion'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Imágenes (múltiples permitidas)</label>
                        <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple id="imageUpload">
                        <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-3"></div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Guardar</button>
                    <a href="index.php?action=admin" class="btn btn-secondary-custom">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <?php unset($_SESSION['datos_formulario']); ?>

    <script>
    // Carga dinámica de modelos por marca
    document.addEventListener('DOMContentLoaded', function(){
        const marca = document.getElementById('idMarca');
        const modelo = document.getElementById('idModelo');
        function loadModelos(idMarca, preselect){
            if(!idMarca){ modelo.innerHTML = '<option value="">Seleccione modelo</option>'; return; }
            // HomeController->getModelos espera el parámetro 'marca'
            fetch('index.php?action=getModelos&marca='+encodeURIComponent(idMarca))
                .then(r=>r.json())
                .then(data=>{
                    modelo.innerHTML = '<option value="">Seleccione modelo</option>';
                    if(Array.isArray(data)){
                        data.forEach(m=>{
                            const opt=document.createElement('option');
                            opt.value=m.id; opt.textContent=m.nombre;
                            if(preselect && preselect==m.id) opt.selected=true;
                            modelo.appendChild(opt);
                        });
                    }
                })
                .catch(()=>{ modelo.innerHTML = '<option value="">Error cargando modelos</option>'; });
        }
        marca.addEventListener('change', ()=> loadModelos(marca.value));
        if(marca.value){ loadModelos(marca.value, '<?php echo htmlspecialchars($_SESSION['datos_formulario']['idModelo'] ?? ''); ?>'); }

        // Image preview with principal selection
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');

        imageUpload.addEventListener('change', function() {
            imagePreview.innerHTML = ''; // Clear previous previews
            const files = this.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create container for image and radio button
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'position-relative';

                    // Create image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.height = '100px';
                    img.style.width = 'auto';
                    img.style.borderRadius = '8px';
                    img.style.marginRight = '10px';

                    // Create radio button for principal image selection
                    const radioContainer = document.createElement('div');
                    radioContainer.className = 'form-check position-absolute top-0 start-0 p-2 bg-white rounded';
                    
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'imagen_principal';
                    radio.value = i; // Use file index as value
                    radio.className = 'form-check-input';
                    if (i === 0) radio.checked = true; // First image is principal by default

                    const label = document.createElement('label');
                    label.className = 'form-check-label small';
                    label.textContent = 'Ppal.';

                    radioContainer.appendChild(radio);
                    radioContainer.appendChild(label);
                    previewContainer.appendChild(img);
                    previewContainer.appendChild(radioContainer);
                    imagePreview.appendChild(previewContainer);
                }
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
</body>
</html>
