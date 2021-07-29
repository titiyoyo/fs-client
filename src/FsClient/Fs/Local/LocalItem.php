<?php

namespace Tertere\FsClient\Fs\Local;

use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Exception\FsClientConfigException;
use Tertere\FsClient\Fs\AbstractItem;
use Tertere\FsClient\Fs\ItemInterface;

/**
 * Created by JetBrains PhpStorm.
 * User: terencepires
 * Date: 17/02/12
 * Time: 07:12
 * To change this template use File | Settings | File Templates.
 */
class LocalItem extends AbstractItem implements ItemInterface
{
    protected Filesystem $oFs;
    protected $pathinfo;

    use LocalTrait;

    public function __construct($path)
    {
        $this->oFs = new Filesystem();

        if (!$this->oFs->exists($path)) {
            throw new FsClientConfigException(__METHOD__ . " - path " . $path . " is invalid");
        }

        $this->path = $path;
        $this->setProperties();
    }

    public function getParent(): LocalDirectory
    {
        return $this->list(dirname($this->path));
    }

    public function delete(): bool
    {
        try  {
            $this->oFs->remove($this->path);
        } catch(\Exception $ex) {
            return false;
        }
        return true;
    }
}
