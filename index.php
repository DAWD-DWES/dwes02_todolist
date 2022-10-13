<?php
if (!empty($_POST)) {
    $tareas = (filter_input(INPUT_POST, 'tareas', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) ?? array();
    $tareasCompletadas = (filter_input(INPUT_POST, 'tareasCompletadas', FILTER_VALIDATE_BOOLEAN, FILTER_REQUIRE_ARRAY)) ?? array();
    if (isset($_POST['crear_tarea'])) {
        $tarea = ucwords(strtolower(trim(filter_input(INPUT_POST, 'tarea', FILTER_SANITIZE_STRING))));
        $tareas[] = $tarea;
    } else if (isset($_GET['borrar_tarea'])) {
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT);
        unset($tareas[tareaId]);
        $tareas = array_values($tareas);
    } else if (isset($_GET['completar_tarea'])) {
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT);
        $tareasCompletadas[tareaId] = true;
    } else if (isset($_GET['limpiar'])) {
        $tareas = array();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="stylesheet.css">
        <title>Agenda</title>
    </head>
    <body>
        <form class="agenda" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">     
            <h1>Agenda</h1>
            <fieldset>
                <legend>Nueva Tarea:</legend>
                <div class="form-section">
                    <label for="tarea">Tarea:</label>
                    <input id="tarea" type="text" name="tarea">
                    <!--       <?php if (isset($tarea) && empty($tarea)): ?>
                                               <p class="error">Nombre obligatorio</p>
                    <?php endif ?> -->
                </div>
                <div class="form-section">
                    <input class="submit blue" type="submit" value="Añadir Tarea" name='crear_tarea'/>
                    <input class="submit green" type="reset" value="Limpiar Campos"/>
                </div>
            </fieldset>
            <fieldset>
                <legend>Lista de Tareas:</legend>
                <?php if (empty($agenda)): ?>
                    <p>No hay tareas</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Número Tarea</th>
                                <th>Tarea</th>
                                <th>Completado</th>
                            </tr>
                        </thead>
                        <?php foreach ($tareas as $numTarea => $tarea): ?>
                            <tr>
                                <td>$numTarea</td>
                                <td>$tarea</td>
                                <td><?= ($tareasCompletadas[$numTarea]) ? "Si" : "No" ?></td>
                            </tr>
                            <input type='hidden' name="<?= "tareas[]" ?>" value="<?= $tarea ?>">
                            <input type='hidden' name="<?= "tareasCompletadas[]" ?>" value="<?= $tareasCompletadas[$numTarea] ?>">
                        <?php endforeach ?>
                    <?php endif ?>
                    </fieldset>
                    <!-- Creamos el formulario de introducción de un nuevo contacto -->

                    <?php if (!empty($agenda)): ?>

                        <fieldset>
                            <legend></legend>
                            <div class="form-section">
                                <label for="tarea">Tarea Completada:</label>
                                <input id="tarea" type="text" name="tarea">
                                <!--       <?php if (isset($tarea) && empty($tarea)): ?>
                                                           <p class="error">Nombre obligatorio</p>
                                <?php endif ?> -->
                                <input class="submit blue" type="submit" value="Añadir Tarea" name='completar_tarea'/>
                            </div>
                            <div class="form-section">
                                <label for="tarea">Tarea Borrada:</label>
                                <input id="tarea" type="text" name="tarea">
                                <!--       <?php if (isset($tarea) && empty($tarea)): ?>
                                                       <p class="error">Nombre obligatorio</p>
                                <?php endif ?> -->
                                <input class="submit blue" type="submit" value="Añadir Tarea" name='borrar_tarea'/>
                            </div>
                            <input class="submit red" type="submit" formaction="<?= "{$_SERVER['PHP_SELF']}?limpiar=1" ?>"  value="Vaciar">
                        </fieldset>
                    <?php endif ?>
                    </form>
                    </body>
                    </html>
