<?php
require_once __DIR__ . "/../../controllers/votacionController.php";
use app\controllers\votacionController;

$controlador = new votacionController();

// Verificar que se envió un ID
$id = $_GET['id'] ?? null;
if ($id === null) {
    echo "<script>
        alert('ID de votación no proporcionado.');
        window.location.href = 'gestionar_votaciones.php';
    </script>";
    exit;
}

// Obtener datos de la votación
$votacion = $controlador->obtenerVotacionControlador($id);
if (!$votacion) {
    echo "<script>
        alert('No se encontró la votación con ID: $id');
        window.location.href = 'gestionar_votaciones.php';
    </script>";
    exit;
}

// Mapeo inverso para obtener el ID_TIPO_DEPENDIENTE desde TIPO_DEPENDIENTE
$tipo_dependiente_map = [
    'Estudiante' => '1',
    'Docente' => '2',
    'Administrativo' => '3',
    'Sin asignar' => ''
];
$id_tipo_dependiente_actual = $tipo_dependiente_map[$votacion['TIPO_DEPENDIENTE']] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Votación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Administración</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="crear_votacion.php">
                                <i class="fas fa-plus-circle"></i> Crear Votación
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="gestionar_votaciones.php">
                                <i class="fas fa-list"></i> Gestionar Votaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="resultados.php">
                                <i class="fas fa-chart-bar"></i> Resultados
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Editar Votación #<?php echo $votacion['ID_TIPO_SOLICITUD']; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="gestionar_votaciones.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Información de la Votación</h5>
                            </div>
                            <div class="card-body">
                                <form id="formEditarVotacion">
                                    <!-- ID oculto para identificar la votación -->
                                    <input type="hidden" name="id_tipo_solicitud" value="<?php echo $votacion['ID_TIPO_SOLICITUD']; ?>">

                                    <div class="mb-3">
                                        <label for="tipo_solicitud" class="form-label">Tipo de Solicitud *</label>
                                        <input type="text" class="form-control" id="tipo_solicitud" name="tipo_solicitud" 
                                               value="<?php echo htmlspecialchars($votacion['TIPO_SOLICITUD']); ?>" required>
                                    </div>

                                    <input type="hidden" name="servicio" value="<?php echo $votacion['SERVICIO']; ?>">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                                       value="<?php echo $votacion['FECHA_INICIO']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                                       value="<?php echo $votacion['FECHA_FIN']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_tipo_dependiente" class="form-label">Tipo de Dependiente</label>
                                        <select class="form-select" id="id_tipo_dependiente" name="id_tipo_dependiente">
                                            <option value="" <?php echo $id_tipo_dependiente_actual === '' ? 'selected' : ''; ?>>Sin asignar</option>
                                            <option value="1" <?php echo $id_tipo_dependiente_actual === '1' ? 'selected' : ''; ?>>Estudiante</option>
                                            <option value="2" <?php echo $id_tipo_dependiente_actual === '2' ? 'selected' : ''; ?>>Docente</option>
                                            <option value="3" <?php echo $id_tipo_dependiente_actual === '3' ? 'selected' : ''; ?>>Administrativo</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Agrupador Actual</label>
                                        <div class="form-control bg-light" id="agrupador_actual" style="color: #6c757d;">
                                            <?php echo $votacion['AGRUPADOR']; ?>
                                        </div>
                                        <small class="form-text text-muted">
                                            Se actualizará automáticamente según los cambios realizados
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nuevo Agrupador (preview)</label>
                                        <div class="form-control bg-warning bg-opacity-25" id="preview_agrupador" style="color: #856404;">
                                            <?php echo $votacion['AGRUPADOR']; ?>
                                        </div>
                                    </div>

                                    <input type="hidden" id="agrupador" name="agrupador" value="<?php echo $votacion['AGRUPADOR']; ?>">
                                    <input type="hidden" name="accion" value="modificar_votacion">

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="gestionar_votaciones.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Guardar Cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Información Actual</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Datos Actuales:</h6>
                                    <ul class="mb-0">
                                        <li><strong>ID:</strong> <?php echo $votacion['ID_TIPO_SOLICITUD']; ?></li>
                                        <li><strong>Servicio:</strong> <?php echo $votacion['SERVICIO']; ?></li>
                                        <li><strong>Facultad:</strong> <?php echo $votacion['FACULTAD']; ?></li>
                                        <li><strong>Tipo:</strong> <?php echo $votacion['TIPO_DEPENDIENTE']; ?></li>
                                        <li><strong>Agrupador:</strong> <code><?php echo $votacion['AGRUPADOR']; ?></code></li>
                                    </ul>
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                                    <p class="mb-0">
                                        Al cambiar el tipo de dependiente o el título, 
                                        se generará un nuevo agrupador automáticamente.
                                    </p>
                                </div>

                                <div class="alert alert-secondary">
                                    <h6><i class="fas fa-clock"></i> Fechas:</h6>
                                    <p class="mb-1"><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($votacion['FECHA_INICIO'])); ?></p>
                                    <p class="mb-0"><strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($votacion['FECHA_FIN'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Agrupador original para comparación
        const agrupadorOriginal = '<?php echo $votacion['AGRUPADOR']; ?>';

        document.getElementById('formEditarVotacion').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores para construir el nuevo agrupador
            const dependiente = document.getElementById('id_tipo_dependiente').value;
            const titulo = document.getElementById('tipo_solicitud').value.toUpperCase();
            
            // Determinar letra según tipo de dependiente
            let letra = '';
            if (dependiente == '1') letra = 'E'; // Estudiante
            else if (dependiente == '2') letra = 'D'; // Docente
            else if (dependiente == '3') letra = 'A'; // Administrativo
            else letra = 'X'; // Sin asignar (null)
            
            // Mapeo de facultades
            const facultadesMap = {
                'DERECHO': 'DER',
                'INGENIER': 'ING',
                'ECONOM': 'ECO',
                'SALUD': 'SAL',
                'MEDICINA': 'MED',
                'EDUCACION': 'EDU',
                'ADMINISTRACION': 'ADM',
                'CONTADURIA': 'CON',
                'CIENCIAS': 'CIE',
                'SOCIALES': 'SOC',
                'CONSILIATURA': 'CON',
                'CONSEJO': 'CON'
            };
            
            // Detectar facultad en el título
            let facultad = 'GEN'; // Por defecto General
            for (const [palabra, abrev] of Object.entries(facultadesMap)) {
                if (titulo.includes(palabra)) {
                    facultad = abrev;
                    break;
                }
            }
            
            // Construir y setear el nuevo agrupador
            const nuevoAgrupador = letra + facultad;
            document.getElementById('agrupador').value = nuevoAgrupador;
            
            console.log(`Agrupador actualizado: ${agrupadorOriginal} → ${nuevoAgrupador}`);
            
            // Confirmar cambios si el agrupador cambió
            if (nuevoAgrupador !== agrupadorOriginal) {
                Swal.fire({
                    title: '¿Confirmar cambios?',
                    html: `
                        <p>El agrupador cambiará de:</p>
                        <p><code style="background: #f8d7da; padding: 4px 8px; border-radius: 4px;">${agrupadorOriginal}</code></p>
                        <p>a:</p>
                        <p><code style="background: #d4edda; padding: 4px 8px; border-radius: 4px;">${nuevoAgrupador}</code></p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar cambios',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        enviarFormulario();
                    }
                });
            } else {
                enviarFormulario();
            }
        });

        function enviarFormulario() {
            const formData = new FormData(document.getElementById('formEditarVotacion'));
            
            fetch('/prueba_votaciones/app/ajax/votacionAjax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log("Respuesta del servidor:", text);
                try {
                    const data = JSON.parse(text);
                    Swal.fire({
                        title: data.titulo,
                        text: data.texto,
                        icon: data.icono,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        if (data.icono === 'success') {
                            window.location.href = 'gestionar_votaciones.php';
                        }
                    });
                } catch (error) {
                    console.error("Error al convertir JSON:", error);
                    console.warn("Texto recibido:", text);
                    Swal.fire({
                        title: 'Error',
                        text: 'La respuesta del servidor no es válida. Revisa la consola.',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado al enviar los datos.',
                    icon: 'error'
                });
            });
        }

        function actualizarPreviewAgrupador() {
            const dependiente = document.getElementById('id_tipo_dependiente').value;
            const titulo = document.getElementById('tipo_solicitud').value.toUpperCase();
            
            let letra = '';
            if (dependiente == '1') letra = 'E';
            else if (dependiente == '2') letra = 'D';
            else if (dependiente == '3') letra = 'A';
            else letra = 'X';
            
            const facultadesMap = {
                'DERECHO': 'DER', 'INGENIER': 'ING', 'ECONOM': 'ECO',
                'SALUD': 'SAL', 'MEDICINA': 'MED', 'EDUCACION': 'EDU',
                'ADMINISTRACION': 'ADM', 'CONTADURIA': 'CON', 'CIENCIAS': 'CIE', 
                'SOCIALES': 'SOC', 'CONSILIATURA': 'CON', 'CONSEJO': 'CON'
            };
            
            let facultad = 'GEN';
            for (const [palabra, abrev] of Object.entries(facultadesMap)) {
                if (titulo.includes(palabra)) {
                    facultad = abrev;
                    break;
                }
            }
            
            const preview = letra + facultad;
            const previewElement = document.getElementById('preview_agrupador');
            previewElement.textContent = preview || agrupadorOriginal;
            
            // Cambiar color según si es diferente al original
            if (preview !== agrupadorOriginal) {
                previewElement.className = 'form-control bg-warning bg-opacity-25';
                previewElement.style.color = '#856404';
            } else {
                previewElement.className = 'form-control bg-light';
                previewElement.style.color = '#6c757d';
            }
        }

        // Eventos para actualizar preview en tiempo real
        document.getElementById('id_tipo_dependiente').addEventListener('change', actualizarPreviewAgrupador);
        document.getElementById('tipo_solicitud').addEventListener('input', actualizarPreviewAgrupador);

        // Validación de fechas
        document.getElementById('fecha_inicio').addEventListener('change', validarFechas);
        document.getElementById('fecha_fin').addEventListener('change', validarFechas);

        function validarFechas() {
            const fechaInicio = new Date(document.getElementById('fecha_inicio').value);
            const fechaFin = new Date(document.getElementById('fecha_fin').value);
            
            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                Swal.fire({
                    title: 'Fechas inválidas',
                    text: 'La fecha de inicio debe ser anterior o igual a la fecha de fin',
                    icon: 'warning'
                });
                document.getElementById('fecha_fin').value = document.getElementById('fecha_inicio').value;
            }
        }
    </script>
</body>
</html>
