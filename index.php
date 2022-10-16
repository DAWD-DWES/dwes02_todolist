<?php
if (!empty($_POST)) {
    $tareas = (filter_input(INPUT_POST, 'tareas', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) ?? array(); // Contiene la lista de tareas
    $tareasCompletadas = (filter_input(INPUT_POST, 'tareasCompletadas', FILTER_VALIDATE_BOOLEAN, FILTER_REQUIRE_ARRAY)) ?? array(); // Contiene la lista de los estados de completado de las tareas
    if (isset($_POST['crear_tarea'])) { // Si se solicita la creación de una tarea
        $tarea = (trim(filter_input(INPUT_POST, 'tarea', FILTER_SANITIZE_STRING))); // Lee la tarea del formulario
        if (!empty($tarea)) { // Si la tarea no es la cadena vacía
            $tareas[] = $tarea; // Añado la tarea a la lista
            $tareasCompletadas[] = false; // Añado el estado falso a la lista de estados de completado
        }
    } else if (isset($_POST['borrar_tarea'])) { // Si se solicita que se borre la tarea 
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT); // Se lee el número de tarea (uno más que el índice real)          
        unset($tareas[$tareaId]); // Se borra la tarea de la lista
        unset($tareasCompletadas[$tareaId]); // Se borra el estado de completado de la lista
        $tareas = array_values($tareas); // Se reindexa la lista de tareas para que los índices sean consecutivos
        $tareasCompletadas = array_values($tareasCompletadas); // Se reindexa la lista de estados para que los índices sean consecutivos    
    } else if (isset($_POST['completar_tarea'])) { // Si se solicita que se complete una tarea
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT); // Se lee el número de tarea (uno más que el índice real)
        $tareasCompletadas[$tareaId] = true; // Se cambia el estado de completado de la tarea
    } else if (isset($_GET['limpiar_tareas'])) { // Si se solicita que se complete una tarea
        $tareas = array(); // Se vacía la lista de tareas
    }
}
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
        <form class="agenda" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">  <!-- Creo un formulario que envía los datos de nuevo al script -->
            <h1>Agenda de Tareas</h1>
            <fieldset> <!-- Sección de nueva tarea -->
                <legend>Nueva Tarea:</legend>
                <div class="form-section">
                    <label for="tarea">Tarea:</label>
                    <input id="tarea" type="text" name="tarea">
                    <?php if (isset($tarea) && empty($tarea)): ?> <!-- Si la tarea está vacía es muestra un mensaje de error -->
                        <p class="error">(*) Nombre obligatorio</p>
                    <?php endif ?>
                </div>
                <div class="form-section">
                    <input class="submit blue" type="submit" value="Añadir Tarea" name='crear_tarea'/> <!-- Envío de petición de nueva tarea -->
                    <input class="submit green" type="reset" value="Limpiar Campos"/>
                </div>
            </fieldset>
            <fieldset> <!-- Sección de creación de la tabla de tareas -->
                <legend>Lista de Tareas:</legend>
                <?php if (empty($tareas)): ?> <!-- Si la lista de tareas está vacía aparece el mensaje -->
                    <p>No hay tareas</p>
                <?php else: ?> <!-- Sino se crea la tabla de tareas -->
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
                                    <td><?= $tarea ?></td>
                                    <td><?= ($tareasCompletadas[$numTarea]) ? "Si" : "No" ?></td>
                                </tr>
                            <input type='hidden' name="<?= "tareas[]" ?>" value="<?= $tarea ?>"> <!-- Incluyo cada tarea en el input que va recogiendo todos los valores en el array tareas -->
                            <input type='hidden' name="<?= "tareasCompletadas[]" ?>" value="<?= $tareasCompletadas[$numTarea] ?>"> <!-- Incluyo cada tarea en el input que va recogiendo todos los valores en el array tareasCompletadas -->
                        <?php endforeach ?>
                        </tbody>
                    </table>
                <?php endif ?>
            </fieldset>
            <?php if (!empty($tareas)): ?> <!-- Si hay tareas en la lista -->
                <fieldset> <!-- Sección de operaciones sobre la lista de tareas -->
                    <legend></legend>
                    <div class="form-section">
                        <label for="tarea">Num Tarea:</label>
                        <input id="tarea" type="number" min="1" max=<?= count($tareas) ?> value="1" name="tarea_id">
                        <input class="submit blue" type="submit" value="Tarea Completada" name='completar_tarea'/>
                        <input class="submit blue" type="submit" value="Tarea Borrada" name='borrar_tarea'/>
                        <input class="submit red" type="submit" formaction="<?= "{$_SERVER['PHP_SELF']}?limpiar_tareas=1" ?>"  value="Vaciar Agenda">
                    </div>
                </fieldset>
            <?php endif ?>
        </form>
    </body>
</html>
