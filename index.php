<?php
session_start();

if (filter_has_var(INPUT_POST, 'crear_tarea')) { // Si se solicita la creación de una tarea
    $tarea = trim(filter_input(INPUT_POST, 'tarea', FILTER_UNSAFE_RAW)); // Lee la tarea del formulario
    if (empty($tarea)) { // Si la tarea no es la cadena vacía
        $errorNombreTarea = true; //Error en el valor introducido como nombre de tarea
    } else {
        $_SESSION['tareas'][] = ['nombre' => $tarea, 'estado' => 0]; // Añado la tarea a la lista
    }
} else if (filter_has_var(INPUT_POST, 'borrar_tarea')) { // Si se solicita que se borre la tarea 
    $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT); // Se lee el número de tarea (uno más que el índice real)          
    unset($_SESSION['tareas'][$tareaId - 1]); // Se borra la tarea de la lista
    $_SESSION['tareas'] = array_values($_SESSION['tareas']); // Se reindexa la lista de tareas para que los índices sean consecutivos
} else if (filter_has_var(INPUT_POST, 'completar_tarea')) { // Si se solicita que se complete una tarea
    $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT); // Se lee el número de tarea (uno más que el índice real)          
    $_SESSION['tareas'][$tareaId - 1]['estado'] = 1; // Se cambia el estado de completado de la tarea
} else {
    $_SESSION['tareas'] = [];
}
$tareas = $_SESSION['tareas'];

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="stylesheet.css">
        <title>Agenda de Tareas</title>
    </head>
    <body>
        <main class="principal">
            <h1>Agenda de Tareas</h1>
            <form class="agenda" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">  <!-- Creo un formulario que envía los datos de nuevo al script -->
                <fieldset class="section"> <!-- Sección de nueva tarea -->
                    <legend>Nueva Tarea:</legend>
                    <div class="form-section">
                        <label for="tarea">Tarea:</label>
                        <input id="tarea" type="text" name="tarea">
                        <?php if (isset($errorNombreTarea)): ?> <!-- Si la tarea está vacía es muestra un mensaje de error -->
                            <p class="error">(*) Nombre obligatorio</p>
                        <?php endif ?>
                    </div>
                    <div class="form-section">
                        <input class="submit blue" type="submit" value="Añadir Tarea" name="crear_tarea"/> <!-- Envío de petición de nueva tarea -->
                        <input class="submit green" type="reset" value="Limpiar Campos"/>
                    </div>
                </fieldset>
                <?php if (!empty($tareas)): ?>
                    <fieldset class="section"> <!-- Sección de creación de la tabla de tareas -->
                        <legend>Lista de Tareas:</legend>
                        <table>
                            <thead>
                                <tr>
                                    <th>Número Tarea</th>
                                    <th>Tarea</th>
                                    <th>Completado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tareas as $numTarea => $tarea): ?> <!-- Bucle de creación de las filas de la tabla -->
                                    <tr>
                                        <td><?= $numTarea + 1 ?></td> <!-- Añado uno al índice para que la lista se inicie en 1 -->
                                        <td><?= htmlentities($tarea['nombre']) ?></td>
                                        <td><?= ($tarea['estado']) ? "Si" : "No" ?></td>
                                    </tr>
                                <input type='hidden' name="<?= "tareas[$numTarea][nombre]" ?>" value="<?= $tarea['nombre'] ?>"> <!-- Incluyo cada tarea en el input que va recogiendo todos los valores en el array tareas -->
                                <input type='hidden' name="<?= "tareas[$numTarea][estado]" ?>" value="<?= $tarea['estado'] ?>"> <!-- Incluyo cada tarea en el input que va recogiendo todos los valores en el array tareasCompletadas -->
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="section"> <!-- Sección de operaciones sobre la lista de tareas -->
                        <div class="form-section">
                            <label class="blue" for="tarea">Num Tarea:</label>
                            <input id="tarea" type="number" min="1" max=<?= count($tareas) ?> value="1" name="tarea_id">
                            <input class="submit blue" type="submit" value="Tarea Completada" name='completar_tarea'/>
                            <input class="submit blue" type="submit" value="Tarea Borrada" name='borrar_tarea'/>
                            <input class="submit red" type="submit" formmethod = "GET" formaction="<?= "{$_SERVER['PHP_SELF']}?limpiar_tareas" ?>"  value="Vaciar Agenda">
                            <!-- Otra manera de enviar la petición al servidor con un mensaje GET-->   
                            <!-- Esta forma no requiere que el botón sea de tipo submit -->
                            <!-- <a href="<?= "{$_SERVER['PHP_SELF']}?limpiar_tareas" ?>"><input class="submit red" value="Vaciar Agenda"></a> -->                     
                        </div> 
                    </fieldset>
                <?php else: ?>
                    <fieldset class="section"> 
                        <legend>Lista de Tareas:</legend>
                        <p class="blue">No hay tareas</p>
                    </fieldset>  
                <?php endif ?>
            </form>
        </main>
    </body>
</html>
