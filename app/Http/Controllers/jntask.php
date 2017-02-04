<?php
//error_reporting(E_ALL);

function jntask($cmd) {
   $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
   if ($socket === false) {
      echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
   }

   $result = socket_connect($socket, '127.0.0.1', 8888);
   if ($result === false) {
      echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
   }

   $in = 'S{"cmd":"'.$cmd.'"}';
   $out = '';

   socket_write($socket, $in, strlen($in));
   $out = socket_read($socket, 2048);

   socket_close($socket);
   return $out;
}
