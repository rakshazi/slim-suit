<?php
namespace Rakshazi\SlimSuit;

abstract class Entity
{
    /**
     * @var \Rakshazi\SlimSuit\App
     */
    protected $app;

    /**
     * @var array
     */
    protected $data;

    public function __construct(\Rakshazi\SlimSuit\App $app)
    {
        $this->app = $app;
    }

    /**
     * Get data (row) by key. Return default if data not found
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Set data (row) by key. Autoupdate DB
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
        $this->app->getContainer()->db->update($this->getTable(), [$key => $value]);
    }

    /**
     * Set all data to entity, without inserting in db
     * @param array $data
     * @return \Rakshazi\SlimSuit\Entity
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Insert entity data into db
     * @param array $data
     * @return int Row id from db
     */
    public function insert(array $data): int
    {
        $this->data = $data;
        $this->app->getContainer()->db->insert($this->getTable(), $data);
        return $this->app->getContainer()->db->id();
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

    public function delete(): bool
    {
        return (bool)$this->app->getContainer()->db->delete($this->getTable(), [
            'id' => $this->get('id')
        ]);
    }

    /**
     * Return entity table name
     * @return string
     */
    abstract public function getTable(): string;
}
