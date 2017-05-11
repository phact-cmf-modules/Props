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
 * @date 11/05/17 09:28
 */
namespace Modules\Props\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Props\Forms\PropForm;
use Modules\Props\Models\Prop;

class PropAdmin extends Admin
{
    public function getSearchColumns()
    {
        return ['name'];
    }
    
    public function getModel()
    {
        return new Prop;
    }
    
    public static function getName()
    {
        return 'Свойства';
    }

    public static function getItemName()
    {
        return 'Свойство';
    }

    public function getForm()
    {
        return new PropForm();
    }

    public function getRelatedAdmins()
    {
        $instance = $this->getInstance();
        $hasVariants = $instance->getIsNew() || $instance->type == Prop::TYPE_LIST;
        if ($hasVariants) {
            return [
                'variants' => PropVariantAdmin::class
            ];
        }
        return [];
    }
}