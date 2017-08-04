<?php
App::uses('AwsSource', 'AwsDatasource.Model/Datasource');

use Aws\Common\Aws;
use Aws\Sqs\SqsClient;

class AwsSqsSource extends AwsSource {

  protected $_connection = null;

  public function listSources($data = null) {
  }

  public function connect() {
    $this->_connection = SqsClient::factory($this->config);
    $this->connected = true;
  }
}
