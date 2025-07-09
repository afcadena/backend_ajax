<?php
require_once __DIR__ . "/../../controllers/votacionController.php";
use app\controllers\votacionController;

$insVotacion = new votacionController();
$votaciones = $insVotacion->listarVotacionesControlador();
$tipos_votacion = $insVotacion->obtenerTiposVotacion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Votaciones</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-bottom: 3px solid #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f8f9ff;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-activa {
            background-color: #d4edda;
            color: #155724;
        }

        .status-finalizada {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelada {
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

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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
            <h1>Gestión de Votaciones</h1>
            <p>Sistema de administración de votaciones institucionales</p>
        </div>

        <!-- Formulario para crear/editar votación -->
        <div class="section">
            <h2 class="section-title">Crear Nueva Votación</h2>
            <form id="formVotacion">
                <input type="hidden" id="id_votacion" name="id_votacion">
                <input type="hidden" name="usuario_creador" value="admin">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="titulo">Título de la Votación *</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_tipo_votacion">Tipo de Votación *</label>
                        <select id="id_tipo_votacion" name="id_tipo_votacion" required>
                            <option value="">Seleccione un tipo</option>
                            <?php foreach($tipos_votacion as $tipo): ?>
                                <option value="<?php echo $tipo['ID_TIPO_VOTACION']; ?>">
                                    <?php echo $tipo['NOMBRE_TIPO']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_facultad">Facultad *</label>
                        <select id="id_facultad" name="id_facultad" required>
                            <option value="">Seleccione una facultad</option>
                            <?php foreach($facultades as $facultad): ?>
                                <option value="<?php echo $facultad['ID_FACULTAD']; ?>">
                                    <?php echo $facultad['NOMBRE_FACULTAD']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha y Hora de Inicio *</label>
                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_fin">Fecha y Hora de Fin *</label>
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin" required>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Crear Votación</button>
                    <button type="button" class="btn btn-secondary" id="btnCancelar" onclick="limpiarFormulario()">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Lista de votaciones -->
        <div class="section">
            <h2 class="section-title">Votaciones Registradas</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Facultad</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Planchas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($votaciones as $votacion): ?>
                        <tr>
                            <td><?php echo $votacion['ID_VOTACION']; ?></td>
                            <td><?php echo $votacion['TITULO']; ?></td>
                            <td><?php echo $votacion['NOMBRE_TIPO']; ?></td>
                            <td><?php echo $votacion['NOMBRE_FACULTAD']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($votacion['FECHA_INICIO'])); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($votacion['FECHA_FIN'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($votacion['ESTADO']); ?>">
                                    <?php echo $votacion['ESTADO']; ?>
                                </span>
                            </td>
                            <td><?php echo $votacion['total_planchas']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($votacion['ESTADO'] == 'PENDIENTE'): ?>
                                        <button class="btn btn-warning" onclick="editarVotacion(<?php echo $votacion['ID_VOTACION']; ?>)">Editar</button>
                                        <button class="btn btn-danger" onclick="eliminarVotacion(<?php echo $votacion['ID_VOTACION']; ?>)">Eliminar</button>
                                    <?php endif; ?>
                                    <button class="btn btn-primary" onclick="verDetalles(<?php echo $votacion['ID_VOTACION']; ?>)">Ver</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div id="modalDetalles" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detalles de la Votación</h3>
                <span class="close" onclick="cerrarModal('modalDetalles')">&times;</span>
            </div>
            <div id="contenidoDetalles"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let modoEdicion = false;

        document.getElementById('formVotacion').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const accion = modoEdicion ? 'modificar_votacion' : 'crear_votacion';
            formData.append('accion', accion);
            
            fetch('../votacionAjax.php', {
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

        function editarVotacion(id) {
            fetch(`../votacionAjax.php?accion=obtener_votacion&id_votacion=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('id_votacion').value = data.ID_VOTACION;
                    document.getElementById('titulo').value = data.TITULO;
                    document.getElementById('id_tipo_votacion').value = data.ID_TIPO_VOTACION;
                    document.getElementById('id_facultad').value = data.ID_FACULTAD;
                    document.getElementById('fecha_inicio').value = data.FECHA_INICIO.replace(' ', 'T');
                    document.getElementById('fecha_fin').value = data.FECHA_FIN.replace(' ', 'T');
                    
                    document.getElementById('btnGuardar').textContent = 'Modificar Votación';
                    document.querySelector('.section-title').textContent = 'Modificar Votación';
                    modoEdicion = true;
                    
                    document.getElementById('formVotacion').scrollIntoView({ behavior: 'smooth' });
                }
            });
        }

        function eliminarVotacion(id) {
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
                    formData.append('accion', 'eliminar_votacion');
                    formData.append('id_votacion', id);
                    
                    fetch('../votacionAjax.php', {
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

        function verDetalles(id) {
            fetch(`../votacionAjax.php?accion=obtener_votacion&id_votacion=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const contenido = `
                        <div class="form-group">
                            <strong>Título:</strong> ${data.TITULO}
                        </div>
                        <div class="form-group">
                            <strong>Tipo:</strong> ${data.NOMBRE_TIPO}
                        </div>
                        <div class="form-group">
                            <strong>Facultad:</strong> ${data.NOMBRE_FACULTAD}
                        </div>
                        <div class="form-group">
                            <strong>Fecha de Inicio:</strong> ${new Date(data.FECHA_INICIO).toLocaleString()}
                        </div>
                        <div class="form-group">
                            <strong>Fecha de Fin:</strong> ${new Date(data.FECHA_FIN).toLocaleString()}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong> <span class="status-badge status-${data.ESTADO.toLowerCase()}">${data.ESTADO}</span>
                        </div>
                        <div class="form-group">
                            <strong>Creado por:</strong> ${data.USUARIO_CREADOR}
                        </div>
                        <div class="form-group">
                            <strong>Fecha de Creación:</strong> ${new Date(data.FECHA_CREACION).toLocaleString()}
                        </div>
                    `;
                    
                    document.getElementById('contenidoDetalles').innerHTML = contenido;
                    document.getElementById('modalDetalles').style.display = 'block';
                }
            });
        }

        function limpiarFormulario() {
            document.getElementById('formVotacion').reset();
            document.getElementById('id_votacion').value = '';
            document.getElementById('btnGuardar').textContent = 'Crear Votación';
            document.querySelector('.section-title').textContent = 'Crear Nueva Votación';
            modoEdicion = false;
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
