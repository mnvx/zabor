<?php

namespace Mnv\Blog\Models\Catalog;

class CatalogAbstract
{
    const VALUES = [];

    public static function getCodeById($id)
    {
        foreach (static::VALUES as $key => $value) {
            if ($value == $id) {
                return $key;
            }
        }
        return null;
    }

    public static function getIdByCode($code)
    {
        return isset(static::VALUES[$code]) ? static::VALUES[$code] : null;
    }

    public static function getCodes()
    {
        return array_keys(static::VALUES);
    }

}