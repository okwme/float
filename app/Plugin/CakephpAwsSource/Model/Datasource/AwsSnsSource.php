<?php
App::uses('AwsSource', 'AwsDatasource.Model/Datasource');

use Aws\Common\Aws;
use Aws\Sns\SnsClient;

class AwsSnsSource extends AwsSource {

  protected $_connection = null;

  public function listSources($data = null) {
  }

  public function connect() {
    $this->_connection = SnsClient::factory($this->config);
    $this->connected = true;
  }
}
