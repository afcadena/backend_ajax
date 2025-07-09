<?php
require_once "../controllers/planchaController.php";
require_once "../controllers/votacionController.php";
use app\controllers\planchaController;
use app\controllers\votacionController;

$insplancha = new planchaController();
$insVotacion = new votacionController();
$planchas = $insplancha->listarPlanchasControlador();
$votaciones = $insVotacion->listarVotacionesControlador();
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
            max-width: 1200px;
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

        .color-input {
            height: 50px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
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
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f8fff9;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            border: 2px solid #ddd;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-activo {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactivo {
            background-color: #f8d7da;
            color: #721c24;
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
            gap: 10px;
            flex-wrap: wrap;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #28a745;
            border-bottom-color: #28a745;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestión de Planchas</h1>
            <p>Sistema de administración de planchas electorales</p>
        </div>

        <!-- Pestañas -->
        <div class="section">
            <div class="tabs">
                <button class="tab active" onclick="cambiarTab('planchas')">Gestionar Planchas</button>
                <button class="tab" onclick="cambiarTab('asociar')">Asociar a Votaciones</button>
            </div>

            <!-- Tab Gestionar Planchas -->
            <div id="tab-planchas" class="tab-content active">
                <h2 class="section-title">Crear Nueva Plancha</h2>
                <form id="formPlancha">
                    <input type="hidden" id="id_plancha" name="id_plancha">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre_plancha">Nombre de la Plancha *</label>
                            <input type="text" id="nombre_plancha" name="nombre_plancha" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="color_plancha">Color de la Plancha</label>
                            <input type="color" id="color_plancha" name="color_plancha" value="#000000" class="color-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la plancha electoral..."></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary" id="btnGuardarPlancha">Crear Plancha</button>
                        <button type="button" class="btn btn-secondary" onclick="limpiarFormularioPlancha()">Cancelar</button>
                    </div>
                </form>

                <!-- Lista de planchas -->
                <h2 class="section-title" style="margin-top: 40px;">Planchas Registradas</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Color</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Votaciones</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($planchas as $plancha): ?>
                            <tr>
                                <td><?php echo $plancha['ID_PLANCHA']; ?></td>
                                <td><?php echo $plancha['NOMBRE_PLANCHA']; ?></td>
                                <td>
                                    <div class="color-preview" style="background-color: <?php echo $plancha['COLOR_PLANCHA']; ?>"></div>
                                    <?php echo $plancha['COLOR_PLANCHA']; ?>
                                </td>
                                <td><?php echo substr($plancha['DESCRIPCION'], 0, 50) . (strlen($plancha['DESCRIPCION']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($plancha['ESTADO']); ?>">
                                        <?php echo $plancha['ESTADO']; ?>
                                    </span>
                                </td>
                                <td><?php echo $plancha['total_votaciones']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($plancha['FECHA_CREACION'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning" onclick="editarPlancha(<?php echo $plancha['ID_PLANCHA']; ?>)">Editar</button>
                                        <?php if($plancha['total_votaciones'] == 0): ?>
                                            <button class="btn btn-danger" onclick="eliminarPlancha(<?php echo $plancha['ID_PLANCHA']; ?>)">Eliminar</button>
                                        <?php endif; ?>
                                        <button class="btn btn-primary" onclick="verDetallesPlancha(<?php echo $plancha['ID_PLANCHA']; ?>)">Ver</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Asociar Planchas -->
            <div id="tab-asociar" class="tab-content">
                <h2 class="section-title">Asociar Plancha a Votación</h2>
                <form id="formAsociar">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_votacion_asociar">Votación *</label>
                            <select id="id_votacion_asociar" name="id_votacion" required>
                                <option value="">Seleccione una votación</option>
                                <?php foreach($votaciones as $votacion): ?>
                                    <?php if($votacion['ESTADO'] == 'PENDIENTE'): ?>
                                        <option value="<?php echo $votacion['ID_VOTACION']; ?>">
                                            <?php echo $votacion['TITULO'] . ' - ' . $votacion['NOMBRE_FACULTAD']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_plancha_asociar">Plancha *</label>
                            <select id="id_plancha_asociar" name="id_plancha" required>
                                <option value="">Seleccione una plancha</option>
                                <?php foreach($planchas as $plancha): ?>
                                    <?php if($plancha['ESTADO'] == 'ACTIVO'): ?>
                                        <option value="<?php echo $plancha['ID_PLANCHA']; ?>">
                                            <?php echo $plancha['NOMBRE_PLANCHA']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="orden_plancha">Orden de Aparición</label>
                        <input type="number" id="orden_plancha" name="orden_plancha" value="1" min="1">
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-success">Asociar Plancha</button>
                        <button type="button" class="btn btn-secondary" onclick="limpiarFormularioAsociar()">Limpiar</button>
                    </div>
                </form>

                <!-- Lista de asociaciones -->
                <h2 class="section-title" style="margin-top: 40px;">Planchas Asociadas por Votación</h2>
                <div id="listaAsociaciones">
                    <?php foreach($votaciones as $votacion): ?>
                        <?php 
                        $planchas_votacion = $insplancha->obtenerPlanchasVotacion($votacion['ID_VOTACION']);
                        if(!empty($planchas_votacion)): 
                        ?>
                        <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                            <h4><?php echo $votacion['TITULO']; ?> - <?php echo $votacion['NOMBRE_FACULTAD']; ?></h4>
                            <p><strong>Estado:</strong> <span class="status-badge status-<?php echo strtolower($votacion['ESTADO']); ?>"><?php echo $votacion['ESTADO']; ?></span></p>
                            <div class="table-container" style="margin-top: 15px;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Orden</th>
                                            <th>Plancha</th>
                                            <th>Color</th>
                                            <th>Fecha Asociación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($planchas_votacion as $plancha_v): ?>
                                        <tr>
                                            <td><?php echo $plancha_v['ORDEN_PLANCHA']; ?></td>
                                            <td><?php echo $plancha_v['NOMBRE_PLANCHA']; ?></td>
                                            <td>
                                                <div class="color-preview" style="background-color: <?php echo $plancha_v['COLOR_PLANCHA']; ?>"></div>
                                                <?php echo $plancha_v['COLOR_PLANCHA']; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($plancha_v['FECHA_ASOCIACION'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
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

    <script src="../../sweetalert2.all.min.js"></script>
    <script>
        let modoEdicionPlancha = false;

        // Gestión de pestañas
        function cambiarTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Desactivar todas las pestañas
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostrar el contenido seleccionado
            document.getElementById('tab-' + tabName).classList.add('active');
            
            // Activar la pestaña seleccionada
            event.target.classList.add('active');
        }

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

        // Formulario de asociación
        document.getElementById('formAsociar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('accion', 'asociar_plancha');
            
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
                    if (data.icono === 'success') {
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
                    document.getElementById('id_plancha').value = data.ID_PLANCHA;
                    document.getElementById('nombre_plancha').value = data.NOMBRE_PLANCHA;
                    document.getElementById('descripcion').value = data.DESCRIPCION;
                    document.getElementById('color_plancha').value = data.COLOR_PLANCHA;
                    
                    document.getElementById('btnGuardarPlancha').textContent = 'Modificar Plancha';
                    document.querySelector('#tab-planchas .section-title').textContent = 'Modificar Plancha';
                    modoEdicionPlancha = true;
                    
                    // Cambiar a la pestaña de planchas si no está activa
                    cambiarTab('planchas');
                    document.getElementById('formPlancha').scrollIntoView({ behavior: 'smooth' });
                }
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
                    formData.append('id_plancha', id);
                    
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
                    const contenido = `
                        <div class="form-group">
                            <strong>Nombre:</strong> ${data.NOMBRE_PLANCHA}
                        </div>
                        <div class="form-group">
                            <strong>Descripción:</strong> ${data.DESCRIPCION || 'Sin descripción'}
                        </div>
                        <div class="form-group">
                            <strong>Color:</strong> 
                            <div class="color-preview" style="background-color: ${data.COLOR_PLANCHA}; margin-left: 10px;"></div>
                            ${data.COLOR_PLANCHA}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong> <span class="status-badge status-${data.ESTADO.toLowerCase()}">${data.ESTADO}</span>
                        </div>
                        <div class="form-group">
                            <strong>Fecha de Creación:</strong> ${new Date(data.FECHA_CREACION).toLocaleString()}
                        </div>
                        ${data.FECHA_MODIFICACION ? `
                        <div class="form-group">
                            <strong>Última Modificación:</strong> ${new Date(data.FECHA_MODIFICACION).toLocaleString()}
                        </div>
                        ` : ''}
                    `;
                    
                    document.getElementById('contenidoDetallesPlancha').innerHTML = contenido;
                    document.getElementById('modalDetallesPlancha').style.display = 'block';
                }
            });
        }

        function limpiarFormularioPlancha() {
            document.getElementById('formPlancha').reset();
            document.getElementById('id_plancha').value = '';
            document.getElementById('color_plancha').value = '#000000';
            document.getElementById('btnGuardarPlancha').textContent = 'Crear Plancha';
            document.querySelector('#tab-planchas .section-title').textContent = 'Crear Nueva Plancha';
            modoEdicionPlancha = false;
        }

        function limpiarFormularioAsociar() {
            document.getElementById('formAsociar').reset();
            document.getElementById('orden_plancha').value = '1';
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
    </script>
</body>
</html>
