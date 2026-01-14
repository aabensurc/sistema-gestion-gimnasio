<?php

session_start(); // <-- ¡AÑADIDO! Necesario para acceder a la sesión en esta nueva petición HTTP.

include('../../config.php'); // Asegúrate de que la ruta a config.php sea correcta

$search_term = $_GET['q'] ?? ''; // Término de búsqueda de Select2

$results = [];

// 1. Verificar si la sesión del gimnasio está disponible
if (!isset($_SESSION['id_gimnasio_sesion'])) {
    // Si la sesión no está definida, retornamos un array vacío por seguridad
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Usamos una variable para el ID del gimnasio y preparamos el término de búsqueda
$id_gimnasio = $_SESSION['id_gimnasio_sesion'];

if (!empty($search_term)) {
    try {
        // 2. Modificación de la consulta SQL: Se elimina 'codigo' y se incluye 'id_cliente' para búsqueda
        $sql_clients = "SELECT
                            id_cliente,
                            dni,
                            nombres,
                            ape_pat,
                            ape_mat
                        FROM
                            tb_clientes
                        WHERE
                            (CAST(id_cliente AS CHAR) LIKE :search_term  -- Se busca por id_cliente (antes era codigo)
                            OR nombres LIKE :search_term
                            OR ape_pat LIKE :search_term
                            OR ape_mat LIKE :search_term
                            OR dni LIKE :search_term)
                        AND id_gimnasio = :id_gimnasio_sesion  -- <-- FILTRO DE GIMNASIO AÑADIDO
                        LIMIT 10";

        $query_clients = $pdo->prepare($sql_clients);
        $param = '%' . $search_term . '%';
        
        // 3. Enlazar parámetros
        $query_clients->bindParam(':search_term', $param);
        $query_clients->bindParam(':id_gimnasio_sesion', $id_gimnasio); // <-- ENLACE DEL ID DEL GIMNASIO
        
        $query_clients->execute();
        $clientes_encontrados = $query_clients->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clientes_encontrados as $cliente) {
            $text = $cliente['nombres'] . ' ' . $cliente['ape_pat'] . ' ' . $cliente['ape_mat'];
            
            // Se incluye el ID del cliente en el texto de resultado de Select2
            $text .= ' (ID: ' . $cliente['id_cliente'] . ')';

            if (!empty($cliente['dni'])) {
                $text .= ' (DNI: ' . $cliente['dni'] . ')';
            }
            
            // Ya no se referencia el campo 'codigo'
            
            $results[] = [
                'id' => $cliente['id_cliente'],
                'text' => $text
            ];
        }

    } catch (PDOException $e) {
        error_log("Error de PDO en search_clients.php: " . $e->getMessage());
        // En un entorno de producción, solo mostrar un mensaje genérico.
        // En desarrollo, puedes descomentar la línea de abajo para debugging.
        // echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}

header('Content-Type: application/json');
echo json_encode($results);

// Asegurarse de que no haya salida adicional después del JSON
exit;
