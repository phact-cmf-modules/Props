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
 * @date 11/05/17 09:26
 */
namespace Modules\Props\Models;

use Modules\Props\Contrib\PropsManager;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\JsonField;
use Phact\Orm\Model;

/**
 * Class Product
 *
 * @method static PropsManager objects($model = null)
 *
 * @package Modules\Props\Models
 */
abstract class Product extends Model
{
    public static function getFields() 
    {
        return [
            'name' => [
                'class' => CharField::class,
                'label' => 'Наименование',
            ],
            /** Должно быть в объекте обязательно */
            'raw_props' => [
                'class' => JsonField::class,
                'label' => 'Сырые данные свойств',
                'null' => true,
                'editable' => false
            ],
        ];
    }
    
    public function __toString() 
    {
        return (string)$this->name;
    }

    /**
     * Необходимо для того, чтобы работала фильтрация
     *
     * @param null $model
     * @return PropsManager
     */
    public static function objectsManager($model = null)
    {
        if (!$model) {
            $model = new static;
        }
        return new PropsManager($model);
    }
} 