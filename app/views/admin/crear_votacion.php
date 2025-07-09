<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Votación</title>
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
                            <a class="nav-link active" href="crear_votacion.php">
                                <i class="fas fa-plus-circle"></i> Crear Votación
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="gestionar_votaciones.php">
                                <i class="fas fa-list"></i> Listar Votaciones
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
                    <h1 class="h2">Crear Nueva Votación</h1>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <form id="formCrearVotacion">
                                    <div class="mb-3">
                                        <label for="id_tipo_solicitud" class="form-label">ID Tipo de Solicitud (opcional)</label>
                                        <input type="number" class="form-control" id="id_tipo_solicitud" name="id_tipo_solicitud" placeholder="Se generará automáticamente si se deja vacío">
                                        <small class="form-text text-muted">Si no se especifica, se generará automáticamente</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_solicitud" class="form-label">Tipo de Solicitud *</label>
                                        <input type="text" class="form-control" id="tipo_solicitud" name="tipo_solicitud" required placeholder="Ej: ELECCION DE LOS REPRESENTANTES DE LOS ESTUDIANTES A LA CONSILIATURA">
                                    </div>

                                    <input type="hidden" name="servicio" value="VOT">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_tipo_dependiente" class="form-label">Tipo de Dependiente (opcional)</label>
                                        <select class="form-select" id="id_tipo_dependiente" name="id_tipo_dependiente">
                                            <option value="">Sin asignar</option>
                                            <option value="1">Estudiante</option>
                                            <option value="2">Docente</option>
                                            <option value="3">Administrativo</option>
                                        </select>
                                        <small class="form-text text-muted">Puede dejarse sin asignar (null)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Agrupador (se genera automáticamente)</label>
                                        <div class="form-control bg-light" id="preview_agrupador" style="color: #6c757d;">
                                            Se generará automáticamente al enviar
                                        </div>
                                        <small class="form-text text-muted">
                                            Formato: [E/D/A/X][Facultad] - E=Estudiante, D=Docente, A=Administrativo, X=Sin asignar
                                        </small>
                                    </div>

                                    <input type="hidden" id="agrupador" name="agrupador">
                                    <input type="hidden" name="accion" value="crear_votacion">
                                    <input type="hidden" name="usuario_creador" value="<?php echo $_SESSION['usuario'] ?? 'admin'; ?>">

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" class="btn btn-secondary me-md-2" onclick="history.back()">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Crear Votación</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sección de Preguntas (se muestra después de crear la votación) -->
                        <div class="card mt-4" id="seccionPreguntas" style="display: none;">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Configurar Preguntas</h5>
                            </div>
                            <div class="card-body">
                                <form id="formAgregarPregunta">
                                    <input type="hidden" id="id_votacion_pregunta" name="id_votacion">
                                    
                                    <div class="mb-3">
                                        <label for="pregunta" class="form-label">Pregunta *</label>
                                        <textarea class="form-control" id="pregunta" name="pregunta" rows="2" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tipo_pregunta" class="form-label">Tipo de Pregunta *</label>
                                                <select class="form-select" id="tipo_pregunta" name="tipo_pregunta" required>
                                                    <option value="">Seleccione un tipo</option>
                                                    <option value="SELECCION_UNICA">Selección Única</option>
                                                    <option value="SELECCION_MULTIPLE">Selección Múltiple</option>
                                                    <option value="TEXTO_LIBRE">Texto Libre</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="orden" class="form-label">Orden</label>
                                                <input type="number" class="form-control" id="orden" name="orden" value="1" min="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="obligatoria" name="obligatoria" checked>
                                            <label class="form-check-label" for="obligatoria">
                                                Pregunta obligatoria
                                            </label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success">Agregar Pregunta</button>
                                </form>

                                <div id="listaPreguntasCreadas" class="mt-4"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Estructura de la Base de Datos</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-database"></i> Campos que se guardarán:</h6>
                                    <ul class="mb-0">
                                        <li><strong>ID_TIPO_SOLICITUD:</strong> Auto-generado o manual</li>
                                        <li><strong>TIPO_SOLICITUD:</strong> Título de la votación</li>
                                        <li><strong>SERVICIO:</strong> Siempre "VOT"</li>
                                        <li><strong>FECHA_INICIO:</strong> Fecha de inicio</li>
                                        <li><strong>FECHA_FIN:</strong> Fecha de fin</li>
                                        <li><strong>ID_TIPO_DEPENDIENTE:</strong> Opcional (puede ser null)</li>
                                        <li><strong>AGRUPADOR:</strong> Generado automáticamente</li>
                                    </ul>
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Ejemplo de agrupador:</h6>
                                    <p class="mb-0">
                                        <strong>EDER</strong> = <strong>E</strong>studiante + <strong>DER</strong>echo<br>
                                        <strong>DING</strong> = <strong>D</strong>ocente + <strong>ING</strong>eniería<br>
                                        <strong>XGEN</strong> = Sin asignar + <strong>GEN</strong>eral
                                    </p>
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
document.getElementById('formCrearVotacion').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Obtener valores para construir el agrupador
    const dependiente = document.getElementById('id_tipo_dependiente').value;
    const titulo = document.getElementById('tipo_solicitud').value.toUpperCase();
    
    // Determinar letra según tipo de dependiente
    let letra = '';
    if (dependiente == '1') letra = 'E'; // Estudiante
    else if (dependiente == '2') letra = 'D'; // Docente
    else if (dependiente == '3') letra = 'A'; // Administrativo
    else letra = 'X'; // Sin asignar (null)
    
    // Mapeo de facultades más preciso
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
    
    // Construir y setear el agrupador
    const agrupadorFinal = letra + facultad;
    document.getElementById('agrupador').value = agrupadorFinal;
    
    console.log(`Agrupador construido: ${agrupadorFinal} (${letra} + ${facultad})`);
    
    const formData = new FormData(this);
    
    fetch('/prueba_votaciones/app/ajax/votacionAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log("Respuesta cruda del servidor:", text);
        try {
            const data = JSON.parse(text);
            if (data.tipo === 'limpiar') {
                Swal.fire({
                    title: data.titulo,
                    text: data.texto,
                    icon: data.icono,
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    document.getElementById('seccionPreguntas').style.display = 'block';
                    document.getElementById('id_votacion_pregunta').value = data.id_votacion;
                    document.getElementById('formCrearVotacion').reset();
                    document.getElementById('preview_agrupador').textContent = 'Se generará automáticamente al enviar';
                });
            } else {
                Swal.fire({
                    title: data.titulo,
                    text: data.texto,
                    icon: data.icono
                });
            }
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
});

// Resto del script permanece igual
document.getElementById('formAgregarPregunta').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('accion', 'agregar_pregunta');
    
    fetch('../../app/ajax/votacionAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            title: data.titulo,
            text: data.texto,
            icon: data.icono
        });
        
        if (data.tipo === 'limpiar') {
            this.reset();
            cargarPreguntasCreadas();
        }
    });
});

function cargarPreguntasCreadas() {
    const idVotacion = document.getElementById('id_votacion_pregunta').value;
    if (!idVotacion) return;
    
    fetch(`../../app/ajax/votacionAjax.php?accion=listar_preguntas&id_votacion=${idVotacion}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('listaPreguntasCreadas').innerHTML = data.html;
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
    document.getElementById('preview_agrupador').textContent = preview || 'Se generará automáticamente al enviar';
}

// Eventos para actualizar preview
document.getElementById('id_tipo_dependiente').addEventListener('change', actualizarPreviewAgrupador);
document.getElementById('tipo_solicitud').addEventListener('input', actualizarPreviewAgrupador);
</script>
</body>
</html>
