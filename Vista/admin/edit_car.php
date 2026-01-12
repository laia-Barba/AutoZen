<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Coche - AutoZen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-color:#FF6B35; --light-bg:#F8F9FA; --dark-color:#2C3E50; }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); }
        .container-card { background:#fff; border-radius:15px; padding:25px; box-shadow:0 10px 30px rgba(0,0,0,.08); margin-top:30px; }
        .form-title { font-weight:700; margin-bottom:20px; }
        .btn-primary-custom { background: var(--primary-color); color:#fff; border:none; font-weight:600; padding:10px 18px; border-radius:8px; }
        .btn-primary-custom:hover { background:#f35a22; }
        .btn-secondary-custom { background:#6c757d; color:#fff; border:none; font-weight:600; padding:10px 18px; border-radius:8px; }
        .btn-secondary-custom:hover { background:#555; }

        .thumb-grid { display:flex; flex-wrap:wrap; gap:14px; }
        .thumb-card { width: 180px; background:#fff; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,.07); padding:12px; }
        .thumb-img { width:100%; height:110px; border-radius:10px; background:#f1f3f5; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .thumb-img img { width:100%; height:100%; object-fit:contain; object-position:center; background:#fff; }
        .thumb-actions { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:10px; }
        .thumb-actions .form-check { margin:0; }
        .thumb-delete { display:flex; align-items:center; gap:8px; }
        .section-title { font-weight:800; color: var(--dark-color); }
    </style>
</head>
<body>
    <div class="container mt-4 d-flex gap-2">
        <a href="index.php?action=manageCars" class="btn btn-secondary-custom"><i class="fas fa-arrow-left me-2"></i>Volver</a>
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
            <h2 class="form-title">Editar Coche (ID <?php echo (int)$vehiculo['idVehiculo']; ?>)</h2>

            <form action="index.php?action=editCar&id=<?php echo (int)$vehiculo['idVehiculo']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idVehiculo" value="<?php echo (int)$vehiculo['idVehiculo']; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Marca</label>
                        <select name="idMarca" id="idMarca" class="form-select" required>
                            <option value="">Seleccione marca</option>
                            <?php foreach ($marcas as $m): ?>
                                <option value="<?php echo $m['id']; ?>" <?php echo ((int)$vehiculo['idMarca']==(int)$m['id'])?'selected':''; ?>>
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
                        <input type="number" name="anio" class="form-control" min="1900" max="2100" value="<?php echo htmlspecialchars($vehiculo['año'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kilómetros</label>
                        <input type="number" name="km" class="form-control" min="0" value="<?php echo htmlspecialchars($vehiculo['km'] ?? '0'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" min="0" value="<?php echo htmlspecialchars($vehiculo['precio'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Combustible</label>
                        <select name="combustible" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php foreach (['Gasolina','Diesel','Eléctrico','Híbrido'] as $c): ?>
                                <option value="<?php echo $c; ?>" <?php echo (($vehiculo['combustible'] ?? '')==$c)?'selected':''; ?>><?php echo $c; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Color</label>
                        <select name="color" class="form-select" required>
                            <?php foreach (['Negro','Blanco','Rojo','Gris','Azul','Beige','Morado','Verde','Amarillo'] as $color): ?>
                                <option value="<?php echo $color; ?>" <?php echo (($vehiculo['color'] ?? '')==$color)?'selected':''; ?>><?php echo $color; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cambio</label>
                        <select name="cambio" class="form-select" required>
                            <?php foreach (['Manual','Auto'] as $cambio): ?>
                                <option value="<?php echo $cambio; ?>" <?php echo (($vehiculo['cambio'] ?? '')==$cambio)?'selected':''; ?>><?php echo $cambio; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Consumo (l/100km)</label>
                        <input type="number" step="0.01" name="consumo" class="form-control" value="<?php echo htmlspecialchars($vehiculo['consumo'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Potencia (CV)</label>
                        <input type="number" name="potencia" class="form-control" min="0" value="<?php echo htmlspecialchars($vehiculo['potencia'] ?? '0'); ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Motor</label>
                        <input type="text" name="motor" class="form-control" value="<?php echo htmlspecialchars($vehiculo['motor'] ?? ''); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" rows="4" class="form-control"><?php echo htmlspecialchars($vehiculo['descripcion'] ?? ''); ?></textarea>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="section-title mb-2">Imágenes actuales</h5>
                            <small class="text-muted">Marca "Ppal." para definir la imagen principal</small>
                        </div>

                        <?php if (empty($imagenes)): ?>
                            <div class="alert alert-info">Este coche no tiene imágenes todavía.</div>
                        <?php else: ?>
                            <div class="thumb-grid">
                                <?php foreach ($imagenes as $img): ?>
                                    <?php
                                        $ruta = $img['ruta'] ?? '';
                                        $imgUrl = '';
                                        if (!empty($ruta)) {
                                            $pos = stripos($ruta, 'uploads');
                                            if ($pos !== false) {
                                                $relative = str_replace('\\', '/', substr($ruta, $pos));
                                                $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                                                $imgUrl = $base . '/' . ltrim($relative, '/');
                                            } else {
                                                $imgUrl = $ruta;
                                            }
                                        }
                                        $radioVal = 'existing_' . (int)$img['idImagen'];
                                        $checked = !empty($img['esPrincipal']);
                                    ?>
                                    <div class="thumb-card">
                                        <div class="thumb-img">
                                            <?php if (!empty($imgUrl)): ?>
                                                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="Imagen">
                                            <?php else: ?>
                                                <div class="text-muted small">Sin imagen</div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="thumb-actions">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="imagen_principal" id="ppal_<?php echo (int)$img['idImagen']; ?>" value="<?php echo htmlspecialchars($radioVal); ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="ppal_<?php echo (int)$img['idImagen']; ?>">Ppal.</label>
                                            </div>

                                            <label class="thumb-delete">
                                                <input type="checkbox" name="delete_images[]" value="<?php echo (int)$img['idImagen']; ?>">
                                                <span class="text-danger">Eliminar</span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 mt-2">
                        <h5 class="section-title mb-2">Añadir nuevas imágenes</h5>
                        <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple id="imageUpload">
                        <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-3"></div>
                        <small class="text-muted d-block mt-2">Si quieres que una nueva sea la principal, marca "Ppal." en la vista previa.</small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Guardar cambios</button>
                    <a href="index.php?action=manageCars" class="btn btn-secondary-custom">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const marca = document.getElementById('idMarca');
        const modelo = document.getElementById('idModelo');
        function loadModelos(idMarca, preselect){
            if(!idMarca){ modelo.innerHTML = '<option value="">Seleccione modelo</option>'; return; }
            fetch('index.php?action=getModelos&marca='+encodeURIComponent(idMarca))
                .then(r=>r.json())
                .then(data=>{
                    modelo.innerHTML = '<option value="">Seleccione modelo</option>';
                    if(Array.isArray(data)){
                        data.forEach(m=>{
                            const opt=document.createElement('option');
                            opt.value=m.id; opt.textContent=m.nombre;
                            if(preselect && String(preselect)===String(m.id)) opt.selected=true;
                            modelo.appendChild(opt);
                        });
                    }
                })
                .catch(()=>{ modelo.innerHTML = '<option value="">Error cargando modelos</option>'; });
        }

        marca.addEventListener('change', ()=> loadModelos(marca.value));
        if(marca.value){ loadModelos(marca.value, '<?php echo htmlspecialchars($vehiculo['idModelo'] ?? ''); ?>'); }

        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');

        imageUpload.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            const files = this.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'position-relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.height = '110px';
                    img.style.width = 'auto';
                    img.style.borderRadius = '10px';

                    const radioContainer = document.createElement('div');
                    radioContainer.className = 'form-check position-absolute top-0 start-0 p-2 bg-white rounded';

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'imagen_principal';
                    radio.value = 'new_' + i;
                    radio.className = 'form-check-input';

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

        // Si no hay principal marcado en existentes, marca la primera existente como principal por defecto
        const principalChecked = document.querySelector('input[name="imagen_principal"]:checked');
        if (!principalChecked) {
            const firstExisting = document.querySelector('input[name="imagen_principal"][value^="existing_"]');
            if (firstExisting) firstExisting.checked = true;
        }
    });
    </script>
</body>
</html>
