<?php
require_once __DIR__ . "/../../controllers/planchaController.php";
use app\controllers\planchaController;

$insplancha = new planchaController();
$planchas = $insplancha->listarPlanchasControlador();
$preguntas = $insplancha->obtenerPreguntasDisponibles();
$tipos_solicitud = $insplancha->obtenerTiposSolicitud();
$estadisticas = $insplancha->obtenerEstadisticasPlanchas();
$planchas_por_agrupador = $insplancha->obtenerPlanchasPorAgrupador();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Planchas - Sistema de Votaciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 2.8em;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .workflow-info {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .workflow-info h3 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        .workflow-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .workflow-step {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1em;
            color: #666;
            font-weight: 500;
        }
        
        .section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .section-title {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 25px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            font-weight: 600;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 1em;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background-color: white;
        }
        
        .file-input-container {
            position: relative;
            display: block;
            width: 100%;
        }
        
        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label {
            display: block;
            padding: 20px;
            border: 2px dashed #667eea;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
        }
        
        .file-input-label:hover {
            border-color: #5a67d8;
            background: linear-gradient(135deg, #e8ecff 0%, #d6dbff 100%);
        }
        
        .file-input-icon {
            font-size: 2em;
            margin-bottom: 10px;
            display: block;
        }
        
        .file-preview {
            margin-top: 15px;
            text-align: center;
        }
        
        .file-preview img {
            max-width: 250px;
            max-height: 200px;
            border-radius: 10px;
            border: 3px solid #e1e5e9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #a8a8a8 0%, #8c8c8c 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .table-container {
            overflow-x: auto;
            margin-top: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 0.95em;
        }
        
        .table tr:hover {
            background-color: #f8f9ff;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .image-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e1e5e9;
        }
        
        .no-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f1f3f4 0%, #e9ecef 100%);
            border: 2px dashed #ccc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #666;
            text-align: center;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-activo {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .status-inactivo {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .alert {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            border-left: 4px solid;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border-color: #17a2b8;
            color: #0c5460;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-color: #ffc107;
            color: #856404;
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
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f3f4;
        }
        
        .modal-title {
            font-size: 1.6em;
            color: #333;
            font-weight: 600;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s ease;
        }
        
        .close:hover {
            color: #667eea;
        }
        
        .agrupador-section {
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .agrupador-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .agrupador-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .agrupador-stat {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .header {
                padding: 25px;
            }
            
            .header h1 {
                font-size: 2.2em;
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
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .workflow-steps {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗳️ Gestión de Planchas</h1>
            <p>Sistema completo de administración de planchas para votaciones universitarias</p>
            
            <div class="workflow-info">
                <h3>📋 Proceso de Creación de Planchas</h3>
                <div class="workflow-steps">
                    <div class="workflow-step">
                        <div class="step-number">1</div>
                        <div>Configurar Votación</div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">2</div>
                        <div>Seleccionar Pregunta</div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">3</div>
                        <div>Elegir Tipo/Facultad</div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">4</div>
                        <div>Diseñar Plancha</div>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">5</div>
                        <div>Subir Imagen</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['total_planchas']; ?></div>
                <div class="stat-label">Total Planchas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['con_archivo_local']; ?></div>
                <div class="stat-label">Con Archivo Local</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['solo_url']; ?></div>
                <div class="stat-label">Solo URL Externa</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['preguntas_utilizadas']; ?></div>
                <div class="stat-label">Preguntas Utilizadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['total_preguntas_disponibles']; ?></div>
                <div class="stat-label">Preguntas Disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $estadisticas['total_tipos_votacion']; ?></div>
                <div class="stat-label">Tipos de Votación</div>
            </div>
        </div>

        <!-- Estadísticas por Agrupador -->
        <?php if (!empty($planchas_por_agrupador)): ?>
        <div class="section">
            <h2 class="section-title">📊 Planchas por Facultad/Agrupador</h2>
            
            <?php 
            $agrupadores = [];
            foreach($planchas_por_agrupador as $item) {
                $agrupadores[$item['AGRUPADOR']][] = $item;
            }
            ?>
            
            <?php foreach($agrupadores as $agrupador => $tipos): ?>
            <div class="agrupador-section">
                <div class="agrupador-title">🏛️ <?php echo strtoupper($agrupador); ?></div>
                <div class="agrupador-stats">
                    <?php foreach($tipos as $tipo): ?>
                    <div class="agrupador-stat">
                        <div style="font-weight: 600; color: #667eea; margin-bottom: 5px;">
                            <?php echo $tipo['TIPO_SOLICITUD']; ?>
                        </div>
                        <div style="font-size: 1.5em; font-weight: bold; color: #333;">
                            <?php echo $tipo['total_planchas']; ?>
                        </div>
                        <div style="font-size: 0.9em; color: #666;">
                            planchas (<?php echo $tipo['con_imagen_local']; ?> con imagen)
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Formulario de Creación -->
        <div class="section">
            <h2 class="section-title" id="form-title">➕ Crear Nueva Plancha</h2>
            
            <?php if (empty($preguntas) || empty($tipos_solicitud)): ?>
            <div class="alert alert-warning">
                <strong>⚠️ Configuración Requerida</strong><br>
                <?php if (empty($preguntas)): ?>
                    • No hay preguntas disponibles en el sistema.<br>
                <?php endif; ?>
                <?php if (empty($tipos_solicitud)): ?>
                    • No hay tipos de votación configurados.<br>
                <?php endif; ?>
                Contacta al administrador del sistema para completar la configuración inicial.
            </div>
            <?php endif; ?>

            <form id="formPlancha" enctype="multipart/form-data">
                <input type="hidden" id="id_opcion_pregunta" name="id_opcion_pregunta">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id_pregunta">🔍 Pregunta de Votación *</label>
                        <select id="id_pregunta" name="id_pregunta" required>
                            <option value="">Seleccione una pregunta</option>
                            <?php foreach($preguntas as $pregunta): ?>
                                <option value="<?php echo $pregunta['ID_PREGUNTA']; ?>" title="<?php echo htmlspecialchars($pregunta['PREGUNTA']); ?>">
                                    <?php echo $pregunta['ID_PREGUNTA'] . '. ' . (strlen($pregunta['PREGUNTA']) > 60 ? substr($pregunta['PREGUNTA'], 0, 60) . '...' : $pregunta['PREGUNTA']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_tipo_solicitud">🏛️ Tipo de Votación / Facultad *</label>
                        <select id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                            <option value="">Seleccione un tipo</option>
                            <?php foreach($tipos_solicitud as $tipo): ?>
                                <option value="<?php echo $tipo['ID_TIPO_SOLICITUD']; ?>" title="<?php echo htmlspecialchars($tipo['DESCRIPCION']); ?>">
                                    <?php echo $tipo['AGRUPADOR'] . ' - ' . $tipo['TIPO_SOLICITUD']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="opcion">📝 Nombre de la Plancha *</label>
                    <input type="text" id="opcion" name="opcion" required 
                           placeholder="Ej: Plancha Verde, VOTO EN BLANCO, Lista Estudiantil Unidos">
                    <small style="color: #666; font-size: 14px; margin-top: 5px; display: block;">
                        💡 Tip: Use nombres descriptivos que identifiquen claramente la plancha
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="imagen">🖼️ Imagen de la Plancha</label>
                    <div class="file-input-container">
                        <input type="file" id="imagen" name="imagen" class="file-input" accept="image/*" onchange="previewImage(this)">
                        <label for="imagen" class="file-input-label">
                            <span class="file-input-icon">📁</span>
                            <strong>Seleccionar imagen desde tu computadora</strong><br>
                            <small>Formatos: JPG, PNG, GIF, WebP | Tamaño máximo: 5MB</small><br>
                            <small>📐 Recomendado: Diseñar en PowerPoint o Genially</small>
                        </label>
                    </div>
                    <div id="imagePreview" class="file-preview"></div>
                </div>
                
                <div class="form-group">
                    <label for="url">🔗 O URL de Imagen Externa</label>
                    <input type="url" id="url" name="url" placeholder="https://ejemplo.com/mi-plancha.png">
                    <small style="color: #666; font-size: 14px; margin-top: 5px; display: block;">
                        🌐 Alternativa: Si la imagen está alojada en otro servidor (Genially, Google Drive, etc.)
                    </small>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="btnGuardarPlancha">
                        ✅ Crear Plancha
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                        ❌ Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Planchas -->
        <div class="section">
            <h2 class="section-title">📋 Planchas Registradas</h2>
            
            <?php if (empty($planchas)): ?>
            <div class="alert alert-info">
                <strong>📝 No hay planchas registradas</strong><br>
                Crea tu primera plancha usando el formulario anterior. El sistema organizará automáticamente las imágenes por facultad/agrupador.
            </div>
            <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Pregunta</th>
                            <th>Tipo/Facultad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($planchas as $plancha): ?>
                        <tr>
                            <td><strong><?php echo $plancha['ID_OPCION_PREGUNTA']; ?></strong></td>
                            <td>
                                <?php if (!empty($plancha['RUTA_IMAGEN'])): ?>
                                    <img src="<?php echo $plancha['RUTA_IMAGEN']; ?>"
                                         alt="<?php echo htmlspecialchars($plancha['OPCION']); ?>"
                                         class="image-preview"
                                         title="Archivo local: <?php echo $plancha['RUTA_IMAGEN']; ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="no-image" style="display: none;">Sin imagen</div>
                                <?php elseif (!empty($plancha['URL'])): ?>
                                    <img src="<?php echo $plancha['URL']; ?>"
                                         alt="<?php echo htmlspecialchars($plancha['OPCION']); ?>"
                                         class="image-preview"
                                         title="URL externa: <?php echo $plancha['URL']; ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="no-image" style="display: none;">Sin imagen</div>
                                <?php else: ?>
                                    <div class="no-image">Sin imagen</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($plancha['OPCION']); ?></strong>
                                <?php if (!empty($plancha['RUTA_IMAGEN'])): ?>
                                    <br><small style="color: #28a745;">📁 Archivo local</small>
                                <?php elseif (!empty($plancha['URL'])): ?>
                                    <br><small style="color: #17a2b8;">🔗 URL externa</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span title="<?php echo htmlspecialchars($plancha['PREGUNTA']); ?>">
                                    <?php echo $plancha['ID_PREGUNTA'] . '. ' . (strlen($plancha['PREGUNTA']) > 40 ? substr($plancha['PREGUNTA'], 0, 40) . '...' : $plancha['PREGUNTA']); ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo $plancha['AGRUPADOR']; ?></strong><br>
                                <small><?php echo $plancha['TIPO_SOLICITUD']; ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($plancha['ESTADO']); ?>">
                                    <?php echo $plancha['ESTADO']; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($plancha['FECHA_CREACION'])); ?><br>
                                <small><?php echo date('H:i', strtotime($plancha['FECHA_CREACION'])); ?></small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning" onclick="editarPlancha(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)" title="Editar plancha">
                                        ✏️
                                    </button>
                                    <button class="btn btn-danger" onclick="eliminarPlancha(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)" title="Eliminar plancha">
                                        🗑️
                                    </button>
                                    <button class="btn btn-primary" onclick="verDetalles(<?php echo $plancha['ID_OPCION_PREGUNTA']; ?>)" title="Ver detalles">
                                        👁️
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div id="modalDetalles" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">🔍 Detalles de la Plancha</h3>
                <span class="close" onclick="cerrarModal('modalDetalles')">&times;</span>
            </div>
            <div id="contenidoDetalles"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let modoEdicion = false;

        // Preview de imagen con información adicional
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    preview.innerHTML = `
                        <img src="${e.target.result}" style="max-width: 250px; max-height: 200px; border-radius: 10px; border: 3px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div style="margin-top: 10px; font-size: 14px; color: #666;">
                            📄 <strong>${file.name}</strong><br>
                            📏 ${fileSize} MB | 🖼️ ${file.type}
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        }

        // Formulario de planchas con validaciones mejoradas
        document.getElementById('formPlancha').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validaciones adicionales
            const opcion = document.getElementById('opcion').value.trim();
            const pregunta = document.getElementById('id_pregunta').value;
            const tipo = document.getElementById('id_tipo_solicitud').value;
            const imagen = document.getElementById('imagen').files[0];
            const url = document.getElementById('url').value.trim();
            
            if (!opcion || !pregunta || !tipo) {
                Swal.fire({
                    title: '⚠️ Campos Requeridos',
                    text: 'Por favor complete todos los campos obligatorios',
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            if (!imagen && !url) {
                Swal.fire({
                    title: '🖼️ Imagen Requerida',
                    text: 'Debe subir una imagen o proporcionar una URL',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            const formData = new FormData(this);
            const accion = modoEdicion ? 'modificar_plancha' : 'crear_plancha';
            formData.append('accion', accion);
            
            // Mostrar loading
            Swal.fire({
                title: modoEdicion ? '⏳ Modificando plancha...' : '⏳ Creando plancha...',
                text: 'Por favor espere mientras procesamos la información',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('../../planchaAjax.php', {
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
                    title: '❌ Error de Conexión',
                    text: 'No se pudo conectar con el servidor. Verifique su conexión.',
                    icon: 'error',
                    confirmButtonText: 'Reintentar'
                });
            });
        });

        function editarPlancha(id) {
            Swal.fire({
                title: '⏳ Cargando datos...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`../../planchaAjax.php?accion=obtener_plancha&id_opcion_pregunta=${id}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data) {
                    document.getElementById('id_opcion_pregunta').value = data.ID_OPCION_PREGUNTA;
                    document.getElementById('id_pregunta').value = data.ID_PREGUNTA;
                    document.getElementById('id_tipo_solicitud').value = data.ID_TIPO_SOLICITUD;
                    document.getElementById('opcion').value = data.OPCION;
                    document.getElementById('url').value = data.URL || '';
                    
                    // Mostrar preview de imagen actual
                    const preview = document.getElementById('imagePreview');
                    if (data.RUTA_IMAGEN) {
                        preview.innerHTML = `
                            <img src="${data.RUTA_IMAGEN}" style="max-width: 250px; max-height: 200px; border-radius: 10px; border: 3px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div style="margin-top: 10px; font-size: 14px; color: #666;">
                                📁 <strong>Imagen actual (archivo local)</strong><br>
                                📂 ${data.RUTA_IMAGEN}
                            </div>
                        `;
                    } else if (data.URL) {
                        preview.innerHTML = `
                            <img src="${data.URL}" style="max-width: 250px; max-height: 200px; border-radius: 10px; border: 3px solid #667eea; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div style="margin-top: 10px; font-size: 14px; color: #666;">
                                🔗 <strong>Imagen actual (URL externa)</strong><br>
                                🌐 ${data.URL}
                            </div>
                        `;
                    }
                    
                    document.getElementById('btnGuardarPlancha').innerHTML = '✅ Modificar Plancha';
                    document.getElementById('form-title').innerHTML = '✏️ Modificar Plancha';
                    modoEdicion = true;
                    
                    document.getElementById('formPlancha').scrollIntoView({ behavior: 'smooth' });
                    
                    Swal.fire({
                        title: '✅ Datos Cargados',
                        text: 'Los datos de la plancha han sido cargados para edición',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error('No se pudieron obtener los datos');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: '❌ Error',
                    text: 'No se pudo cargar la información de la plancha',
                    icon: 'error'
                });
            });
        }

        function eliminarPlancha(id) {
            Swal.fire({
                title: '⚠️ ¿Está completamente seguro?',
                html: `
                    <p>Esta acción eliminará permanentemente:</p>
                    <ul style="text-align: left; margin: 15px 0;">
                        <li>🗑️ La plancha de la base de datos</li>
                        <li>🖼️ El archivo de imagen del servidor</li>
                        <li>🔗 Todas las relaciones asociadas</li>
                    </ul>
                    <p><strong>Esta acción NO se puede deshacer</strong></p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '🗑️ Sí, eliminar definitivamente',
                cancelButtonText: '❌ Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '⏳ Eliminando...',
                        text: 'Eliminando plancha y archivos asociados',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    const formData = new FormData();
                    formData.append('accion', 'eliminar_plancha');
                    formData.append('id_opcion_pregunta', id);
                    
                    fetch('../../planchaAjax.php', {
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
                            title: '❌ Error',
                            text: 'No se pudo eliminar la plancha',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        function verDetalles(id) {
            Swal.fire({
                title: '⏳ Cargando detalles...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`../../planchaAjax.php?accion=obtener_plancha&id_opcion_pregunta=${id}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data) {
                    let imagenHtml = '';
                    if (data.RUTA_IMAGEN) {
                        imagenHtml = `
                        <div class="form-group">
                            <strong>🖼️ Imagen:</strong><br>
                            <img src="${data.RUTA_IMAGEN}" alt="${data.OPCION}" style="max-width: 300px; border-radius: 10px; margin: 10px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <br><small style="color: #28a745;">📁 Archivo local: ${data.RUTA_IMAGEN}</small>
                        </div>`;
                    } else if (data.URL) {
                        imagenHtml = `
                        <div class="form-group">
                            <strong>🖼️ Imagen:</strong><br>
                            <img src="${data.URL}" alt="${data.OPCION}" style="max-width: 300px; border-radius: 10px; margin: 10px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <br><small style="color: #17a2b8;">🔗 URL externa: ${data.URL}</small>
                        </div>`;
                    } else {
                        imagenHtml = '<div class="form-group"><strong>🖼️ Imagen:</strong> Sin imagen configurada</div>';
                    }

                    const contenido = `
                        <div style="text-align: left;">
                            <div class="form-group">
                                <strong>📝 Nombre:</strong> ${data.OPCION}
                            </div>
                            <div class="form-group">
                                <strong>❓ Pregunta:</strong> ${data.PREGUNTA}
                            </div>
                            <div class="form-group">
                                <strong>🏛️ Tipo de Votación:</strong> ${data.TIPO_SOLICITUD}
                            </div>
                            <div class="form-group">
                                <strong>📂 Agrupador/Facultad:</strong> ${data.AGRUPADOR}
                            </div>
                            ${data.DESCRIPCION_TIPO ? `
                            <div class="form-group">
                                <strong>📋 Descripción:</strong> ${data.DESCRIPCION_TIPO}
                            </div>
                            ` : ''}
                            ${imagenHtml}
                            <div class="form-group">
                                <strong>📊 Estado:</strong> <span class="status-badge status-${data.ESTADO.toLowerCase()}">${data.ESTADO}</span>
                            </div>
                            <div class="form-group">
                                <strong>📅 Fecha de Creación:</strong> ${new Date(data.FECHA_CREACION).toLocaleString('es-ES')}
                            </div>
                            ${data.FECHA_MODIFICACION ? `
                            <div class="form-group">
                                <strong>🔄 Última Modificación:</strong> ${new Date(data.FECHA_MODIFICACION).toLocaleString('es-ES')}
                            </div>
                            ` : ''}
                        </div>
                    `;
                    
                    document.getElementById('contenidoDetalles').innerHTML = contenido;
                    document.getElementById('modalDetalles').style.display = 'block';
                } else {
                    throw new Error('No se pudieron obtener los detalles');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: '❌ Error',
                    text: 'No se pudieron cargar los detalles de la plancha',
                    icon: 'error'
                });
            });
        }

        function limpiarFormulario() {
            document.getElementById('formPlancha').reset();
            document.getElementById('id_opcion_pregunta').value = '';
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('btnGuardarPlancha').innerHTML = '✅ Crear Plancha';
            document.getElementById('form-title').innerHTML = '➕ Crear Nueva Plancha';
            modoEdicion = false;
        }

        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modales = document.querySelectorAll('.modal');
            modales.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Mostrar información adicional al seleccionar pregunta
        document.getElementById('id_pregunta').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const fullText = selectedOption.getAttribute('title');
                if (fullText && fullText.length > 60) {
                    // Mostrar tooltip o información adicional si es necesario
                    console.log('Pregunta completa:', fullText);
                }
            }
        });

        // Mostrar información adicional al seleccionar tipo de solicitud
        document.getElementById('id_tipo_solicitud').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const descripcion = selectedOption.getAttribute('title');
                if (descripcion) {
                    console.log('Descripción del tipo:', descripcion);
                }
            }
        });
    </script>
</body>
</html>
