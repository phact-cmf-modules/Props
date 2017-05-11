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

use Phact\Orm\Fields\DecimalField;

class PropNumberValue extends PropValue
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'value' => [
                'class' => DecimalField::class,
                'label' => 'Значение'
            ],
        ]);
    }
} 