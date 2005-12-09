<?php
 require_once(dirname(__FILE__) . '/../unit_tester.php'); require_once(dirname(__FILE__) . '/../expectation.php'); class TestCase extends SimpleTestCase { function TestCase($label) { $this->SimpleTestCase($label); } function assert($condition, $message = false) { parent::assertTrue($condition, $message); } function assertEquals($first, $second, $message = false) { parent::assert(new EqualExpectation($first), $second, $message); } function assertEqualsMultilineStrings($first, $second, $message = false) { parent::assert(new EqualExpectation($first), $second, $message); } function assertRegexp($pattern, $subject, $message = false) { parent::assert(new PatternExpectation($pattern), $subject, $message); } function error($message) { parent::fail("Error triggered [$message]"); } function name() { return $this->getLabel(); } } ?>