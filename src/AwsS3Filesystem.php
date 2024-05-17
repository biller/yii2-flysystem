<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace creocoder\flysystem;

use Aws\CacheInterface;
use Aws\Credentials\CredentialsInterface;
use Aws\S3\S3Client;
use Closure;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use yii\base\InvalidConfigException;

/**
 * AwsS3Filesystem
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class AwsS3Filesystem extends Filesystem
{
    public ?string $key;
    public ?string $secret;
    public string $region = '';
    public string $baseUrl = '';
    public string $version = '';
    public string $bucket = '';
    public string $prefix = '';
    public bool $pathStyleEndpoint = false;
    public array $options = [];
    public bool $streamReads = false;
    public string $endpoint = '';
    public array|CacheInterface|CredentialsInterface|bool|Closure $credentials;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->bucket)) {
            throw new InvalidConfigException('The "bucket" property must be set.');
        }

        parent::init();
    }

    protected function prepareAdapter(): AwsS3V3Adapter
    {
        $config = [];

        if ($this->credentials) {
            $config['credentials'] = $this->credentials;
        } elseif ($this->key && $this->secret) {
            $config['credentials'] = ['key' => $this->key, 'secret' => $this->secret];
        }

        if ($this->pathStyleEndpoint === true) {
            $config['use_path_style_endpoint'] = true;
        }

        if (!empty($this->region)) {
            $config['region'] = $this->region;
        }

        if (!empty($this->baseUrl)) {
            $config['base_url'] = $this->baseUrl;
        }

        if (!empty($this->endpoint)) {
            $config['endpoint'] = $this->endpoint;
        }

        $config['version'] = (($this->version !== null) ? $this->version : 'latest');

        $client = new S3Client($config);

        return new AwsS3V3Adapter($client, $this->bucket, $this->prefix, null, null, $this->options, $this->streamReads);
    }
}
