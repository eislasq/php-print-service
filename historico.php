<?php

$arrRespuesta = null;
$results = null;
$comando = '';
$idcta = 0;


//$_GET['historico']= json_decode(file_get_contents('get.txt'));

try {

    if (isset($_GET['historico'])) {

        $results = json_encode($_GET['historico']);
        $results = json_decode($results, true);
//        var_dump($results);

        $comando =  "    ++++++++++++++++++++++++++++++++++++++++++\n";
        $comando .= "    +                                        +\n";
        $comando .= "    +         ESTE ES UN HISTORICO DE        +\n";
        $comando .= "    +        LAS VENTAS REALIZADAS AL        +\n";
        $comando .= "    +                CLIENTE                 +\n";
        $comando .= "    +                                        +\n";
        $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";
        $comando .= "    +                                        +\n";
        $comando .= "    +             BONETERA CHAVE             +\n";
        $comando .= "    +              LOS POBLANOS              +\n";
        $comando .= "    +          TEL: (954) 582-2523           +\n";
        $comando .= "    +           RFC: OELJ801118130           +\n";
        $comando .= "    +        TERCERA PONIENTE # 803          +\n";
        $comando .= "    +       PUERTO ESCONDIDO, OAXACA         +\n";
        $comando .= "    +                                        +\n";
        $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";
        $comando .= "    Sucursal: " . $results[0]['sucursal'] .  "\n";
        $comando .= "    No. Cliente: " . str_pad($results[0]['idproveedorCliente'], 10, "0", STR_PAD_LEFT) . "\n";
        $comando .= "    Cliente: " . (isset($results[0]['cliente']) ? $results[0]['cliente'] : "MOSTRADOR") . "\n";


        foreach($results as $item) {
            if($idcta !== $item["idVenta"]) {
                $saldo = 0;
//                if($idVenta === $item["idVenta"]) {
//                    $comando .= "    +****************************************+\n";
//                    $comando .= "    Vendedor: " . str_pad($item['idUsuario'], 5, "0", STR_PAD_LEFT) . "\n";
//                    $comando .= "    No. Venta: " . str_pad($item["idVenta"], 10, "0", STR_PAD_LEFT) . "\n";
//                    $comando .= "    Fecha: " . date("d/m/Y", strtotime($item['dtFecha'])) . "\t Hora: " . date("G:i:s", strtotime($item['dtFecha'])) . "\n";
//                    $comando .= "    Venta: $" . number_format($item["venta"],0,".",",") . "\t Descuento: " . $item["iDescuento"] . "%\n";
//                    $comando .= "    Total Venta: $" . number_format($item['dMonto'],0,".",",") . "\n";
//                    $comando .= "    +****************************************+\n";
//                    $comando .= "    Tipo Fecha   C(+)    A(-)   Sld($)  V.A.\n";
//                } else {
                    $comando .= "    +========================================+\n";
                    $comando .= "    Vendedor: " . str_pad($item['idUsuario'], 5, "0", STR_PAD_LEFT) . "\n";
                    $comando .= "    No. Venta: " . str_pad($item["idVenta"], 10, "0", STR_PAD_LEFT) . "\n";
                    $comando .= "    Fecha: " . date("d/m/Y", strtotime($item['dtFecha'])) . "\t Hora: " . date("G:i:s", strtotime($item['dtFecha'])) . "\n";
                    $comando .= "    Venta: $" . number_format($item["venta"],0,".",",") . "\t Descuento: " . $item["iDescuento"] . "%\n";
                    $comando .= "    Total Venta: $" . number_format($item['dMonto'],0,".",",") . "\n";
                    $comando .= "    +========================================+\n";
                    $comando .= "    Tipo Fecha   C(+)    A(-)   Sld($)  V.A.\n";
//                }
                $idcta = $item["idVenta"];
            }
            if($item["idtipoPago"] === '1') {
                $abono = '       ';
                $cargo = str_pad((int) $item['dMonto'], 6, " ", STR_PAD_RIGHT);
                $vtaAn = ($item['idvtaAnt'] === '0')?'':$item['idvtaAnt'];
            } else {
                $abono = str_pad((int) ($item['dMonto']*-1), 6, " ", STR_PAD_RIGHT);
                $cargo = '       ';
            }
            $saldo = str_pad($saldo + $item['dMonto'], 7, " ", STR_PAD_RIGHT);
            $comando .= "    " . substr($item["cDescripcion"],0,3) . "  " . date("d/n/y", strtotime($item['dtFecha'])) 
                    . " " .  $cargo . " " . $abono . " " . $saldo . " " . $vtaAn . "\n";
            $vtaAn = '';
        }

        $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";
        $comando .= "             GRACIAS POR SU COMPRA\n";
        $comando .= "                 VUELVE PRONTO\n";
        $comando .= "             ---------------------\n";
        $comando .= "       CONSERVAR TICKET INDISPENSABLE PARA\n";
        $comando .= "       CANCELACION/DEVOLUCION O ACLARACION\n";
        $comando .= "    ++++++++++++++++++++++++++++++++++++++++++\n";

        echo $comando;
        $file = fopen('/dev/usb/lp0', 'r+');
        fwrite($file, $comando);
        fclose($file);
        $arrRespuesta = array("status" => "success");
    } else {
        $arrRespuesta = array("status" => "error");
    }
} catch (Exception $ex) {
    $arrRespuesta = array("status" => "error");
}
echo 'callBackTicket(' . json_encode($arrRespuesta) . ')';
