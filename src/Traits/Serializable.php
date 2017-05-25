<?php
namespace Rakshazi\SlimSuit\Traits;

trait Serializable
{
    public function serialize()
    {
        return serialize($this->data);
    }
    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
