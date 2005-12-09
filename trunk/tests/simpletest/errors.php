<?php
 if (! defined('E_STRICT')) { define('E_STRICT', 2048); } class SimpleErrorQueue { var $_queue; function SimpleErrorQueue() { $this->clear(); } function add($severity, $message, $filename, $line, $super_globals) { array_push( $this->_queue, array($severity, $message, $filename, $line, $super_globals)); } function extract() { if (count($this->_queue)) { return array_shift($this->_queue); } return false; } function clear() { $this->_queue = array(); } function isEmpty() { return (count($this->_queue) == 0); } function &instance() { static $queue = false; if (! $queue) { $queue = new SimpleErrorQueue(); } return $queue; } function getSeverityAsString($severity) { static $map = array( E_STRICT => 'E_STRICT', E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING', E_PARSE => 'E_PARSE', E_NOTICE => 'E_NOTICE', E_CORE_ERROR => 'E_CORE_ERROR', E_CORE_WARNING => 'E_CORE_WARNING', E_COMPILE_ERROR => 'E_COMPILE_ERROR', E_COMPILE_WARNING => 'E_COMPILE_WARNING', E_USER_ERROR => 'E_USER_ERROR', E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE => 'E_USER_NOTICE'); return $map[$severity]; } } function simpleTestErrorHandler($severity, $message, $filename, $line, $super_globals) { if ($severity = $severity & error_reporting()) { restore_error_handler(); if (ini_get('log_errors')) { $label = SimpleErrorQueue::getSeverityAsString($severity); error_log("$label: $message in $filename on line $line"); } $queue = &SimpleErrorQueue::instance(); $queue->add($severity, $message, $filename, $line, $super_globals); set_error_handler('simpleTestErrorHandler'); } } ?>