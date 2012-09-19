<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Adding a database class for testing.
// The code will never be executed thanks to the mock objects.
class TestMysql
{
	var $link;

	function TestMysql()
	{
		if(!$this->link) $this->link = mysql_connect('testhost', 'user', 'password');
	}

	function Query($str)
	{
		$result = mysql_query($str, $this->link);
		while($row = mysql_fetch_array($result))
			$output[] = $row;

		return $output;
	}
}

// Another simple class that will be partially mocked,
// see http://simpletest.org/en/partial_mocks_documentation.html
class User
{
	function User()
	{
		// A constructor is required for a class that is partially mocked.
	}

	function DB()
	{
		return new TestMysql;
	}

	function Name($id)
	{
		$db = $this->DB();
		$result = $db->Query("SELECT name FROM user WHERE id=$id");

		return $result['name'];
	}
}

// Generate a mock mysql object, basically every function defined but no content.
// With this line, a class called 'MockTestMysql' will be created.
// So now we have a database emulator.
Mock::generate('TestMysql');

// Generate a partial mock of the User object called 'UserMock', so we can override only the specified functions.
// It is needed because we call TestMysql from the User::DB() function, so it should return our mock database.
Mock::generatePartial('User', 'UserMock', array('DB'));

class AdvancedExampleTest extends UnitTestCase
{
	// A database mock object
	var $db;

	function AdvancedExampleTest()
	{
		parent::__construct('Example tests');
	}

	// Always called before every test function
	function setUp()
	{
		// Create the mock database.
		// Use "$this" as constructor argument to all mock objects
		$this->db = &new MockTestMysql($this);
	}

	// Always called after every test function
	function tearDown()
	{
		unset($this->db);
	}

	///// All functions starting with "test" will be tested /////

	function testMockActor()
	{
		// The mock is now an actor and will return certain values.

		// Database will now return 'OK' on first, and 'NO' on every other query.
		$this->db->setReturnValue('Query', 'NO');
		$this->db->setReturnValueAt(0, 'Query', 'OK');

		$output = $this->db->Query('SELECT * FROM table');
		$this->assertEqual($output, 'OK');

		$output = $this->db->Query('SELECT * FROM table');
		$this->assertEqual($output, 'NO');
	}

	function testMockCritic()
	{
		// The mock is now a critic and expects certain arguments.

		/*
		$this->db->expectArguments('Query', array('SELECT * FROM user'));
		$this->db->expectCallCount('Query', 1);

		$this->db->Query('SELECT * FROM user');

		// Need tally() for expectCallCount();
		$this->db->tally();
		 */
	}

	function testPartialMock()
	{
		// Now we make a partial user mock and returns the db on a call to DB.

		$user = new UserMock($this);
		$user->setReturnReference('DB', $this->db);
		$user->User(); // IMPORTANT: Need to call constructor manually in partial mocks!

		$this->db->setReturnValue('Query', array('name' => 'Boris'), array('SELECT name FROM user WHERE id=1'));

		// Thanks to the above preparations, we can now test the user name function this easy:
        // (Preparations should go into setUp() in a real testing class)
		$name = $user->Name(1);

		// A regexp test just for fun
		$this->assertPattern('/^Boris$/', $name);
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
