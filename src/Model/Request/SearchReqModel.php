<?php

namespace App\Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @codeCoverageIgnore
 */
class SearchReqModel extends BaseReqModel
{
    /**
     * @Assert\Type("App\Model\Request\StorageModel")
     *
     * @var StorageModel
     */
    protected $storage;

    /**
     * @Assert\Type("App\Model\Request\RamCollectionModel")
     *
     * @var RamCollectionModel
     */
    protected $ram;

    /**
     * @Assert\Type("string", message="It must be an string.")
     *
     * @var string
     */
    protected $hdd;

    /**
     * @Assert\Type("string", message="It must be an string.")
     *
     * @var string
     */
    protected $location;

    public function __construct(array $data = null)
    {
        $data['storage'] = $this->createStorageModel($data['storage']);
        $data['ram'] = $this->createRamCollection($data['ram']);
        parent::__construct($data);
    }

    public function getStorage(): ?StorageModel
    {
        return $this->storage;
    }

    public function setStorage(?StorageModel $storage): void
    {
        $this->storage = $storage;
    }

    public function getRam(): ?RamCollectionModel
    {
        return $this->ram;
    }

    public function setRam(?RamCollectionModel $ram): void
    {
        $this->ram = $ram;
    }

    public function getHdd(): string
    {
        return $this->hdd;
    }

    public function setHdd(string $hdd): void
    {
        $this->hdd = $hdd;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function createRamCollection(array $ramData): ?RamCollectionModel
    {
        if (empty($ramData)) {
            return null;
        }
        $ramCollectionModel = new RamCollectionModel();

        foreach ($ramData as $ramDatum) {
            $ramModel = new RamModel($ramDatum);
            $ramCollectionModel->append($ramModel);
        }

        return $ramCollectionModel;
    }

    public function createStorageModel(array $data = null): ?StorageModel
    {
        if (empty($data)) {
            return null;
        }

        $storageModel = new StorageModel($data[0]);

        return $storageModel;
    }
}
