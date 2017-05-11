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
 * @date 03/05/17 08:09
 */
namespace Modules\Props\Models;

use Modules\Props\Helpers\PropHelper;
use Phact\Main\Phact;
use Phact\Orm\Fields\BooleanField;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\HasManyField;
use Phact\Orm\Fields\IntField;
use Phact\Orm\Fields\PositionField;
use Phact\Orm\Model;

class Prop extends Model
{
    const TYPE_LIST = 1;
    const TYPE_NUMBER = 2;
    const TYPE_CHAR = 3;

    const VIEW_LIST = 1;
    const VIEW_RANGE = 2;
    const VIEW_HIDE = 10;

    public static function getFields() 
    {
        return [
            'name' => [
                'class' => CharField::class,
                'label' => 'Наименование',
            ],
            'type' => [
                'class' => IntField::class,
                'label' => 'Тип',
                'choices' => [
                    self::TYPE_LIST => 'Список',
                    self::TYPE_NUMBER => 'Число',
                    self::TYPE_CHAR => 'Строка'
                ],
            ],
            'view' => [
                'class' => IntField::class,
                'label' => 'Вид',
                'choices' => [
                    self::VIEW_LIST => 'Список',
                    self::VIEW_RANGE => 'Список (от-до)',
                    self::VIEW_HIDE => 'Не отображать'
                ],
                'default' => self::VIEW_LIST
            ],
            'unit' => [
                'class' => CharField::class,
                'label' => 'Единица измерения',
                'hint' => 'мм, см, м',
                'null' => true
            ],
            'position' => [
                'class' => PositionField::class,
                'editable' => false,
                'relations' => []
            ],
            'variants' => [
                'class' => HasManyField::class,
                'label' => 'Варианты',
                'editable' => false,
                'modelClass' => PropVariant::class
            ],
            'required' => [
                'class' => BooleanField::class,
                'label' => 'Обязательно для заполнение',
                'default' => 0
            ],
        ];
    }

    public static function getValuesModels()
    {
        return [
            self::TYPE_LIST => PropListValue::class,
            self::TYPE_NUMBER => PropNumberValue::class,
            self::TYPE_CHAR => PropCharValue::class
        ];
    }

    public function getValueModel()
    {
        $values = self::getValuesModels();
        return isset($values[$this->type]) ? $values[$this->type] : null;
    }

    public function getValue($object)
    {
        $modelClass = $this->getValueModel();
        if ($modelClass) {
            $values = $modelClass::objects()->filter(['object_id' => $object->id, 'prop_id' => $this->id])->values(['value'], true);
            if (count($values) > 0) {
                return $values[0];
            }
        }
        return null;
    }

    public function getObjects()
    {
        $modelClass = $this->getValueModel();
        if ($modelClass) {
            $objectClass = Phact::app()->getModule('Props')->modelClass;
            return $objectClass::objects()->filter([
                'id__in' => $modelClass::objects()->filter(['prop_id' => $this->id])->select(['object_id'])
            ])->all();
        }
        return [];
    }
    
    public function __toString() 
    {
        return $this->name;
    }

    public function afterSave()
    {
        parent::afterSave();
        if (!is_null($this->getChangedAttribute('name')) || !is_null($this->getChangedAttribute('unit'))) {
            PropHelper::propChanged($this);
        }
    }

    public function afterDelete()
    {
        parent::afterSave();
        PropHelper::propDeleted($this);
    }
} 