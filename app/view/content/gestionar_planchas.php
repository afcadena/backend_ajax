<?php
require_once __DIR__ . "/../../controllers/planchaController.php";
require_once __DIR__ . "/../../controllers/votacionController.php";
use app\controllers\planchaController;
use app\controllers\votacionController;

$insplancha = new planchaController();
$insVotacion = new votacionController();
$planchas = $insplancha->listarPlanchasControlador();
$preguntas_votacion = $insplancha->obtenerPreguntasVotacion();
$estadisticas = $insplancha->obtenerEstadisticasPlanchas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Planchas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #28a745;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .table th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f8fff9;
        }

        .image-preview {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .no-image {
            width: 60px;
            height: 40px;
            background-color: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .agrupador-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            font-family: monospace;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .modal-title {
            font-size: 1.5em;
            color: #333;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 8px 12px;
            font-size: 12px;
        }

        .url-preview {
            max-width: 300px;
            word-break: break-all;
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .table-container {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Planchas</h1>
            <p>Sistema de administración de opciones de votación</p>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['total_planchas']; ?></div>
                <div class="stat-label">Total Planchas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['con_imagen']; ?></div>
                <div class="stat-label">Con Imagen</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['votaciones_asociadas']; ?></div>
                <div class="stat-label">Votaciones</div>
            </div>
        </div>

        <!-- Formulario para crear/editar plancha -->
        <div class="section">
            <h2 class="section-title">Crear Nueva Plancha</h2>
            <form id="formPlancha">
                <input type="hidden" id="id_opcion_pregunta" name="id_opcion_pregunta">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_solicitud_pregunta">Pregunta de Votación *</label>
                        <select id="id_solicitud_pregunta" name="id_solicitud_pregunta" required>
                            <option value="">Seleccione una pregunta</option>
                            <?php foreach($preguntas_votacion as $pregunta): ?>
                                <option value="<?php echo $pregunta['ID_SOLICITUD_PREGUNTA']; ?>">
                                    <?php echo $pregunta['TIPO_SOLICITUD'] . ' - ' . $pregunta['PREGUNTA']; ?>
                                    <span class="agrupador-badge"><?php echo $pregunta['AGRUPADOR']; ?></span>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="opcion">Nombre de la Plancha *</label>
                        <input type="text" id="opcion" name="opcion" required placeholder="Ej: Plancha Verde, Lista 1, etc.">
                    </div>
                </div>

                <div class="form-group">
                    <label for="url">URL de la Imagen</label>
                    <input type="url" id="url" name="url" placeholder="https://ejemplo.com/imagen.png">
                    <small style="color: #666;">URL completa de la imagen de la plancha (opcional)</small>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="btnGuardarPlancha">Crear Plancha</button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFormularioPlancha()">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Lista de planchas -->
        <div class="section">
            <h2 class="section-title">Planchas Registradas</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Pregunta</th>
                            <th>Votación</th>
                            <th>Agrupador</th>
                            <th>URL</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($planchas as $plancha): ?>
                        <tr>
                            <td><?php echo $plancha['ID_OPCION_PREGUNTA']; ?></td>
                            <td>
                                <?php if(!empty($plancha['URL'])): ?>
                                    <img src="<?php echo $plancha['URL']; ?>" alt="<?php echo $plancha['OPCION']; ?>" class="image-preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="no-image" style="display: none;">Sin imagen</div>
                                <?php else: ?>
                                    <div class="no-image">Sin imagen</div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo $plancha['OPCION']; ?></strong></td>
                            <td><?php echo substr($plancha['PREGUNTA'], 0, 30) . (strlen($plancha['PREGUNTA']) > 30 ? '...' : ''); ?></td>
                            <td><?php echo substr($plancha['TIPO_SOLICITUD'], 0, 40) . (strlen($plancha['TIPO_SOLICITUD']) > 40 ? '...' : ''); ?></td>
                            <td>
                                <span class="agrupador-badge"><?php echo $plancha['AGRUPADOR']; ?></span>
                            </td>
                            <td>
                                <?php if(!empty($plancha['URL'])): ?>
                                    <div class="url-preview"><?php echo $plancha['URL']; ?></div>
                                <?php else: ?>
                                    <span style="color: #999;">Sin URL</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning" onclick="editarPlancha(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)">Editar</button>
                                    <button class="btn btn-danger" onclick="eliminarPlancha(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)">Eliminar</button>
                                    <button class="btn btn-primary" onclick="verDetallesPlancha(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)">Ver</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para detalles de plancha -->
    <div id="modalDetallesPlancha" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detalles de la Plancha</h3>
                <span class="close" onclick="cerrarModal('modalDetallesPlancha')">&times;</span>
            </div>
            <div id="contenidoDetallesPlancha"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let modoEdicionPlancha = false;

        // Formulario de planchas
        document.getElementById('formPlancha').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const accion = modoEdicionPlancha ? 'modificar_plancha' : 'crear_plancha';
            formData.append('accion', accion);
            
            fetch('../planchaAjax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.titulo,
                    text: data.texto,
                    icon: data.icono,
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    if (data.tipo === 'limpiar' || data.icono === 'success') {
                        location.reload();
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error'
                });
            });
        });

        function editarPlancha(id) {
            fetch(`../planchaAjax.php?accion=obtener_plancha&id_plancha=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('id_opcion_pregunta').value = data.ID_OPCION_PREGUNTA;
                    document.getElementById('id_solicitud_pregunta').value = data.ID_SOLICITUD_PREGUNTA;
                    document.getElementById('opcion').value = data.OPCION;
                    document.getElementById('url').value = data.URL || '';
                    
                    document.getElementById('btnGuardarPlancha').textContent = 'Modificar Plancha';
                    document.querySelector('.section-title').textContent = 'Modificar Plancha';
                    modoEdicionPlancha = true;
                    
                    document.getElementById('formPlancha').scrollIntoView({ behavior: 'smooth' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudieron cargar los datos de la plancha',
                    icon: 'error'
                });
            });
        }

        function eliminarPlancha(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('accion', 'eliminar_plancha');
                    formData.append('id_opcion_pregunta', id);
                    
                    fetch('../planchaAjax.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            title: data.titulo,
                            text: data.texto,
                            icon: data.icono
                        }).then(() => {
                            if (data.icono === 'success') {
                                location.reload();
                            }
                        });
                    });
                }
            });
        }

        function verDetallesPlancha(id) {
            fetch(`../planchaAjax.php?accion=obtener_plancha&id_plancha=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const imagenHtml = data.URL ? 
                        `<img src="${data.URL}" alt="${data.OPCION}" style="max-width: 200px; border-radius: 8px; margin: 10px 0;" onerror="this.style.display='none';">` : 
                        '<p style="color: #666;">Sin imagen</p>';
                    
                    const contenido = `
                        <div style="margin-bottom: 15px;">
                            <strong>ID:</strong> ${data.ID_OPCION_PREGUNTA}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Nombre:</strong> ${data.OPCION}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Pregunta:</strong> ${data.PREGUNTA}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Votación:</strong> ${data.TIPO_SOLICITUD}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Agrupador:</strong> 
                            <span class="agrupador-badge">${data.AGRUPADOR}</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>URL:</strong> ${data.URL || 'Sin URL'}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Imagen:</strong><br>
                            ${imagenHtml}
                        </div>
                    `;
                    
                    document.getElementById('contenidoDetallesPlancha').innerHTML = contenido;
                    document.getElementById('modalDetallesPlancha').style.display = 'block';
                }
            });
        }

        function limpiarFormularioPlancha() {
            document.getElementById('formPlancha').reset();
            document.getElementById('id_opcion_pregunta').value = '';
            document.getElementById('btnGuardarPlancha').textContent = 'Crear Plancha';
            document.querySelector('.section-title').textContent = 'Crear Nueva Plancha';
            modoEdicionPlancha = false;
        }

        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modales = document.querySelectorAll('.modal');
            modales.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Preview de URL en tiempo real
        document.getElementById('url').addEventListener('input', function() {
            const url = this.value;
            if (url && url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                // Mostrar preview si es una imagen válida
                console.log('URL de imagen válida:', url);
            }
        });
    </script>
</body>
</html>
