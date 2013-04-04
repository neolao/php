<?php
namespace Neolao\Behavior;

/**
 * Objects that implement this interface are serializable to json format
 */
interface SerializableJson
{
    /**
     * Serialize to a json
     *
     * @return  string                  The serialized json
     */
    public function serializeJson();

    /**
     * Unserialize from a json
     *
     * @param   string      $json       The json
     */
    public function unserializeJson($json);
}
