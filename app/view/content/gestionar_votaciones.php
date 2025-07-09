<?php
require_once "../controllers/solicitudController.php";
use app\controllers\solicitudController;

$insSolicitud = new solicitudController();
$solicitudes = $insSolicitud->listarSolicitudesControlador();
$tipos_solicitud = $insSolicitud->obtenerTiposSolicitud();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solicitudes</title>
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
            <h1>Gestión de Solicitudes</h1>
            <p>Sistema de administración de solicitudes institucionales</p>
        </div>

        <!-- Formulario para crear/editar votación -->
        <div class="section">
            <h2 class="section-title">Crear Nueva Solicitud</h2>
            <form id="formSolicitud" class="FormularioAjax" method="POST" action="../solicitudAjax.php">
        <input type="hidden" id="id_solicitud" name="id_solicitud">
        <input type="hidden" name="usuario" value="admin">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="destino">Destino *</label>
                <input type="text" id="destino" name="destino" required>
            </div>
            
            <div class="form-group">
                <label for="id_tipo_solicitud">Tipo de Solicitud *</label>
                <select id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                    <option value="">Seleccione un tipo</option>
                    <?php foreach($tipos_solicitud as $tipo): ?>
                        <option value="<?php echo $tipo['ID_TIPO_SOLICITUD']; ?>">
                            <?php echo $tipo['TIPO_SOLICITUD']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="motivo">Motivo *</label>
            <textarea id="motivo" name="motivo" rows="3" required></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="periodo_inicio">Fecha y Hora de Inicio *</label>
                <input type="datetime-local" id="periodo_inicio" name="periodo_inicio" required>
            </div>
            
            <div class="form-group">
                <label for="periodo_fin">Fecha y Hora de Fin *</label>
                <input type="datetime-local" id="periodo_fin" name="periodo_fin" required>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-primary" id="btnGuardar">Crear Solicitud</button>
            <button type="button" class="btn btn-secondary" id="btnCancelar" onclick="limpiarFormulario()">Cancelar</button>
        </div>
    </form>
