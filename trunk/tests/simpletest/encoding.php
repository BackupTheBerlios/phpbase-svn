<?php
 require_once(dirname(__FILE__) . '/socket.php'); class SimpleEncodedPair { var $_key; var $_value; function SimpleEncodedPair($key, $value) { $this->_key = $key; $this->_value = $value; } function asRequest() { return $this->_key . '=' . urlencode($this->_value); } function asMime() { $part = 'Content-Disposition: form-data; '; $part .= "name=\"" . $this->_key . "\"\r\n"; $part .= "\r\n" . $this->_value; return $part; } function isKey($key) { return $key == $this->_key; } function getKey() { return $this->_key; } function getValue() { return $this->_value; } } class SimpleAttachment { var $_key; var $_content; var $_filename; function SimpleAttachment($key, $content, $filename) { $this->_key = $key; $this->_content = $content; $this->_filename = $filename; } function asRequest() { return ''; } function asMime() { $part = 'Content-Disposition: form-data; '; $part .= 'name="' . $this->_key . '"; '; $part .= 'filename="' . $this->_filename . '"'; $part .= "\r\nContent-Type: " . $this->_deduceMimeType(); $part .= "\r\n\r\n" . $this->_content; return $part; } function _deduceMimeType() { if ($this->_isOnlyAscii($this->_content)) { return 'text/plain'; } return 'application/octet-stream'; } function _isOnlyAscii($ascii) { for ($i = 0, $length = strlen($ascii); $i < $length; $i++) { if (ord($ascii[$i]) > 127) { return false; } } return true; } function isKey($key) { return $key == $this->_key; } function getKey() { return $this->_key; } function getValue() { return $this->_filename; } } class SimpleEncoding { var $_request; function SimpleEncoding($query = false) { if (! $query) { $query = array(); } $this->clear(); $this->merge($query); } function clear() { $this->_request = array(); } function add($key, $value) { if ($value === false) { return; } if (is_array($value)) { foreach ($value as $item) { $this->_addPair($key, $item); } } else { $this->_addPair($key, $value); } } function _addPair($key, $value) { $this->_request[] = new SimpleEncodedPair($key, $value); } function attach($key, $content, $filename) { $this->_request[] = new SimpleAttachment($key, $content, $filename); } function merge($query) { if (is_object($query)) { $this->_request = array_merge($this->_request, $query->getAll()); } elseif (is_array($query)) { foreach ($query as $key => $value) { $this->add($key, $value); } } } function getValue($key) { $values = array(); foreach ($this->_request as $pair) { if ($pair->isKey($key)) { $values[] = $pair->getValue(); } } if (count($values) == 0) { return false; } elseif (count($values) == 1) { return $values[0]; } else { return $values; } } function getAll() { return $this->_request; } function _encode() { $statements = array(); foreach ($this->_request as $pair) { if ($statement = $pair->asRequest()) { $statements[] = $statement; } } return implode('&', $statements); } } class SimpleGetEncoding extends SimpleEncoding { function SimpleGetEncoding($query = false) { $this->SimpleEncoding($query); } function getMethod() { return 'GET'; } function writeHeadersTo(&$socket) { } function writeTo(&$socket) { } function asUrlRequest() { return $this->_encode(); } } class SimpleHeadEncoding extends SimpleGetEncoding { function SimpleHeadEncoding($query = false) { $this->SimpleGetEncoding($query); } function getMethod() { return 'HEAD'; } } class SimplePostEncoding extends SimpleEncoding { function SimplePostEncoding($query = false) { $this->SimpleEncoding($query); } function getMethod() { return 'POST'; } function writeHeadersTo(&$socket) { $socket->write("Content-Length: " . (integer)strlen($this->_encode()) . "\r\n"); $socket->write("Content-Type: application/x-www-form-urlencoded\r\n"); } function writeTo(&$socket) { $socket->write($this->_encode()); } function asUrlRequest() { return ''; } } class SimpleMultipartEncoding extends SimplePostEncoding { var $_boundary; function SimpleMultipartEncoding($query = false, $boundary = false) { $this->SimplePostEncoding($query); $this->_boundary = ($boundary === false ? uniqid('st') : $boundary); } function writeHeadersTo(&$socket) { $socket->write("Content-Length: " . (integer)strlen($this->_encode()) . "\r\n"); $socket->write("Content-Type: multipart/form-data, boundary=" . $this->_boundary . "\r\n"); } function writeTo(&$socket) { $socket->write($this->_encode()); } function _encode() { $stream = ''; foreach ($this->_request as $pair) { $stream .= "--" . $this->_boundary . "\r\n"; $stream .= $pair->asMime() . "\r\n"; } $stream .= "--" . $this->_boundary . "--\r\n"; return $stream; } } ?>