<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace creocoder\flysystem;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use yii\base\InvalidConfigException;

/**
 * AwsS3Filesystem
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class AwsS3Filesystem extends Filesystem
{
    /**
     * @var string
     */
    public $key = '';
    /**
     * @var string
     */
    public $secret = '';
    /**
     * @var string
     */
    public $region = '';
    /**
     * @var string
     */
    public $baseUrl = '';
    /**
     * @var string
     */
    public $version = '';
    /**
     * @var string
     */
    public $bucket = '';
    /**
     * @var string
     */
    public $prefix = '';
    /**
     * @var bool
     */
    public $pathStyleEndpoint = false;
    /**
     * @var array
     */
    public $options = [];
    /**
     * @var bool
     */
    public $streamReads = false;
    /**
     * @var string
     */
    public $endpoint = '';
    /**
     * @var array|\Aws\CacheInterface|\Aws\Credentials\CredentialsInterface|bool|callable
     */
    public $credentials;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->credentials)) {
            if (empty($this->key)) {
                throw new InvalidConfigException('The "key" property must be set.');
            }

            if (empty($this->secret)) {
                throw new InvalidConfigException('The "secret" property must be set.');
            }
        }

        if (empty($this->bucket)) {
            throw new InvalidConfigException('The "bucket" property must be set.');
        }

        parent::init();
    }

    /**
     * @return AwsS3V3Adapter
     */
    protected function prepareAdapter()
    {
        $config = [];

        if (empty($this->credentials)) {
            $config['credentials'] = ['key' => $this->key, 'secret' => $this->secret];
        } else {
            $config['credentials'] = $this->credentials;
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
