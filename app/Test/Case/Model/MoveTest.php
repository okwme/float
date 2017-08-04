<?php
/* Move Test cases generated on: 2012-09-26 14:16:25 : 1348668985*/
App::uses('Move', 'Model');

/**
 * Move Test Case
 *
 */
class MoveTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.move');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Move = ClassRegistry::init('Move');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Move);

		parent::tearDown();
	}

}
