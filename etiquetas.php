<?php

    function formatoFecha($fecha) {

        $cadFinal = "";
        $arrMes = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E", "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J", "11" => "K", "12" => "L");
        $cadFinal = $arrMes[date("n", strtotime($fecha))] . date("d", strtotime($fecha)) . date("y", strtotime($fecha));

        return $cadFinal;
    }



$arrRespuesta = null;
$results = null;
$comando = '';

try {

    if (isset($_GET['etiquetas'])) {

 		$results = json_encode($_GET['etiquetas']);
 		$results = json_decode($results, true);

 		       foreach ($results as $item) {

 		           if ($item['icantidadPedida'] > 1) {
 		               $comando .= "N\n";
 		               $comando .= "q770\n";
 		               $comando .= "S2\n";
 		
                               $comando .= "A0,10,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 0, 21)) . "\"\n";
                               $comando .= "A0,40,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 21, 21)) . "\"\n";
                               $comando .= "A0,70,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 42, 21)) . "\"\n";
                               $comando .= "A0,144,0,4,1,1,N,\"" . str_pad($item['idProducto'], 8, "0", STR_PAD_LEFT) . "\"\n";
                               $comando .= "A204,144,0,4,1,1,N,\"" . formatoFecha($item['dtCreacion']) . "-" . $item['idPedido'] . "\"\n";

                               $comando .= "A411,10,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 0, 21)) . "\"\n";
                               $comando .= "A411,40,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 21, 21)) . "\"\n";
                               $comando .= "A411,70,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 42, 21)) . "\"\n";
                               $comando .= "A411,144,0,4,1,1,N,\"" . str_pad($item['idProducto'], 8, "0", STR_PAD_LEFT) . "\"\n";
                               $comando .= "A604,144,0,4,1,1,N,\"" . formatoFecha($item['dtCreacion']) . "-" . $item['idPedido'] . "\"\n";

 		               $numLabel = floor($item['icantidadPedida'] / 2);
 		               $comando .= "P" . $numLabel . "\n";
 		           }
 		
 		           // Valida cuando son nones
 		           if (($item['icantidadPedida'] % 2) > 0) {
 		               $comando .= "N\n";
 		               $comando .= "q770\n";
 		               $comando .= "S2\n";

                               $comando .= "A0,10,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 0, 21)) . "\"\n";
                               $comando .= "A0,40,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 21, 21)) . "\"\n";
                               $comando .= "A0,70,0,4,1,1,N,\"" . utf8_decode(substr($item['cNombre'], 42, 21)) . "\"\n";
                               $comando .= "A0,144,0,4,1,1,N,\"" . str_pad($item['idProducto'], 8, "0", STR_PAD_LEFT) . "\"\n";
                               $comando .= "A204,144,0,4,1,1,N,\"" . formatoFecha($item['dtCreacion']) . "-" . $item['idPedido'] . "\"\n";

 		               $comando .= "P1\n";
 		           }
 		       }
 		
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

?>

