<?php
 if (!defined('TYPE_MATTERS')) { define('TYPE_MATTERS', true); } class SimpleDumper { function describeValue($value) { $type = $this->getType($value); switch($type) { case "Null": return "NULL"; case "Boolean": return "Boolean: " . ($value ? "true" : "false"); case "Array": return "Array: " . count($value) . " items"; case "Object": return "Object: of " . get_class($value); case "String": return "String: " . $this->clipString($value, 100); default: return "$type: $value"; } return "Unknown"; } function getType($value) { if (! isset($value)) { return "Null"; } elseif (is_bool($value)) { return "Boolean"; } elseif (is_string($value)) { return "String"; } elseif (is_integer($value)) { return "Integer"; } elseif (is_float($value)) { return "Float"; } elseif (is_array($value)) { return "Array"; } elseif (is_resource($value)) { return "Resource"; } elseif (is_object($value)) { return "Object"; } return "Unknown"; } function describeDifference($first, $second, $identical = false) { if ($identical) { if (! $this->_isTypeMatch($first, $second)) { return "with type mismatch as [" . $this->describeValue($first) . "] does not match [" . $this->describeValue($second) . "]"; } } $type = $this->getType($first); if ($type == "Unknown") { return "with unknown type"; } $method = '_describe' . $type . 'Difference'; return $this->$method($first, $second, $identical); } function _isTypeMatch($first, $second) { return ($this->getType($first) == $this->getType($second)); } function clipString($value, $size, $position = 0) { $length = strlen($value); if ($length <= $size) { return $value; } $position = min($position, $length); $start = ($size/2 > $position ? 0 : $position - $size/2); if ($start + $size > $length) { $start = $length - $size; } $value = substr($value, $start, $size); return ($start > 0 ? "..." : "") . $value . ($start + $size < $length ? "..." : ""); } function _describeGenericDifference($first, $second) { return "as [" . $this->describeValue($first) . "] does not match [" . $this->describeValue($second) . "]"; } function _describeNullDifference($first, $second, $identical) { return $this->_describeGenericDifference($first, $second); } function _describeBooleanDifference($first, $second, $identical) { return $this->_describeGenericDifference($first, $second); } function _describeStringDifference($first, $second, $identical) { if (is_object($second) || is_array($second)) { return $this->_describeGenericDifference($first, $second); } $position = $this->_stringDiffersAt($first, $second); $message = "at character $position"; $message .= " with [" . $this->clipString($first, 100, $position) . "] and [" . $this->clipString($second, 100, $position) . "]"; return $message; } function _describeIntegerDifference($first, $second, $identical) { if (is_object($second) || is_array($second)) { return $this->_describeGenericDifference($first, $second); } return "because [" . $this->describeValue($first) . "] differs from [" . $this->describeValue($second) . "] by " . abs($first - $second); } function _describeFloatDifference($first, $second, $identical) { if (is_object($second) || is_array($second)) { return $this->_describeGenericDifference($first, $second); } return "because [" . $this->describeValue($first) . "] differs from [" . $this->describeValue($second) . "] by " . abs($first - $second); } function _describeArrayDifference($first, $second, $identical) { if (! is_array($second)) { return $this->_describeGenericDifference($first, $second); } if (! $this->_isMatchingKeys($first, $second, $identical)) { return "as key list [" . implode(", ", array_keys($first)) . "] does not match key list [" . implode(", ", array_keys($second)) . "]"; } foreach (array_keys($first) as $key) { if ($identical && ($first[$key] === $second[$key])) { continue; } if (! $identical && ($first[$key] == $second[$key])) { continue; } return "with member [$key] " . $this->describeDifference( $first[$key], $second[$key], $identical); } return ""; } function _isMatchingKeys($first, $second, $identical) { $first_keys = array_keys($first); $second_keys = array_keys($second); if ($identical) { return ($first_keys === $second_keys); } sort($first_keys); sort($second_keys); return ($first_keys == $second_keys); } function _describeResourceDifference($first, $second, $identical) { return $this->_describeGenericDifference($first, $second); } function _describeObjectDifference($first, $second, $identical) { if (! is_object($second)) { return $this->_describeGenericDifference($first, $second); } return $this->_describeArrayDifference( get_object_vars($first), get_object_vars($second), $identical); } function _stringDiffersAt($first, $second) { if (! $first || ! $second) { return 0; } if (strlen($first) < strlen($second)) { list($first, $second) = array($second, $first); } $position = 0; $step = strlen($first); while ($step > 1) { $step = (integer)(($step + 1)/2); if (strncmp($first, $second, $position + $step) == 0) { $position += $step; } } return $position; } function dump($variable) { ob_start(); print_r($variable); $formatted = ob_get_contents(); ob_end_clean(); return $formatted; } function getFormattedAssertionLine($stack, $format = '%d', $prefix = 'assert') { foreach ($stack as $frame) { if (isset($frame['file']) && strpos($frame['file'], 'simpletest') !== false) { if (substr(dirname($frame['file']), -10) == 'simpletest') { continue; } } if (strncmp($frame['function'], $prefix, strlen($prefix)) == 0) { return sprintf($format, $frame['line']); } } return ''; } } ?>