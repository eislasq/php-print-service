<?php

  try {
      $arrRespuesta;
      $fpi = fopen('/dev/usb/lp1', 'r+');
      fwrite($fpi, "\x1b\x70\x30\x37\x79");
      fwrite($fpi, $_GET["ticket"]["ticket"]);
      fwrite($fpi, "\x1dV" . chr(66) . chr(3));
      fclose($fpi);
      $arrRespuesta = array("status" => "success");
   } catch (Exception $ex) {
      $arrRespuesta = array("status" => "error");
   }

   echo 'callBackTicket(' . json_encode($arrRespuesta) . ')';
