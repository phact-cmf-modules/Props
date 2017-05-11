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
 * @date 03/05/17 08:14
 */
namespace Modules\Props\Models;

use Modules\Props\Helpers\PropHelper;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\PositionField;
use Phact\Orm\Model;

class PropVariant extends Model
{
    public static function getFields() 
    {
        return [
            'name' => [
                'class' => CharField::class,
                'label' => 'Наименование',
            ],
            'prop' => [
                'class' => ForeignField::class,
                'modelClass' => Prop::class,
                'label' => 'Характеристика'
            ],
            'position' => [
                'class' => PositionField::class,
                'editable' => false,
                'relations' => ['prop']
            ],
        ];
    }
    
    public function __toString() 
    {
        return $this->name;
    }

    public function afterUpdate()
    {
        parent::afterUpdate();
        PropHelper::propChanged($this->prop);
    }

    public function afterDelete()
    {
        PropHelper::propChanged($this->prop);
        parent::afterDelete();
    }
} 