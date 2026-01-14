<?php
include('app/config.php');
session_start();

// Validar que hay sesión y es admin (algo básico, rol 1)
// Aunque esto es un script helper que pedí permiso para crear (o asumí en el plan)
// Voy a actualizar el ROL administrador (id=1) para que tenga el permiso 15.

$id_rol = 1; // Rol Administrador

// Obtener permisos actuales
$sql = "SELECT permisos FROM tb_roles WHERE id_rol = :id_rol";
$query = $pdo->prepare($sql);
$query->bindParam(':id_rol', $id_rol);
$query->execute();
$role = $query->fetch(PDO::FETCH_ASSOC);

if ($role) {
    $permisos_actuales = json_decode($role['permisos'], true);
    if (!$permisos_actuales) {
        $permisos_actuales = [];
    }

    if (!in_array("15", $permisos_actuales)) {
        $permisos_actuales[] = "15"; // Agregar como string para consistencia con lo existente
        $permisos_json = json_encode($permisos_actuales);

        $update = $pdo->prepare("UPDATE tb_roles SET permisos = :permisos WHERE id_rol = :id_rol");
        $update->bindParam(':permisos', $permisos_json);
        $update->bindParam(':id_rol', $id_rol);

        if ($update->execute()) {
            echo "Permiso 15 agregado exitosamente al Rol 1.<br>";

            // Actualizar sesión actual si corresponde
            if (isset($_SESSION['rol_sesion']) && $_SESSION['rol_sesion'] == 'ADMINISTRADOR') { // o id_rol == 1
                $_SESSION['permisos_sesion'] = $permisos_actuales;
                echo "Sesión actualizada. Recarga la página.<br>";
            }
        } else {
            echo "Error al actualizar BD.";
        }
    } else {
        echo "El Rol 1 ya tiene el permiso 15.";
    }
} else {
    echo "Rol 1 no encontrado.";
}
