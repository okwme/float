cakephp-aws-datasource
======================

Aws DataSource Plugin for CakePHP

## Requirements
- PHP5
- CakePHP2
- [AWS SDK for PHP 2](http://aws.amazon.com/jp/sdkforphp/)


## Installation

Ensure require is present in composer.json. This will install the plugin into Plugin/CakephpAwsDatasource:

```json
{
  "require": {
    "nanapi/cakephp-aws-datasource": "v1.0.0"
  }
}
```


app/Config/bootstrap.php
```
CakePlugin::load('CakephpAwsSource');
```

app/Config/database.php
```php
<?php

class DATABASE_CONFIG {

  public $aws = array(
    'datasource' => 'CakephpAwsSource.AwsSource',
    'key' => 'AWS_ACCESS_KEY_HERE',
    'secret' => 'AWS_SECRET_HERE',
  );

```


## How to use it

your model
```php
<?php
App::uses('AwdModel', 'CakephpAwsSource.Model');

class MyAws extends AwsModel {
}

```

your controller
```php
<?php
App::uses('AppController', 'Controller');

class MyController extends AppController {
  public $uses = array(
    'MyAws';
  );

  public function index() {
    $s3 = $this->MyAws->get('s3');
    $s3->putObject(array(...));
  }
}

```


## Methods
This is a wrapper for [Guzzle\Service\Builder\ServiceBuilderInterface][]. For a list of methods that can be used by the Model, please refer to the Aws SDK for PHP.


[AWS SDK for PHP 2]: http://aws.amazon.com/jp/sdkforphp/ "AWS SDK for PHP 2"
[Guzzle\Service\Builder\ServiceBuilderInterface]: http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Guzzle.Service.Builder.ServiceBuilderInterface.html "Guzzle\Service\Builder\ServiceBuilderInterface"
