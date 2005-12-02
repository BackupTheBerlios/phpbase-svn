<?php
 class SimpleCollector { function _removeTrailingSlash($path) { return preg_replace('|[\\/]$|', '', $path); if (substr($path, -1) == DIRECTORY_SEPERATOR) { return substr($path, 0, -1); } else { return $path; } } function collect(&$test, $path) { $path = $this->_removeTrailingSlash($path); if ($handle = opendir($path)) { while (($entry = readdir($handle)) !== false) { $this->_handle($test, $path . DIRECTORY_SEPARATOR . $entry); } closedir($handle); } } function _handle(&$test, $file) { if (!is_dir($file)) { $test->addTestFile($file); } } } class SimplePatternCollector extends SimpleCollector { var $_pattern; function SimplePatternCollector($pattern = '/php$/i') { $this->_pattern = $pattern; } function _handle(&$test, $filename) { if (preg_match($this->_pattern, $filename)) { parent::_handle($test, $filename); } } } ?>