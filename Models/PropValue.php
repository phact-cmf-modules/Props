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
 * @date 03/05/17 08:19
 */
namespace Modules\Props\Models;

use Phact\Main\Phact;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\IntField;
use Phact\Orm\Model;

abstract class PropValue extends Model
{
    public static function getFields() 
    {
        $objectClass = Phact::app()->getModule('Props')->modelClass;
        return [
            'prop' => [
                'class' => ForeignField::class,
                'modelClass' => Prop::class,
                'label' => 'Характеристика'
            ],
            'object' => [
                'class' => ForeignField::class,
                'modelClass' => $objectClass,
                'label' => 'Объект'
            ]
        ];
    }
} 