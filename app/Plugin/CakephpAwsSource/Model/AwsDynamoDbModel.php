<?php
App::uses('AwsModel', 'AwsDatasource.Model');

class AwsDynamoDbModel extends AwsModel {
  public $useDbConfig = 'aws_dynamodb';

  public function __call($method, $args) {
    switch (strtolower($method)) {
      case 'putitem':
      case 'createtable':
      case 'waituntil':
      case 'deleteitem':
      case 'deletetable':
      case 'describetable';
      case 'getitem';
      case 'query';
      case 'scan';
      case 'updateitem';
      case 'updatetable';
      case 'waituntiltableexists':
      case 'waituntiltablenotexists';
        $table_name = array(
          'TableName' => $this->getDataSource()->getTable(),
        );
        if (isset($args[0])) {
          $args[0] += $table_name;
        } else {
          $args[0] = $table_name;
        }
      break;
    }
    return call_user_func_array(array($this->getClient(), $method), $args);
  }

  public function query($sql) {
    return $this->__call('query', func_get_args());
  }

  public function getClient() {
    return $this->getDataSource()->getClient();
  }

  public function getTable() {
    return $this->getDataSource()->getTable();
  }
}
