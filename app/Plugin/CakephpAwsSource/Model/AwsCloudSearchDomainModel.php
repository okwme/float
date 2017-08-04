<?php
App::uses('AwsModel', 'AwsDatasource.Model');

class AwsCloudSearchDomainModel extends AwsModel {
  public $useDbConfig = 'aws_cloudsearch';

  public function __call($method, $args) {
    return call_user_func_array(array($this->getDataSource()->getConnection(), $method), $args);
  }

  public function getClient() {
    return $this->getDataSource()->getConnection();
  }
}
