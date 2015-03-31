<?php

/**
 * @group System
 */
class PHPTest extends CIUnit_TestCase {

    function setUp() {
        // Setup
    }

    public function testPhpVersion() {
        $this->assertTrue(phpversion() >= 5.3);
    }

}
