<?php

    $arrRespuesta = array();
    $comando ='';
    $enlace = '';
    $sql = '';
    $montoTotal = 0;
    $row = '';
    $results = '';

    try {

        $db = new PDO('mysql:host=127.0.0.1;dbname=bonetera_v1', 'ticket', '1qa2ws3ed4rf5tg6yh7uj8ik9ol');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = "select * from ventav vtav where vtav.idVenta = " . $_GET["ticket"]["ticket"]["id"];

        $resp = $db->prepare($sql);
        $resp->execute();
        $results = $resp->fetchAll(PDO::FETCH_ASSOC);

        if(count($results)>0) {

            if($_GET["ticket"]["ticket"]["tipo"] > 0 ) {
                $comando = "    ++++++++++++++++++++++++++++++++++++++++++\n";
                $comando .= "    +                                        +\n";
                $comando .= "    +      ESTE ES UNA COPIA DEL             +\n";
                $comando .= "    +     TICKET DE VENTA ORIGINAL           +\n";
                $comando .= "    +                                        +\n";
                $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";
                $comando .= "    Fecha de reimpresión: " . date("d/m/Y G:i:s") . "\n";
                $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";
            } else {
                $comando = "    ++++++++++++++++++++++++++++++++++++++++\n";
            }
            $comando .= "    +             BONETERA CHAVE             +\n";
            $comando .= "    +              LOS POBLANOS              +\n";
            $comando .= "    +          TEL: (954) 582-2523           +\n";
            $comando .= "    +           RFC: OELJ801118130           +\n";
            $comando .= "    +        TERCERA PONIENTE # 803          +\n";
            $comando .= "    +       PUERTO ESCONDIDO, OAXACA         +\n";
            $comando .= "    ++++++++++++++++++++++++++++++++++++++++\n\n";
            $comando .= "    Sucursal: " . $results[0]['sucursal'] . "\n";
            $comando .= "    +======================================+\n";
            $comando .= "    Fecha: " . date("d/m/Y", strtotime($results[0]['dtCreacion'])) . "\t Hora: " . date("G:i:s", strtotime($results[0]['dtCreacion'])) . "\n";
            $comando .= "    Vendedor: " . str_pad($results[0]['idUsuario'], 5, "0", STR_PAD_LEFT) . "\n";
            $comando .= "    No. Cliente: " . str_pad($results[0]['idproveedorCliente'], 10, "0", STR_PAD_LEFT) . "\n";
            $comando .= "    Cliente: " . (isset($results[0]['cliente']) ? $results[0]['cliente'] : "MOSTRADOR") . "\n";
            $comando .= "    Venta: " . str_pad($results[0]['idVenta'], 10, "0", STR_PAD_LEFT) . "\n";
            $comando .= "    +======================================+\n";
            $comando .= "    Cant. Clave            Precio    Total\n";

            foreach ($results as $item) {
                $cadena = "";
                $cantidad = "";
                $subMonto = "";
                $cantidad = '     ' . str_replace("-", "", $item['icantidadPedida']);
                $subMonto = $cantidad * $item['dPrecio'];
                $montoTotal += $subMonto;
                $cadena = str_replace('ñ', 'n', utf8_encode($item['producto']));
                $cadena = str_replace('Ñ', 'N', $cadena);
                $cadena = strtoupper($cadena);
                $comando .= $cadena . "\n";
                $comando .= str_pad($cantidad, 6) . '    ' . str_pad($item['id'], 15) . '  ' . str_pad("$" . $item['dPrecio'], 8) . "  $" . $subMonto . "\n";
            }

            $comando .= "                                 ---------\n";
            $comando .= "        SubTotal de Venta:       $" . $montoTotal . "\n";
            $comando .= "            Descuento (%):        " . $results[0]['iDescuento'] . "%\n";
            $comando .= "              Costo Extra:       $" . $results[0]['iAumento'] . "\n";
            $comando .= "                   Adeudo:       $" . $results[0]['dAdeudo'] . "\n";
            $comando .= "              Total Venta:       $" . round((($results[0]['dAdeudo'] + $montoTotal + $results[0]['iAumento']) - ($montoTotal * ($results[0]['iDescuento'] / 100)))) . "\n\n";
            $comando .= "    +****************************************+\n";
            $comando .= "             GRACIAS POR SU COMPRA\n";
            $comando .= "                 VUELVE PRONTO\n";
            $comando .= "             ---------------------\n";
            $comando .= "       CONSERVAR TICKET INDISPENSABLE PARA\n";
            $comando .= "       CANCELACION/DEVOLUCION O ACLARACION\n";
            $comando .= "    +****************************************+\n";

            $fpi = fopen('/dev/usb/lp0', 'r+');
            fwrite($fpi, "\x1b\x70\x30\x37\x79");
            fwrite($fpi, $comando);
            fwrite($fpi, "\x1dV" . chr(66) . chr(3));
            fclose($fpi);

            $arrRespuesta = array("status" => "success");
        }

    } catch (Exception $ex) {
        $arrRespuesta = array("status" => "error", "error" => $ex->getMessage());
    }

echo 'callBackTicket(' . json_encode($arrRespuesta) . ')';
