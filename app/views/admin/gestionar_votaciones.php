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
            max-width: 1400px;
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

        .agrupador-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            font-family: monospace;
        }

        .tipo-dependiente {
            font-size: 12px;
            color: #666;
            font-style: italic;
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
            color: #667eea;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
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
            <h1>Gestión de Votaciones</h1>
            <p>Sistema de administración de votaciones institucionales</p>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($votaciones); ?></div>
                <div class="stat-label">Total Votaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_unique(array_column($votaciones, 'AGRUPADOR'))); ?></div>
                <div class="stat-label">Agrupadores Únicos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_unique(array_column($votaciones, 'FACULTAD'))); ?></div>
                <div class="stat-label">Facultades</div>
            </div>
        </div>

        <!-- Lista de votaciones -->
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title" style="margin-bottom: 0;">Votaciones Registradas</h2>
                <a href="crear_votacion.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nueva Votación
                </a>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Solicitud</th>
                            <th>Facultad</th>
                            <th>Tipo Dependiente</th>
                            <th>Agrupador</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($votaciones as $votacion): ?>
                        <tr>
                            <td><?php echo $votacion['ID_TIPO_SOLICITUD']; ?></td>
                            <td>
                                <strong><?php echo substr($votacion['TIPO_SOLICITUD'], 0, 50); ?></strong>
                                <?php if(strlen($votacion['TIPO_SOLICITUD']) > 50): ?>
                                    <span style="color: #666;">...</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $votacion['FACULTAD']; ?></td>
                            <td>
                                <span class="tipo-dependiente"><?php echo $votacion['TIPO_DEPENDIENTE']; ?></span>
                            </td>
                            <td>
                                <span class="agrupador-badge"><?php echo $votacion['AGRUPADOR']; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($votacion['FECHA_INICIO'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($votacion['FECHA_FIN'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($votacion['ESTADO']); ?>">
                                    <?php echo $votacion['ESTADO']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($votacion['ESTADO'] == 'PENDIENTE'): ?>
                                        <button class="btn btn-warning" onclick="editarVotacion(<?php echo $votacion['ID_TIPO_SOLICITUD']; ?>)">Editar</button>
                                        <button class="btn btn-danger" onclick="eliminarVotacion(<?php echo $votacion['ID_TIPO_SOLICITUD']; ?>)">Eliminar</button>
                                    <?php endif; ?>
                                    <button class="btn btn-primary" onclick="verDetalles(<?php echo $votacion['ID_TIPO_SOLICITUD']; ?>)">Ver</button>
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
        function editarVotacion(id) {
            // Redirigir a la página de edición
            window.location.href = `editar_votacion.php?id=${id}`;
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
                    formData.append('id_tipo_solicitud', id);
                    
                    fetch('/prueba_votaciones/app/ajax/votacionAjax.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta eliminación:', data); // 👈 Debug
                        Swal.fire({
                            title: data.titulo,
                            text: data.texto,
                            icon: data.icono
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
                }
            });
        }

        function verDetalles(id) {
            fetch(`/prueba_votaciones/app/ajax/votacionAjax.php?accion=obtener_votacion&id_votacion=${id}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data); // 👈 Debug
                if (data) {
                    const contenido = `
                        <div style="margin-bottom: 15px;">
                            <strong>ID:</strong> ${data.ID_TIPO_SOLICITUD}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Tipo de Solicitud:</strong><br>
                            ${data.TIPO_SOLICITUD}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Servicio:</strong> ${data.SERVICIO}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Facultad:</strong> ${data.FACULTAD}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Tipo de Dependiente:</strong> ${data.TIPO_DEPENDIENTE}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Agrupador:</strong> 
                            <span class="agrupador-badge">${data.AGRUPADOR}</span>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Fecha de Inicio:</strong> ${new Date(data.FECHA_INICIO).toLocaleDateString('es-ES')}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Fecha de Fin:</strong> ${new Date(data.FECHA_FIN).toLocaleDateString('es-ES')}
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>Estado:</strong> 
                            <span class="status-badge status-${data.ESTADO.toLowerCase()}">${data.ESTADO}</span>
                        </div>
                    `;
                    
                    document.getElementById('contenidoDetalles').innerHTML = contenido;
                    document.getElementById('modalDetalles').style.display = 'block';
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudieron cargar los detalles de la votación',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al cargar los detalles',
                    icon: 'error'
                });
            });
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

        // Función para filtrar la tabla
        function filtrarTabla() {
            const input = document.getElementById('filtroTabla');
            const filtro = input.value.toUpperCase();
            const tabla = document.querySelector('.table tbody');
            const filas = tabla.getElementsByTagName('tr');

            for (let i = 0; i < filas.length; i++) {
                const fila = filas[i];
                const celdas = fila.getElementsByTagName('td');
                let mostrar = false;

                for (let j = 0; j < celdas.length; j++) {
                    const celda = celdas[j];
                    if (celda && celda.textContent.toUpperCase().indexOf(filtro) > -1) {
                        mostrar = true;
                        break;
                    }
                }

                fila.style.display = mostrar ? '' : 'none';
            }
        }
    </script>
</body>
</html>
