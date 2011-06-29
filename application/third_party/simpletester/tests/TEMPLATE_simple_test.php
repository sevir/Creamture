<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExampleTest extends UnitTestCase
{
	function ExampleTest()
	{
		parent::__construct('Example tests');
	}

	function setUp()
	{
	}

	function tearDown()
	{
	}

	///// All functions starting with "test" will be tested /////

	function testSimpleStuff()
	{
		$name = 'Andreas';
		$online = TRUE;

		$this->assertEqual($name, 'Andreas');
		$this->assertTrue($online);
	}
}

// Full documentation at http://simpletest.org/en/overview.html

/*
assertTrue($x)                    // Fail if $x is false
assertFalse($x)                   // Fail if $x is true
assertNull($x)                    // Fail if $x is set
assertNotNull($x)                 // Fail if $x not set
assertIsA($x, $t)                 // Fail if $x is not the class or type $t
assertNotA($x, $t)                // Fail if $x is of the class or type $t
assertEqual($x, $y)               // Fail if $x == $y is false
assertNotEqual($x, $y)            // Fail if $x == $y is true
assertWithinMargin($x, $y, $m)    // Fail if abs($x - $y) < $m is false
assertOutsideMargin($x, $y, $m)   // Fail if abs($x - $y) < $m is true
assertIdentical($x, $y)           // Fail if $x == $y is false or a type mismatch
assertNotIdentical($x, $y)        // Fail if $x == $y is true and types match
assertReference($x, $y)           // Fail unless $x and $y are the same variable
assertClone($x, $y)               // Fail unless $x and $y are identical copies
assertPattern($p, $x)             // Fail unless the regex $p matches $x
assertNoPattern($p, $x)           // Fail if the regex $p matches $x
expectError($x)                   // Swallows any upcoming matching error
assert($e)                        // Fail on failed expectation object $e
*/

/*
setReturnValue($method, $returns, $expectedArgs)
setReturnValueAt($callOrder, $method, $returns, $expectedArgs)
setReturnReference($method, $returns, $expectedArgs)
setReturnReferenceAt($callOrder, $method, $returns, $expectedArgs)
*/

/*
Expectation                              Needs tally()

expect($method, $args)                   No
expectAt($timing, $method, $args)        No
expectCallCount($method, $count)         Yes
expectMaximumCallCount($method, $count)  No
expectMinimumCallCount($method, $count)  Yes
expectNever($method)                     No
expectOnce($method, $args)               Yes
expectAtLeastOnce($method, $args)        Yes
*/
