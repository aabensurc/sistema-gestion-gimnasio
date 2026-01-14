<?php
// NOTA: Asume que las variables como $venta_data, $detalle_venta_data, etc., ya están cargadas 

// 1. Configuración de estilos para impresión térmica (ancho estrecho)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boleta Venta #<?php echo $venta_data['id_venta']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            width: 100%; 
            margin: 0;
            padding: 0;
        }
        .ticket {
            /* 1. REINTRODUCIR PADDING: 2.5mm es ideal para 80mm de ancho */
            padding: 2.5mm; 
        }
        .header, .footer, .details, .items {
            /* 3. ESPACIADO VERTICAL: Aumentar margin-bottom de 5px a 8px */
            margin-bottom: 8px; 
            text-align: center;
        }
        .details {
            /* CAMBIO CLAVE: Cambiar a centrado */
            text-align: center; 
            /* 3. ESPACIADO VERTICAL: Aumentar margin-bottom de 5px a 8px */
            margin-bottom: 8px; 
        }
        .details p {
            /* REMOVER line-height, ya que el texto será más compacto al centro */
            margin: 0; 
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 5px;
        }
        .items th, .items td {
            padding: 2px 0;
            text-align: right;
            border-bottom: 1px dotted #ccc; /* Línea separadora discreta */
        }
        .items thead th {
             border-bottom: 1px solid #000; /* Línea sólida bajo encabezados */
        }
        
        /* 2. ALINEACIÓN DE COLUMNAS (Optimización de Anchos) */
        .items th:first-child, .items td:first-child {
            text-align: left;
            width: 48%; /* Producto con más espacio */
        }
        .items th:nth-child(2), .items td:nth-child(2) {
            width: 10%; /* Cantidad */
        }
        .items th:nth-child(3), .items td:nth-child(3) {
            width: 21%; /* Precio Unitario */
        }
        .items th:nth-child(4), .items td:nth-child(4) {
            width: 21%; /* Subtotal */
        }
        /* Fin de la optimización de columnas */

        .total {
            /* MANTENER TAMAÑO GRANDE, PERO FORZAREMOS EL TAMAÑO SOLO EN EL HTML */
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
            text-align: right; 
        }
        .total p {
            margin: 2px 0;
            /* Añadir un margen izquierdo para reducir el espacio disponible para el texto */
            padding-left: 5mm; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .logo {
            max-width: 50px;
            height: auto;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        
        <div class="header">
            <?php 
            $ruta_logo = $URL.'/public/images/gimnasios/'.$_SESSION['imagen_gimnasio_sesion'];
            if(file_exists('../../../public/images/gimnasios/'.$_SESSION['imagen_gimnasio_sesion'])) {
                echo '<img src="'.$ruta_logo.'" class="logo" alt="Logo">';
            }
            ?>
            <div style="font-size: 12px; font-weight: bold;"><?php echo $_SESSION['nombre_gimnasio_sesion'] ?? 'Gimnasio Desconocido'; ?></div>
            <div>**BOLETA DE VENTA**</div>
            <div>ID VENTA: #<?php echo $venta_data['id_venta']; ?></div>
        </div>

        <div class="details">
            <p>**Fecha:** <?php echo date('Y-m-d H:i:s', strtotime($venta_data['fecha_venta'])); ?></p>
            <p>**Cliente:** <?php echo $venta_data['nombre_completo_cliente'] ?? 'Anónimo'; ?></p>
            <p>**Atendido por:** <?php echo $_SESSION['sesion_nombres'] ?? 'Sistema'; ?></p>
        </div>
        
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th style="width: 48%;">Producto</th>
                        <th style="width: 10%;">Cant.</th>
                        <th style="width: 21%;">P. Unit</th>
                        <th style="width: 21%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($detalle_venta_data)): ?>
                        <?php foreach ($detalle_venta_data as $item): ?>
                            <tr>
                                <td><?php echo $item['nombre_producto'] ?? 'Producto Eliminado'; ?></td>
                                <td><?php echo $item['cantidad']; ?></td>
                                <td><?php echo number_format($item['precio_unitario'], 2); ?></td>
                                <td><?php echo number_format($item['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">-- Sin productos --</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="total">
            <p>Total Productos: S/. <?php echo number_format($venta_data['monto_total'] + $venta_data['descuento_total'], 2); ?></p>
            <?php if ($venta_data['descuento_total'] > 0): ?>
            <p>Descuento: S/. -<?php echo number_format($venta_data['descuento_total'], 2); ?></p>
            <?php endif; ?>
            <p style="font-size: 12px; white-space: nowrap;">**TOTAL A PAGAR:** S/. <?php echo number_format($venta_data['monto_total'], 2); ?></p>
            <p>Total Pagado: S/. <?php echo number_format($venta_data['total_pagado'] ?? 0, 2); ?></p>
            <?php if (($venta_data['monto_total'] - ($venta_data['total_pagado'] ?? 0)) > 0): ?>
            <p style="color: red;">Pendiente: S/. <?php echo number_format($venta_data['monto_total'] - ($venta_data['total_pagado'] ?? 0), 2); ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p style="margin-top: 10px;">¡Gracias por su compra!</p>
        </div>

    </div>
</body>
</html>