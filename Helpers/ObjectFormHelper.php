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
 * @date 11/05/17 09:37
 */

namespace Modules\Props\Helpers;

use Modules\Props\Models\Prop;
use Phact\Form\Fields\CharField;
use Phact\Form\Fields\DropDownField;
use Phact\Helpers\Text;

class ObjectFormHelper
{
    public static function getPropsFields($object)
    {
        $fields = [];
        $properties = Prop::objects()->order(['position'])->all();
        $values = $object ? PropHelper::getValues($object, $properties) : [];
        foreach ($properties as $property) {
            $field = [];

            $field['label'] = $property->name . ($property->unit ? ' (' . $property->unit . ')' : '');

            if ($property->type == Prop::TYPE_NUMBER) {
                $field['class'] = CharField::class;
            } elseif ($property->type == Prop::TYPE_CHAR) {
                $field['class'] = CharField::class;
            } elseif ($property->type == Prop::TYPE_LIST) {
                $field['class'] = DropDownField::class;
                $field['choices'] = $property->variants->order(['position'])->choices('id', 'name');
            }

            if (isset($values[$property->id])) {
                $val = $values[$property->id];
                if ($property->isTypeNumber) {
                    $val = floatval($val);
                }
                $field['value'] = $val;
            }

            if ($property->required) {
                $field['required'] = true;
            }

            $fields['property__' . $property->id] = $field;
        }
        return $fields;
    }

    public static function setPropsFields($object, $fields)
    {
        $data = [];
        foreach ($fields as $name => $field) {
            if (Text::startsWith($name, 'property__')) {
                $id = str_replace('property__', '', $name);
                if ($id) {
                    $data[$id] = $field->getValue();
                }
            }
        }
        PropHelper::setValues($object, $data, true);
    }
}