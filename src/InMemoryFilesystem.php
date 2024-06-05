<?php

namespace creocoder\flysystem;

use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use Yii;
use yii\base\InvalidConfigException;

class InMemoryFilesystem extends Filesystem
{
    public string $path;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }

        $this->path = Yii::getAlias($this->path);

        parent::init();
    }

    protected function prepareAdapter(): InMemoryFilesystemAdapter
    {
        return new InMemoryFilesystemAdapter($this->path);
    }
}
