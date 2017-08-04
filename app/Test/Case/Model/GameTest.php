<?php
/* Game Test cases generated on: 2012-09-13 15:35:34 : 1347550534*/
App::uses('Game', 'Model');

/**
 * Game Test Case
 *
 */
class GameTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.game');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Game = ClassRegistry::init('Game');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Game);

		parent::tearDown();
	}

}
