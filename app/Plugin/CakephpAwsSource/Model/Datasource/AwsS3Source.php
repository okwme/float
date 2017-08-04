<?php
App::uses('AwsSource', 'AwsDatasource.Model/Datasource');

use Aws\Common\Aws;
use Aws\S3\S3Client;

class AwsS3Source extends AwsSource {

  protected $_connection = null;

  public function listSources($data = null) {
  }

  public function getBucket() {
    return $this->config['bucket'];
  }

  public function getS3Client() {
    return S3Client::factory($this->config);
  }
}

