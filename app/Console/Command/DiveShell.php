<?class DiveShell extends AppShell {
    public function main() {
    	App::uses('CakeRequest', 'Network');
   		App::uses('CakeResponse', 'Network');
	    App::uses('Controller', 'Controller');
	    App::uses('MovesController', 'Controller');
	    $controller = new MovesController(new CakeRequest(), new CakeResponse());

	    $count = $controller->playGame();
        $this->out($count);
    }
}?>