<?php
header('Content-Type: application/json');

// En una aplicación real esto usaría una base de datos
// Por simplicidad, usamos un archivo JSON
$archivo_tareas = 'tareas.json';

// Inicializar el archivo si no existe
if (!file_exists($archivo_tareas)) {
    file_put_contents($archivo_tareas, json_encode(['tareas' => []]));
}

// Cargar las tareas
$datos = json_decode(file_get_contents($archivo_tareas), true);

// Determinar la acción a realizar
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
if (empty($accion) && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
}

switch ($accion) {
    case 'listar':
        echo json_encode($datos);
        break;
        
    case 'agregar':
        if (isset($_POST['texto']) && !empty($_POST['texto'])) {
            $nuevaTarea = [
                'id' => time(), // Usar timestamp como ID simple
                'texto' => $_POST['texto'],
                'completada' => false
            ];
            
            $datos['tareas'][] = $nuevaTarea;
            file_put_contents($archivo_tareas, json_encode($datos));
            
            echo json_encode(['exito' => true]);
        } else {
            echo json_encode(['exito' => false, 'error' => 'Texto de tarea no proporcionado']);
        }
        break;
        
    case 'eliminar':
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $nuevasTareas = [];
            
            foreach ($datos['tareas'] as $tarea) {
                if ($tarea['id'] != $id) {
                    $nuevasTareas[] = $tarea;
                }
            }
            
            $datos['tareas'] = $nuevasTareas;
            file_put_contents($archivo_tareas, json_encode($datos));
            
            echo json_encode(['exito' => true]);
        } else {
            echo json_encode(['exito' => false, 'error' => 'ID de tarea no proporcionado']);
        }
        break;
        
    default:
        echo json_encode(['exito' => false, 'error' => 'Acción desconocida']);
}
?>