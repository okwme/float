<?php
App::uses('AwsSource', 'AwsDatasource.Model/Datasource');

use Aws\Common\Aws;
use Aws\DynamoDb\DynamoDbClient;

class AwsDynamoDbSource extends AwsSource {

  protected $_connection = null;

  public function listSources($data = null) {
  }

  public function getTable() {
    return $this->config['table'];
  }

  public function getClient() {
    return DynamoDbClient::factory($this->config);
  }
}


