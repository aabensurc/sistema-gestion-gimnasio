<?php
// Función para verificar si el usuario actual tiene un permiso específico
// $permiso_requerido: Es el ID entero del permiso que se está intentando acceder (ej: 2 para 'Usuarios')

function verificarPermiso($permiso_requerido) {
    // La variable de sesión 'permisos_sesion' debe contener el array de IDs decodificados (ej: [1, 2, 5])
    // Este array se DEBE cargar en sesion.php cuando el usuario inicia sesión.
    
    // Si la variable de sesión no existe o no es un array, asumimos que no tiene permisos
    if (!isset($_SESSION['permisos_sesion']) || !is_array($_SESSION['permisos_sesion'])) {
        return false;
    }
    
    // Verificamos si el ID del permiso requerido está dentro del array de permisos del usuario
    return in_array($permiso_requerido, $_SESSION['permisos_sesion']);
}

// Esta función se usará en las páginas a proteger
// $id_permiso: El ID numérico del permiso que se requiere para ver la página (ej: 2)
function requirePermiso($id_permiso) {
    global $URL; // Necesitamos $URL para la redirección

    if (!verificarPermiso($id_permiso)) {
        // Redirigir a la página de acceso no autorizado
        // Usamos una redirección con header() antes de que se envíe cualquier HTML
        header('Location: ' . $URL . '/layout/no_autorizado.php');
        exit; // Detener la ejecución del script
    }
    // Si tiene permiso, el script continúa normalmente.
}
?>