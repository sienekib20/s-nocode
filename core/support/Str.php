<?php

namespace core\support;

use ReflectionClass;

class Str
{
    public static function generatePatternOf($key = '/')
    {

        return '/^' . str_replace(['/', '[', ']'], ['\/', '([', '])'], $key) . '$/';
    }

    public static function changeAllParamsIndex($array)
    {
        if (empty($array)) {

            return [];
        }

        return (object) [
            'id' => $array[0],
            'next' => array_values(array_slice($array, 1))
        ];
    }

    private function callNextParam()
    {
    }

    public static function getCurrentModelTableName($currentModel)
    {
        $currentModel = new ReflectionClass($currentModel);

        $currentModelProperties = $currentModel->getProperties();

        foreach ($currentModelProperties as $currentModelProperty) {

            if ($currentModelProperty->name == 'table') {

                return ucfirst($currentModel->getStaticPropertyValue($currentModelProperty->name));
            }
        }

        $defaultTableName = explode('\\', $currentModel->name);

        return ucfirst(end($defaultTableName));
    }
}
