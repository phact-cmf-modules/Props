<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 04/05/17 08:55
 */

namespace Modules\Props\Helpers;

use Modules\Props\Models\Prop;
use Modules\Props\Models\PropValue;
use Phact\Orm\Model;
use Phact\Orm\QuerySet;

class PropHelper
{
    public static $_propVariants;
    public static $_props = [];

    public static function getProp($id)
    {
        if (!isset(self::$_props[$id])) {
            self::$_props[$id] = Prop::objects()->filter(['id' => $id])->get();
        }
        return self::$_props[$id];
    }

    public static function getPropVariants($prop)
    {
        if (!isset(self::$_propVariants[$prop->id])) {
            self::$_propVariants[$prop->id] = $prop->variants->order(['position'])->choices('id', 'name');
        }
        return self::$_propVariants[$prop->id];
    }

    /**
     * @param $object
     * @param $data [1 => 'Value']
     * @param bool $clean
     */
    public static function setValues($object, $data, $clean = true)
    {
        $savedProps = [];
        $objectId = $object->id;
        $rawProps = [];

        foreach ($data as $id => $value) {
            /** @var Prop $field */
            $field = self::getProp($id);
            if ($field) {
                $rawProp = self::getRawPropInfo($field, $value);
                $modelClass = $field->getValueModel();
                if (!isset($savedProps[$modelClass])) {
                    $savedProps[$modelClass] = [];
                }
                if ($modelClass) {
                    $attrs = [
                        'object_id' => $objectId,
                        'prop_id' => $field->id
                    ];
                    $valueModel = $modelClass::objects()->filter($attrs)->get();
                    if (!$valueModel) {
                        /** @var PropValue $valueModel */
                        $valueModel = new $modelClass;
                        $valueModel->setAttributes($attrs);
                    }
                    $valueModel->value = $value;
                    if ($valueModel->save()) {
                        $savedProps[$modelClass][] = $valueModel->id;
                        $rawProps[$field->id] = $rawProp;
                    }
                }
            }
        }

        $object->raw_props = $rawProps;
        $object->save();

        if ($clean) {
            self::cleanValues($object, $savedProps);
        }
    }

    public static function getRawPropInfo($prop, $value = null)
    {
        $rawProp = [];
        $rawProp['prop_id'] = $prop->id;
        $rawProp['prop_name'] = $prop->name;
        $rawProp['prop_type'] = $prop->type;
        $rawProp['prop_unut'] = $prop->unit;
        $rawProp['value'] = $value;
        if ($prop->type == Prop::TYPE_LIST) {
            $variants = self::getPropVariants($prop);
            $rawProp['human_value'] = isset($variants[$value]) ? $variants[$value] : null;
        } else {
            $rawProp['human_value'] = $value;
        }
        return $rawProp;
    }

    /**
     * Очень затратный способ! Использовать только в крайних случаях для дебага!
     *
     * @param $object
     * @param array $props
     * @return array
     */
    public static function getValues($object, $props = [])
    {
        /** @var Prop[] $props */
        $props = $props ?: Prop::objects()->order(['position'])->all();
        $values = [];
        if ($props) {
            foreach ($props as $prop) {
                $value = $prop->getValue($object);
                if ($value) {
                    $values[$prop->id] = $value;
                }
            }
        }
        return $values;
    }

    public static function cleanValues($object, $excludedValues = [])
    {
        foreach (Prop::getValuesModels() as $modelClass) {
            $excluded = [];
            if (isset($excludedValues[$modelClass])) {
                $excluded = $excludedValues[$modelClass];
            }
            /** @var QuerySet $qs */
            $qs = $modelClass::objects()->getQuerySet();
            $qs = $qs->filter(['object_id' => $object->id]);
            if ($excluded) {
                $qs = $qs->exclude(['id__in' => $excluded]);
            }
            $qs->delete();
        }
    }

    /**
     * @param Prop $prop
     */
    public static function propChanged($prop)
    {
        $objects = $prop->getObjects();
        foreach ($objects as $object) {
            $props = $object->raw_props;
            if (isset($props[$prop->id])) {
                $props[$prop->id] = self::getRawPropInfo($prop, $props[$prop->id]['value']);
                $object->raw_props = $props;
                $object->save();
            }
        }
    }

    /**
     * @param Prop $prop
     */
    public static function propDeleted($prop)
    {
        $objects = $prop->getObjects();
        foreach ($objects as $object) {
            $props = $object->raw_props;
            if (isset($props[$prop->id])) {
                unset($props[$prop->id]);
                $object->raw_props = $props;
                $object->save();
            }
        }
        $modelClass = $prop->getValueModel();
        $modelClass::objects()->filter([
            'prop_id' => $prop->id
        ])->delete();
    }

    /**
     * @param Model $object
     */
    public static function objectDeleted($object)
    {
        self::cleanValues($object);
    }
}