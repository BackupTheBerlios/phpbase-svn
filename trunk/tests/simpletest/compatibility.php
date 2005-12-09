<?php
 class SimpleTestCompatibility { function copy($object) { if (version_compare(phpversion(), '5') >= 0) { eval('$copy = clone $object;'); return $copy; } return $object; } function isIdentical($first, $second) { if ($first != $second) { return false; } if (version_compare(phpversion(), '5') >= 0) { return SimpleTestCompatibility::_isIdenticalType($first, $second); } return ($first === $second); } function _isIdenticalType($first, $second) { if (gettype($first) != gettype($second)) { return false; } if (is_object($first) && is_object($second)) { if (get_class($first) != get_class($second)) { return false; } return SimpleTestCompatibility::_isArrayOfIdenticalTypes( get_object_vars($first), get_object_vars($second)); } if (is_array($first) && is_array($second)) { return SimpleTestCompatibility::_isArrayOfIdenticalTypes($first, $second); } return true; } function _isArrayOfIdenticalTypes($first, $second) { if (array_keys($first) != array_keys($second)) { return false; } foreach (array_keys($first) as $key) { $is_identical = SimpleTestCompatibility::_isIdenticalType( $first[$key], $second[$key]); if (! $is_identical) { return false; } } return true; } function isReference(&$first, &$second) { if (version_compare(phpversion(), '5', '>=') && is_object($first)) { return ($first === $second); } if (is_object($first) && is_object($second)) { $id = uniqid("test"); $first->$id = true; $is_ref = isset($second->$id); unset($first->$id); return $is_ref; } $temp = $first; $first = uniqid("test"); $is_ref = ($first === $second); $first = $temp; return $is_ref; } function isA($object, $class) { if (version_compare(phpversion(), '5') >= 0) { if (! class_exists($class, false) && (function_exists('interface_exists') && !interface_exists($class, false)) ) { return false; } eval("\$is_a = \$object instanceof $class;"); return $is_a; } if (function_exists('is_a')) { return is_a($object, $class); } return ((strtolower($class) == get_class($object)) or (is_subclass_of($object, $class))); } function setTimeout($handle, $timeout) { if (function_exists('stream_set_timeout')) { stream_set_timeout($handle, $timeout, 0); } elseif (function_exists('socket_set_timeout')) { socket_set_timeout($handle, $timeout, 0); } elseif (function_exists('set_socket_timeout')) { set_socket_timeout($handle, $timeout, 0); } } function getStackTrace() { if (function_exists('debug_backtrace')) { return array_reverse(debug_backtrace()); } return array(); } } ?>