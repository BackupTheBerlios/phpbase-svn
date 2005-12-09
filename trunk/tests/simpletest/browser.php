<?php
 require_once(dirname(__FILE__) . '/simpletest.php'); require_once(dirname(__FILE__) . '/http.php'); require_once(dirname(__FILE__) . '/encoding.php'); require_once(dirname(__FILE__) . '/page.php'); require_once(dirname(__FILE__) . '/selector.php'); require_once(dirname(__FILE__) . '/frames.php'); require_once(dirname(__FILE__) . '/user_agent.php'); if (!defined('DEFAULT_MAX_NESTED_FRAMES')) { define('DEFAULT_MAX_NESTED_FRAMES', 3); } class SimpleBrowserHistory { var $_sequence; var $_position; function SimpleBrowserHistory() { $this->_sequence = array(); $this->_position = -1; } function _isEmpty() { return ($this->_position == -1); } function _atBeginning() { return ($this->_position == 0) && ! $this->_isEmpty(); } function _atEnd() { return ($this->_position + 1 >= count($this->_sequence)) && ! $this->_isEmpty(); } function recordEntry($url, $parameters) { $this->_dropFuture(); array_push( $this->_sequence, array('url' => $url, 'parameters' => $parameters)); $this->_position++; } function getUrl() { if ($this->_isEmpty()) { return false; } return $this->_sequence[$this->_position]['url']; } function getParameters() { if ($this->_isEmpty()) { return false; } return $this->_sequence[$this->_position]['parameters']; } function back() { if ($this->_isEmpty() || $this->_atBeginning()) { return false; } $this->_position--; return true; } function forward() { if ($this->_isEmpty() || $this->_atEnd()) { return false; } $this->_position++; return true; } function _dropFuture() { if ($this->_isEmpty()) { return; } while (! $this->_atEnd()) { array_pop($this->_sequence); } } } class SimpleBrowser { var $_user_agent; var $_page; var $_history; var $_ignore_frames; var $_maximum_nested_frames; function SimpleBrowser() { $this->_user_agent = &$this->_createUserAgent(); $this->_user_agent->useProxy( SimpleTest::getDefaultProxy(), SimpleTest::getDefaultProxyUsername(), SimpleTest::getDefaultProxyPassword()); $this->_page = &new SimplePage(); $this->_history = &$this->_createHistory(); $this->_ignore_frames = false; $this->_maximum_nested_frames = DEFAULT_MAX_NESTED_FRAMES; } function &_createUserAgent() { $user_agent = &new SimpleUserAgent(); return $user_agent; } function &_createHistory() { $history = &new SimpleBrowserHistory(); return $history; } function ignoreFrames() { $this->_ignore_frames = true; } function useFrames() { $this->_ignore_frames = false; } function &_parse($response, $depth = 0) { $builder = &new SimplePageBuilder(); $page = &$builder->parse($response); if ($this->_ignore_frames || ! $page->hasFrames() || ($depth > $this->_maximum_nested_frames)) { return $page; } $frameset = &new SimpleFrameset($page); foreach ($page->getFrameset() as $key => $url) { $frame = &$this->_fetch($url, new SimpleGetEncoding(), $depth + 1); $frameset->addFrame($frame, $key); } return $frameset; } function &_fetch($url, $encoding, $depth = 0) { $response = &$this->_user_agent->fetchResponse($url, $encoding); if ($response->isError()) { $page = &new SimplePage($response); } else { $page = &$this->_parse($response, $depth); } return $page; } function _load($url, $parameters) { $frame = $url->getTarget(); if (! $frame || ! $this->_page->hasFrames() || (strtolower($frame) == '_top')) { return $this->_loadPage($url, $parameters); } return $this->_loadFrame(array($frame), $url, $parameters); } function _loadPage($url, $parameters) { $this->_page = &$this->_fetch($url, $parameters); $this->_history->recordEntry( $this->_page->getUrl(), $this->_page->getRequestData()); return $this->_page->getRaw(); } function _loadFrame($frames, $url, $parameters) { $page = &$this->_fetch($url, $parameters); $this->_page->setFrame($frames, $page); } function restart($date = false) { $this->_user_agent->restart($date); } function addHeader($header) { $this->_user_agent->addHeader($header); } function ageCookies($interval) { $this->_user_agent->ageCookies($interval); } function setCookie($name, $value, $host = false, $path = '/', $expiry = false) { $this->_user_agent->setCookie($name, $value, $host, $path, $expiry); } function getCookieValue($host, $path, $name) { return $this->_user_agent->getCookieValue($host, $path, $name); } function getCurrentCookieValue($name) { return $this->_user_agent->getBaseCookieValue($name, $this->_page->getUrl()); } function setMaximumRedirects($max) { $this->_user_agent->setMaximumRedirects($max); } function setMaximumNestedFrames($max) { $this->_maximum_nested_frames = $max; } function setConnectionTimeout($timeout) { $this->_user_agent->setConnectionTimeout($timeout); } function useProxy($proxy, $username = false, $password = false) { $this->_user_agent->useProxy($proxy, $username, $password); } function head($url, $parameters = false) { if (! is_object($url)) { $url = new SimpleUrl($url); } if ($this->getUrl()) { $url = $url->makeAbsolute($this->getUrl()); } $response = &$this->_user_agent->fetchResponse($url, new SimpleHeadEncoding($parameters)); return ! $response->isError(); } function get($url, $parameters = false) { if (! is_object($url)) { $url = new SimpleUrl($url); } if ($this->getUrl()) { $url = $url->makeAbsolute($this->getUrl()); } return $this->_load($url, new SimpleGetEncoding($parameters)); } function post($url, $parameters = false) { if (! is_object($url)) { $url = new SimpleUrl($url); } if ($this->getUrl()) { $url = $url->makeAbsolute($this->getUrl()); } return $this->_load($url, new SimplePostEncoding($parameters)); } function retry() { $frames = $this->_page->getFrameFocus(); if (count($frames) > 0) { $this->_loadFrame( $frames, $this->_page->getUrl(), $this->_page->getRequestData()); return $this->_page->getRaw(); } if ($url = $this->_history->getUrl()) { $this->_page = &$this->_fetch($url, $this->_history->getParameters()); return $this->_page->getRaw(); } return false; } function back() { if (! $this->_history->back()) { return false; } $content = $this->retry(); if (! $content) { $this->_history->forward(); } return $content; } function forward() { if (! $this->_history->forward()) { return false; } $content = $this->retry(); if (! $content) { $this->_history->back(); } return $content; } function authenticate($username, $password) { if (! $this->_page->getRealm()) { return false; } $url = $this->_page->getUrl(); if (! $url) { return false; } $this->_user_agent->setIdentity( $url->getHost(), $this->_page->getRealm(), $username, $password); return $this->retry(); } function getFrames() { return $this->_page->getFrames(); } function getFrameFocus() { return $this->_page->getFrameFocus(); } function setFrameFocusByIndex($choice) { return $this->_page->setFrameFocusByIndex($choice); } function setFrameFocus($name) { return $this->_page->setFrameFocus($name); } function clearFrameFocus() { return $this->_page->clearFrameFocus(); } function getTransportError() { return $this->_page->getTransportError(); } function getMimeType() { return $this->_page->getMimeType(); } function getResponseCode() { return $this->_page->getResponseCode(); } function getAuthentication() { return $this->_page->getAuthentication(); } function getRealm() { return $this->_page->getRealm(); } function getUrl() { $url = $this->_page->getUrl(); return $url ? $url->asString() : false; } function getRequest() { return $this->_page->getRequest(); } function getHeaders() { return $this->_page->getHeaders(); } function getContent() { return $this->_page->getRaw(); } function getContentAsText() { return $this->_page->getText(); } function getTitle() { return $this->_page->getTitle(); } function getAbsoluteUrls() { return $this->_page->getAbsoluteUrls(); } function getRelativeUrls() { return $this->_page->getRelativeUrls(); } function setField($label, $value) { return $this->_page->setField(new SimpleByLabelOrName($label), $value); } function setFieldByName($name, $value) { return $this->_page->setField(new SimpleByName($name), $value); } function setFieldById($id, $value) { return $this->_page->setField(new SimpleById($id), $value); } function getField($label) { return $this->_page->getField(new SimpleByLabelOrName($label)); } function getFieldByName($name) { return $this->_page->getField(new SimpleByName($name)); } function getFieldById($id) { return $this->_page->getField(new SimpleById($id)); } function clickSubmit($label = 'Submit', $additional = false) { if (! ($form = &$this->_page->getFormBySubmit(new SimpleByLabel($label)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitButton(new SimpleByLabel($label), $additional)); return ($success ? $this->getContent() : $success); } function clickSubmitByName($name, $additional = false) { if (! ($form = &$this->_page->getFormBySubmit(new SimpleByName($name)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitButton(new SimpleByName($name), $additional)); return ($success ? $this->getContent() : $success); } function clickSubmitById($id, $additional = false) { if (! ($form = &$this->_page->getFormBySubmit(new SimpleById($id)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitButton(new SimpleById($id), $additional)); return ($success ? $this->getContent() : $success); } function clickImage($label, $x = 1, $y = 1, $additional = false) { if (! ($form = &$this->_page->getFormByImage(new SimpleByLabel($label)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitImage(new SimpleByLabel($label), $x, $y, $additional)); return ($success ? $this->getContent() : $success); } function clickImageByName($name, $x = 1, $y = 1, $additional = false) { if (! ($form = &$this->_page->getFormByImage(new SimpleByName($name)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitImage(new SimpleByName($name), $x, $y, $additional)); return ($success ? $this->getContent() : $success); } function clickImageById($id, $x = 1, $y = 1, $additional = false) { if (! ($form = &$this->_page->getFormByImage(new SimpleById($id)))) { return false; } $success = $this->_load( $form->getAction(), $form->submitImage(new SimpleById($id), $x, $y, $additional)); return ($success ? $this->getContent() : $success); } function submitFormById($id) { if (! ($form = &$this->_page->getFormById($id))) { return false; } $success = $this->_load( $form->getAction(), $form->submit()); return ($success ? $this->getContent() : $success); } function clickLink($label, $index = 0) { $urls = $this->_page->getUrlsByLabel($label); if (count($urls) == 0) { return false; } if (count($urls) < $index + 1) { return false; } $this->_load($urls[$index], new SimpleGetEncoding()); return $this->getContent(); } function isLink($label) { return (count($this->_page->getUrlsByLabel($label)) > 0); } function clickLinkById($id) { if (! ($url = $this->_page->getUrlById($id))) { return false; } $this->_load($url, new SimpleGetEncoding()); return $this->getContent(); } function isLinkById($id) { return (boolean)$this->_page->getUrlById($id); } function click($label) { $raw = $this->clickSubmit($label); if (! $raw) { $raw = $this->clickLink($label); } if (! $raw) { $raw = $this->clickImage($label); } return $raw; } } ?>