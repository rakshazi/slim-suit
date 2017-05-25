<?php
namespace Rakshazi\SlimSuit\Traits;

/**
 * Adopted for SlimSuit version of rakshazi/get-set-trait package
 * @link https://github.com/rakshazi/getSetTrait
 */
trait GetSet
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Call method or getter/setter for property.
     *
     * @param string $method
     * @param mixed  $data
     *
     * @return mixed Data from object property
     *
     * @throws \Exception if method not implemented in class
     */
    public function __call($method = null, $params = [])
    {
        $parts = preg_split('/([A-Z][^A-Z]*)/', $method, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $type = array_shift($parts);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $params);
        }

        if ($type == 'get' || $type == 'set') {
            $property = strtolower(implode('_', $parts));
            $params = (isset($params[0])) ? [$property, $params[0]] : [$property];
            return call_user_func_array([$this, $type], $params);
        }

        throw new \Exception('Method "'.$method.'" not implemented.');
    }

    /**
     * Get property data, eg get('post_id').
     *
     * @param string $property
     * @param mixed $default Default value if property not exists
     *
     * @return mixed
     */
    public function get(string $property, $default = null)
    {
        return $this->data[$property] ?? $default;
    }

    /**
     * Set property data, eg set('post_id',1).
     *
     * @param string $property
     * @param mixed  $data
     *
     * @return $this
     */
    public function set(string $property, $data = null)
    {
        $this->data[$property] = $data;

        return $this;
    }
}
