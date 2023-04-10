# Yii2 的扩展包

    Yii2的gii扩展包,自动生成业务 model，以及自动生成 api 接口

# 一.使用composer安装（推荐）

## 1.安装
    
        composer require jsyqw/business-gii
        
## 2.配置

在 Yii 的配置文件中，添加如下配置

```php
    ...
    $config['modules']['gii'] = [
            'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            'allowedIPs' => ['127.0.0.1', '::1'],
            'generators' => [
                'businessModel' => [ //生成器名称
                    'class' => 'Jsyqw\BusinessGii\model\Generator',
                ],
                'businessApi' => [ //生成器名称
                    'class' => 'Jsyqw\BusinessGii\api\Generator',
                ]
            ],
        ]
    ...
```    

## 3.使用

    访问地址  http://{url}/gii

## 4.示例

![GII页面](https://github.com/jasonyqwang/business-gii/blob/master/docs/gii-page.png)

![Model模型页面](https://github.com/jasonyqwang/business-gii/blob/master/docs/gii-model.png)

![Controller控制器页面](https://github.com/jasonyqwang/business-gii/blob/master/docs/gii-page.png)

# 二.基于git引用该项目 

## 1.初始化 composer 配置

```json

{
    "name": "jsyqw/business-gii",
    "description": "基于Yii2框架，通过gii生成Model,以及生成统一的API格式",
    "type": "yii2-extension",
    "authors": [
        {
            "name": "Jason Wang",
            "email": "jasonwang1211@gmail.com"
        }
    ],
    "require": {},
    "autoload": {
        "psr-4": {
            "Jsyqw\\BusinessGii\\": "src/"
        }
    }
}


```

## 2.配置最外层的composer.json
   
```json
    {
       "autoload": {
              "psr-4": {
                  "Jsyqw\\BusinessGii\\": "vendor/business-gii/src/"
              }
       }
    }
```

## 3.运行：composer dumpautoload

```bash
> composer dumpautoload
> Generated autoload files containing 543 classes
```

对应的 autoload_psr4.php
会多一条信息
eg：
```php
    [
        'Jsyqw\\BusinessGii\\' => array($vendorDir . '/business-gii/src'),
    ],
```