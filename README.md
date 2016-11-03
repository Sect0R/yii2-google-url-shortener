yii2-google-url-shortener
==============

Simple Yii2 component to short URL or expand URL using google API Url Shortener.

Installation
-----
- create folder components in your yii2
- move GoogleShortUrl.php into components folder
- add component class to config/main.php configuration file
```
....
'components' => [
        'google' => [
            'class' => 'app\components\GoogleShortUrl'
        ],
        .......
];
....

```
Usage
------------
```
Short Url:
return Yii::$app->google->shortUrl('http://google.com');

Expand shorten Url:
return Yii::$app->google->expandUrl('https://goo.gl/FuAUYl');

```
Other Information
-----------------
- Documentation for Google URL Shortener API: https://developers.google.com/url-shortener/
- Register Api Key: https://console.developers.google.com/apis/
