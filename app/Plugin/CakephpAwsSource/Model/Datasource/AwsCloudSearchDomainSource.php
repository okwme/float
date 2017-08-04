<?php
App::uses('AwsSource', 'AwsDatasource.Model/Datasource');

use Aws\Common\Aws;
use Aws\CloudSearchDomain\CloudSearchDomainClient;

class AwsCloudSearchDomainSource extends AwsSource {

  protected $_connection = null;

  public function listSources($data = null) {
  }

  public function connect() {
    $this->_connection = CloudSearchDomainClient::factory($this->config);
    $this->connected = true;
  }
}

