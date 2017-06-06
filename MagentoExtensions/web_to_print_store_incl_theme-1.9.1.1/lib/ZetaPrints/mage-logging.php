<?php

function _zetaprints_debug ($msg = null) {
  $backtrace = debug_backtrace();

  $callee_name = $backtrace[1]['function'];

  if (!$msg)
    $msg = "function parameters:\n" . var_export($backtrace[1]['args'], true);
  else if (is_array($msg))
    $msg = "\n" . var_export($msg, true);

  Mage::log("$callee_name: $msg");
}

?>
