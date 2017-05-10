<?php
namespace Rakshazi\SlimSuit;

abstract class Entity
{

    /**
     * Adopted for SlimSuit version of rakshazi/get-set-trait package
     * @link https://github.com/rakshazi/getSetTrait
     */
    use Utils\GetSetTrait;

    /**
     * @var \Rakshazi\SlimSuit\App
     */
    protected $app;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(\Rakshazi\SlimSuit\App $app)
    {
        $this->app = $app;
    }

    /**
     * Return all entity data as array
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set all data to entity
     * @param array $data
     * @return \Rakshazi\SlimSuit\Entity
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Save entity data in db
     */
    public function save()
    {
        if ($this->getId()) {
            $this->app->getContainer()->db->update($this->getTable(), $this->data, ['id' => $this->getId()]);
        } else {
            $this->app->getContainer()->db->insert($this->getTable(), $this->data);
            $this->setId($this->app->getContainer()->db->id());
        }
    }

    /**
     * Insert entity data into db
     * @deprecated use save() instead
     * @param array $data
     * @return int Row id from db
     */
    public function insert(array $data): int
    {
        $this->data = $data;
        $this->save();

        return $this->get('id');
    }

    /**
     * Load entity (data from db)
     * @param mixed $value Field value (eg: id field with value = 10)
     * @param string $field Field name, default: id
     * @return \Rakshazi\SlimSuit\Entity
     */
    public function load($value, $field = 'id'): \Rakshazi\SlimSuit\Entity
    {
        $data = $this->app->getContainer()->db->select($this->getTable(), '*', [$field => $value]);
        $this->data = $data[0] ?? [];

        return $this;
    }

    /**
     * Get all entities from db
     * @param array $where Where clause
     * @return \Rakshazi\SlimSuit\Entity[]
     */
    public function loadAll($where = []): array
    {
        $collection = [];
        $class = substr(strrchr('\\'.get_class($this), '\\'), 1); //Get class name without namespace
        foreach ($this->app->getContainer()->db->select($this->getTable(), '*', $where) as $data) {
            $collection[] = $this->app->getEntity(ucfirst($class))->setData($data);
        }

        return $collection;
    }

    /**
     * Get count of items by $where conditions
     * @param array $where Where clause
     * @return int
     */
    public function count($where = []): int
    {
        return $this->app->getContainer()->db->count($this->getTable(), $where);
    }

    /**
     * Delete entity row from db
     * @return bool
     */
    public function delete(): bool
    {
        return (bool)$this->app->getContainer()->db->delete($this->getTable(), [
            'id' => $this->getId()
        ]);
    }

    /**
     * Return entity table name
     * @return string
     */
    abstract public function getTable(): string;
}