</div>

        <!-- Lista de votaciones -->
        <div class="section">
            <h2 class="section-title">Solicitudes Registradas</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                <tr>
                    <th>ID</th>
                    <th>Destino</th>
                    <th>Tipo</th>
                    <th>Motivo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($solicitudes as $solicitud): ?>
                <tr>
                    <td><?php echo $solicitud['ID_SOLICITUD']; ?></td>
                    <td><?php echo $solicitud['DESTINO']; ?></td>
                    <td><?php echo $solicitud['TIPO_SOLICITUD']; ?></td>
                    <td><?php echo substr($solicitud['MOTIVO'], 0, 50) . (strlen($solicitud['MOTIVO']) > 50 ? '...' : ''); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($solicitud['PERIODO_INICIO'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($solicitud['PERIODO_FIN'])); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($solicitud['ESTADO']); ?>">
                            <?php echo $solicitud['ESTADO']; ?>
                        </span>
                    </td>
                    <td><?php echo $solicitud['USUARIO']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if($solicitud['ESTADO'] == 'PENDIENTE'): ?>
                                <button class="btn btn-warning" onclick="editarSolicitud(<?php echo $solicitud['ID_SOLICITUD']; ?>)">Editar</button>
                                <button class="btn btn-danger" onclick="eliminarSolicitud(<?php echo $solicitud['ID_SOLICITUD']; ?>)">Eliminar</button>
                            <?php endif; ?>
                            <button class="btn btn-primary" onclick="verDetalles(<?php echo $solicitud['ID_SOLICITUD']; ?>)">Ver</button>
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
            <h3 class="modal-title">Detalles de la Solicitud</h3>
            <span class="close" onclick="cerrarModal('modalDetalles')">&times;</span>
        </div>
        <div id="contenidoDetalles"></div>
    </div>
</div>

    <script src="../../sweetalert2.all.min.js"></script>
<script>
    let modoEdicion = false;

    // Sistema AJAX unificado
    document.addEventListener('DOMContentLoaded', function() {
        let formularios_ajax = document.querySelectorAll(".FormularioAjax");
        
        formularios_ajax.forEach(formulario => {
            formulario.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres realizar esta acción?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let datos = new FormData(formulario);
                        const accion = modoEdicion ? 'modificar_solicitud' : 'crear_solicitud';
                        datos.append('accion', accion);
                        
                        fetch(formulario.getAttribute("action"), {
                            method: "POST",
                            body: datos
                        })
                        .then(respuesta => respuesta.json())
                        .then(respuesta => {
                            return alertas_ajax(respuesta);
                        });
                    }
                });
            });
        });
    });

    function alertas_ajax(alerta) {
        if (alerta.tipo == "simple") {
            Swal.fire({
                icon: alerta.icono,
                title: alerta.titulo,
                text: alerta.texto,
                confirmButtonText: 'Aceptar'
            });
        } else if (alerta.tipo == "recargar") {
            Swal.fire({
                icon: alerta.icono,
                title: alerta.titulo,
                text: alerta.texto,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        } else if (alerta.tipo == "limpiar") {
            Swal.fire({
                icon: alerta.icono,
                title: alerta.titulo,
                text: alerta.texto,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelectorAll(".FormularioAjax").forEach(form => form.reset());
                    if (alerta.id_votacion) {
                        mostrarSeccionPreguntas(alerta.id_votacion);
                    }
                    location.reload();
                }
            });
        } else if (alerta.tipo == "redireccionar") {
            window.location.href = alerta.url;
        }
    }

    function editarSolicitud(id) {
        fetch(`../solicitudAjax.php?accion=obtener_solicitud&id_solicitud=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('id_solicitud').value = data.ID_SOLICITUD;
                document.getElementById('destino').value = data.DESTINO;
                document.getElementById('motivo').value = data.MOTIVO;
                document.getElementById('id_tipo_solicitud').value = data.ID_TIPO_SOLICITUD;
                document.getElementById('periodo_inicio').value = data.PERIODO_INICIO.replace(' ', 'T');
                document.getElementById('periodo_fin').value = data.PERIODO_FIN.replace(' ', 'T');
                
                document.getElementById('btnGuardar').textContent = 'Modificar Solicitud';
                document.querySelector('.section-title').textContent = 'Modificar Solicitud';
                modoEdicion = true;
                
                document.getElementById('formSolicitud').scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    function eliminarSolicitud(id) {
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
                formData.append('accion', 'eliminar_solicitud');
                formData.append('id_solicitud', id);
                
                fetch('../solicitudAjax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alertas_ajax(data);
                });
            }
        });
    }

    function verDetalles(id) {
        fetch(`../solicitudAjax.php?accion=obtener_solicitud&id_solicitud=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                const contenido = `
                    <div class="form-group">
                        <strong>Destino:</strong> ${data.DESTINO}
                    </div>
                    <div class="form-group">
                        <strong>Tipo:</strong> ${data.TIPO_SOLICITUD}
                    </div>
                    <div class="form-group">
                        <strong>Motivo:</strong> ${data.MOTIVO}
                    </div>
                    <div class="form-group">
                        <strong>Fecha de Inicio:</strong> ${new Date(data.PERIODO_INICIO).toLocaleString()}
                    </div>
                    <div class="form-group">
                        <strong>Fecha de Fin:</strong> ${new Date(data.PERIODO_FIN).toLocaleString()}
                    </div>
                    <div class="form-group">
                        <strong>Estado:</strong> <span class="status-badge status-${data.ESTADO.toLowerCase()}">${data.ESTADO}</span>
                    </div>
                    <div class="form-group">
                        <strong>Usuario:</strong> ${data.USUARIO}
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
        document.getElementById('formSolicitud').reset();
        document.getElementById('id_solicitud').value = '';
        document.getElementById('btnGuardar').textContent = 'Crear Solicitud';
        document.querySelector('.section-title').textContent = 'Crear Nueva Solicitud';
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
