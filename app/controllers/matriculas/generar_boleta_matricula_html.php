<?php
// Plantilla HTML para Boleta de Matrícula
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boleta Matrícula #<?php echo $matricula_data['id_matricula']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            width: 100%; 
            margin: 0;
            padding: 0;
        }
        .ticket {
            padding: 2.5mm; 
        }
        .header, .footer, .items {
            margin-bottom: 8px; 
            text-align: center;
        }
        .details {
            text-align: center; 
            margin-bottom: 8px;
        }
        .details p {
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
            border-bottom: 1px dotted #ccc; 
        }
        .items thead th {
             border-bottom: 1px solid #000;
        }
        /* Ajuste de columnas para Plan y Fechas */
        .items th:first-child, .items td:first-child {
            text-align: left;
            width: 60%;
        }
        .items th:nth-child(2), .items td:nth-child(2) {
            width: 40%;
        }
        
        .total {
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }
        .total p {
            margin: 2px 0;
            padding-left: 5mm; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .logo {
            max-width: 50px;
            height: auto;
            margin-bottom: 5px;
        }
        .vigencia {
            font-size: 9px;
            text-align: center;
            margin: 5px 0;
            border: 1px solid #ccc;
            padding: 2px;
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
            <div style="font-size: 12px; font-weight: bold;"><?php echo $_SESSION['nombre_gimnasio_sesion'] ?? 'Gimnasio'; ?></div>
            <div>**BOLETA DE MATRÍCULA**</div>
            <div>ID: #<?php echo $matricula_data['id_matricula']; ?></div>
        </div>

        <div class="details">
            <p>**Fecha Emisión:** <?php echo date('Y-m-d H:i'); ?></p>
            <p>**Cliente:** <?php echo $matricula_data['nombre_completo_cliente']; ?></p>
            <p>**Atendido por:** <?php echo $_SESSION['sesion_nombres'] ?? 'Sistema'; ?></p>
        </div>
        
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Descripción Plan</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo $matricula_data['nombre_plan']; ?>
                        </td>
                        <td>
                            S/. <?php echo number_format($matricula_data['monto_final'] + $matricula_data['descuento'], 2); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="vigencia">
            <strong>Vigencia del Plan:</strong><br>
            Del <?php echo date('d/m/Y', strtotime($matricula_data['fecha_inicio'])); ?> 
            al <?php echo date('d/m/Y', strtotime($matricula_data['fecha_fin'])); ?>
        </div>

        <div class="total">
            <?php if ($matricula_data['descuento'] > 0): ?>
            <p>Subtotal: S/. <?php echo number_format($matricula_data['monto_final'] + $matricula_data['descuento'], 2); ?></p>
            <p>Descuento: S/. -<?php echo number_format($matricula_data['descuento'], 2); ?></p>
            <?php endif; ?>
            
            <p style="font-size: 12px; white-space: nowrap;">**TOTAL A PAGAR:** S/. <?php echo number_format($matricula_data['monto_final'], 2); ?></p>
            <p>Total Pagado: S/. <?php echo number_format($matricula_data['total_pagado'] ?? 0, 2); ?></p>
            
            <?php if (($matricula_data['monto_final'] - ($matricula_data['total_pagado'] ?? 0)) > 0): ?>
            <p style="color: red;">Pendiente: S/. <?php echo number_format($matricula_data['monto_final'] - ($matricula_data['total_pagado'] ?? 0), 2); ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p style="margin-top: 10px;">¡Gracias por su preferencia!</p>
            <p style="font-size: 8px;">Conserve este ticket para su control de ingreso.</p>
        </div>

    </div>
</body>
</html>