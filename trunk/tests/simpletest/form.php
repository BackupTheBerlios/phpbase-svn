<?php
 require_once(dirname(__FILE__) . '/tag.php'); require_once(dirname(__FILE__) . '/encoding.php'); require_once(dirname(__FILE__) . '/selector.php'); class SimpleForm { var $_method; var $_action; var $_encoding; var $_default_target; var $_id; var $_buttons; var $_images; var $_widgets; var $_radios; var $_checkboxes; function SimpleForm($tag, $url) { $this->_method = $tag->getAttribute('method'); $this->_action = $this->_createAction($tag->getAttribute('action'), $url); $this->_encoding = $this->_setEncodingClass($tag); $this->_default_target = false; $this->_id = $tag->getAttribute('id'); $this->_buttons = array(); $this->_images = array(); $this->_widgets = array(); $this->_radios = array(); $this->_checkboxes = array(); } function _setEncodingClass($tag) { if (strtolower($tag->getAttribute('method')) == 'post') { if (strtolower($tag->getAttribute('enctype')) == 'multipart/form-data') { return 'SimpleMultipartEncoding'; } return 'SimplePostEncoding'; } return 'SimpleGetEncoding'; } function setDefaultTarget($frame) { $this->_default_target = $frame; } function getMethod() { return ($this->_method ? strtolower($this->_method) : 'get'); } function _createAction($action, $base) { if (is_bool($action)) { return $base; } $url = new SimpleUrl($action); return $url->makeAbsolute($base); } function getAction() { $url = $this->_action; if ($this->_default_target && ! $url->getTarget()) { $url->setTarget($this->_default_target); } return $url; } function _encode() { $class = $this->_encoding; $encoding = new $class(); for ($i = 0, $count = count($this->_widgets); $i < $count; $i++) { $this->_widgets[$i]->write($encoding); } return $encoding; } function getId() { return $this->_id; } function addWidget(&$tag) { if (strtolower($tag->getAttribute('type')) == 'submit') { $this->_buttons[] = &$tag; } elseif (strtolower($tag->getAttribute('type')) == 'image') { $this->_images[] = &$tag; } elseif ($tag->getName()) { $this->_setWidget($tag); } } function _setWidget(&$tag) { if (strtolower($tag->getAttribute('type')) == 'radio') { $this->_addRadioButton($tag); } elseif (strtolower($tag->getAttribute('type')) == 'checkbox') { $this->_addCheckbox($tag); } else { $this->_widgets[] = &$tag; } } function _addRadioButton(&$tag) { if (! isset($this->_radios[$tag->getName()])) { $this->_widgets[] = &new SimpleRadioGroup(); $this->_radios[$tag->getName()] = count($this->_widgets) - 1; } $this->_widgets[$this->_radios[$tag->getName()]]->addWidget($tag); } function _addCheckbox(&$tag) { if (! isset($this->_checkboxes[$tag->getName()])) { $this->_widgets[] = &$tag; $this->_checkboxes[$tag->getName()] = count($this->_widgets) - 1; } else { $index = $this->_checkboxes[$tag->getName()]; if (! SimpleTestCompatibility::isA($this->_widgets[$index], 'SimpleCheckboxGroup')) { $previous = &$this->_widgets[$index]; $this->_widgets[$index] = &new SimpleCheckboxGroup(); $this->_widgets[$index]->addWidget($previous); } $this->_widgets[$index]->addWidget($tag); } } function getValue($selector) { for ($i = 0, $count = count($this->_widgets); $i < $count; $i++) { if ($selector->isMatch($this->_widgets[$i])) { return $this->_widgets[$i]->getValue(); } } foreach ($this->_buttons as $button) { if ($selector->isMatch($button)) { return $button->getValue(); } } return null; } function setField($selector, $value) { $success = false; for ($i = 0, $count = count($this->_widgets); $i < $count; $i++) { if ($selector->isMatch($this->_widgets[$i])) { if ($this->_widgets[$i]->setValue($value)) { $success = true; } } } return $success; } function attachLabelBySelector($selector, $label) { for ($i = 0, $count = count($this->_widgets); $i < $count; $i++) { if ($selector->isMatch($this->_widgets[$i])) { if (method_exists($this->_widgets[$i], 'setLabel')) { $this->_widgets[$i]->setLabel($label); return; } } } } function hasSubmit($selector) { foreach ($this->_buttons as $button) { if ($selector->isMatch($button)) { return true; } } return false; } function hasImage($selector) { foreach ($this->_images as $image) { if ($selector->isMatch($image)) { return true; } } return false; } function submitButton($selector, $additional = false) { $additional = $additional ? $additional : array(); foreach ($this->_buttons as $button) { if ($selector->isMatch($button)) { $encoding = $this->_encode(); $encoding->merge($button->getSubmitValues()); if ($additional) { $encoding->merge($additional); } return $encoding; } } return false; } function submitImage($selector, $x, $y, $additional = false) { $additional = $additional ? $additional : array(); foreach ($this->_images as $image) { if ($selector->isMatch($image)) { $encoding = $this->_encode(); $encoding->merge($image->getSubmitValues($x, $y)); if ($additional) { $encoding->merge($additional); } return $encoding; } } return false; } function submit() { return $this->_encode(); } } ?>
