# AlchemyPay SDK for PHP

[![PHP Version Require](http://poser.pugx.org/hedeqiang/alchemypay-sdk-php/require/php)](https://packagist.org/packages/hedeqiang/alchemypay-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/hedeqiang/alchemypay-sdk-php/v)](//packagist.org/packages/hedeqiang/alchemypay-sdk-php)
[![Total Downloads](https://poser.pugx.org/hedeqiang/alchemypay-sdk-php/downloads)](//packagist.org/packages/hedeqiang/alchemypay-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/hedeqiang/alchemypay-sdk-php/v/unstable)](//packagist.org/packages/hedeqiang/alchemypay-sdk-php)
[![License](https://poser.pugx.org/hedeqiang/alchemypay-sdk-php/license)](//packagist.org/packages/hedeqiang/alchemypay-sdk-php)
[![Tests](https://github.com/hedeqiang/alchemypay-sdk-php/actions/workflows/test.yml/badge.svg)](https://github.com/hedeqiang/payease/actions/workflows/test.yml)

## 环境需求

- PHP >= 7.1
- [Composer](https://getcomposer.org/) >= 2.0

支持主流框架 `Laravel`、`Hyperf`、`Yii2` 快捷使用，具体使用方法请滑到底部

## Installing

```shell
$ composer require hedeqiang/alchemypay -vvv
```

## Usage

```shell
require __DIR__ .'/vendor/autoload.php';
use Hedeqiang\AlchemyPay\Pay;
$app = new Pay([
    'privateKey' => '/parth/client.pfx',
    'publicKey'  => 'path/test.cer',  // 注意： 此公钥为首信易的公钥、并非上传到商户后台的公钥！！！
    'merchantId' => '890000593',
    'password'   => '123456',
]);
```

### 创建订单

```shell
$uri = 'openApi/createOrder';

$params = [
    "amount"           => "10.0",
    "fiatType"         => "CNY",
    "callbackUrl"      => "http://147.243.170.11:9091/transnotify",
    "merchantOrderNum" => "testqwe1234567891035",
    "payMent"          => "w1",
    "email"            => "123456@qq.com",
];

$response = $app->request($uri,$params);
```

### 回调通知

```shell
$result = $app->handleNotify();
// TODO

return 'SUCCESS' ; // retuen 'Fail';
```

## 在 Laravel 中使用

#### 发布配置文件

```php
php artisan vendor:publish --tag=alchemy
or 
php artisan vendor:publish --provider="Hedeqiang\AlchemyPay\ServiceProvider"
```

##### 编写 .env 文件

```
ALCHEMY_PAY_ENDPOINT=
ALCHEMY_PAY_MERCHANT_CODE=
ALCHEMY_PAY_PRIVATE_KEY=
```

### 使用

#### 服务名访问

```php
public function index()
{
    return app('pay')->request($uri,$params);
}
```

#### Facades 门面使用(可以提示)

```php
use Hedeqiang\AlchemyPay\Facades\Pay;

public function index()
{
   return Pay::pay()->request($uri,$params)
}

public function notify(Request $request)
{
   $result = Pay::pay()->handleNotify();
}
```

## 在 Hyperf 中使用

#### 发布配置文件

```php
php bin/hyperf.php vendor:publish hedeqiang/alchemypay
```

##### 编写 .env 文件

```
ALCHEMY_PAY_ENDPOINT=
ALCHEMY_PAY_MERCHANT_CODE=
ALCHEMY_PAY_PRIVATE_KEY=
```

#### 使用

```shell
<?php

use Hedeqiang\AlchemyPay\Pay;
use Hyperf\Utils\ApplicationContext;

// 请求
response = ApplicationContext::getContainer()->get(Pay::class)->request($uri,$parmas);

// 回调
$response = ApplicationContext::getContainer()->get(Pay::class)->handleNotify();
```

## 在 Yii2 中使用

#### 配置

在 `Yii2` 配置文件 `config/main.php` 的 `components` 中添加:

```php
'components' => [
    // ...
    'pay' => [
        'class' => 'Hedeqiang\AlchemyPay\YiiPay',
        'options' => [
            'endpoint'     => 'xxx',
            'merchantCode' => 'xxx',
            'privateKey' => 'xxx',
        ],
    ],
    // ...
]
```

#### 使用

```php

Yii::$app->response->format = Response::FORMAT_JSON;

// 请求
$results = Yii::$app->pay->getPay()->request($uri,$params);
// 回调
$results = Yii::$app->pay->getPay()->handleNotify();
```

## Project supported by JetBrains

Many thanks to Jetbrains for kindly providing a license for me to work on this and other open-source projects.

[![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://www.jetbrains.com/?from=https://github.com/hedeqiang)

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/hedeqiang/alchemypay-sdk-php/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/hedeqiang/alchemypay-sdk-php/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and
PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
