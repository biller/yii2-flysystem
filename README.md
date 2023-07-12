# Flysystem (3.0) Extension for Yii 2
## Notice: Only adapts AWS S3 and local filesystems.

Based on: https://github.com/creocoder/yii2-flysystem

This extension provides [Flysystem 3.0](http://flysystem.thephpleague.com/) integration for the Yii framework.
[Flysystem](http://flysystem.thephpleague.com/) is a filesystem abstraction which allows you to easily swap out a local filesystem for a remote one.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require biller/yii2-flysystem:^3.0
```

or add

```
"biller/yii2-flysystem": "^3.0"
```

to the `require` section of your `composer.json` file.

## Configuring

### Local filesystem

Configure application `components` as follows

```php
return [
    //...
    'components' => [
        //...
        'fs' => [
            'class' => 'biller\flysystem\LocalFilesystem',
            'path' => '@webroot/files',
        ],
    ],
];
```

### AWS S3 filesystem

Either run

```bash
$ composer require league/flysystem-aws-s3-V3:^3.15
```

or add

```
"league/flysystem-aws-s3-V3": "^3.15"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    //...
    'components' => [
        //...
        'awss3Fs' => [
            'class' => 'biller\flysystem\AwsS3Filesystem',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'your-region',
            // 'version' => 'latest',
            // 'baseUrl' => 'your-base-url',
            // 'prefix' => 'your-prefix',
            // 'options' => [],
            // 'endpoint' => 'http://my-custom-url'
        ],
    ],
];
```

### Global visibility settings

Configure `fsID` application component as follows

```php
return [
    //...
    'components' => [
        //...
        'fsID' => [
            //...
            'config' => [
                'visibility' => \League\Flysystem\Visibility::PRIVATE,
            ],
        ],
    ],
];
```

## Usage

### Writing or updating files

To write or update file

```php
Yii::$app->fs->write('filename.ext', 'contents');
```

To write or update file using stream contents

```php
$stream = fopen('/path/to/somefile.ext', 'r+');
Yii::$app->fs->writeStream('filename.ext', $stream);
```

### Reading files

To read file

```php
$contents = Yii::$app->fs->read('filename.ext');
```

To retrieve a read-stream

```php
$stream = Yii::$app->fs->readStream('filename.ext');
$contents = stream_get_contents($stream);
fclose($stream);
```

### Checking if a file exists

To check if a file exists

```php
$exists = Yii::$app->fs->fileExists('filename.ext');
```

### Deleting files

To delete file

```php
Yii::$app->fs->delete('filename.ext');
```

### Getting files mimetype

To get file mimetype

```php
$mimetype = Yii::$app->fs->mimeType('filename.ext');
```

### Getting files timestamp

To get file timestamp

```php
$timestamp = Yii::$app->fs->lastModified('filename.ext');
```

### Getting files size

To get file size

```php
$timestamp = Yii::$app->fs->fileSize('filename.ext');
```

### Creating directories

To create directory

```php
Yii::$app->fs->createDirectory('path/to/directory');
```

Directories are also made implicitly when writing to a deeper path

```php
Yii::$app->fs->write('path/to/filename.ext');
```

### Deleting directories

To delete directory

```php
Yii::$app->fs->deleteDirectory('path/to/filename.ext');
```

### Managing visibility

Visibility is the abstraction of file permissions across multiple platforms. Visibility can be either public or private.

```php
use League\Flysystem\AdapterInterface;

Yii::$app->fs->write('filename.ext', 'contents', [
    'visibility' => \League\Flysystem\Visibility.PRIVATE
]);
```

You can also change and check visibility of existing files

```php
use League\Flysystem\AdapterInterface;

if (Yii::$app->fs->visibility('filename.ext') === \League\Flysystem\Visibility::PRIVATE) {
    Yii::$app->fs->setVisibility('filename.ext', \League\Flysystem\Visibility.PUBLIC);
}
```

### Listing contents

To list contents

```php
$contents = Yii::$app->fs->listContents();

foreach ($contents as $object) {
    echo $object['basename']
        . ' is located at' . $object['path']
        . ' and is a ' . $object['type'];
}
```

By default Flysystem lists the top directory non-recursively. You can supply a directory name and recursive boolean to get more precise results

```php
$contents = Yii::$app->fs->listContents('path/to/directory', true);
```
