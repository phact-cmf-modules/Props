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
 * @date 11/05/17 09:30
 */
namespace Modules\Props\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Props\Models\PropVariant;

class PropVariantAdmin extends Admin
{
    public static $ownerAttribute = 'prop';
    
    public function getSearchColumns()
    {
        return ['name'];
    }
    
    public function getModel()
    {
        return new PropVariant;
    }
    
    public static function getName()
    {
        return 'Варианты выбора';
    }

    public static function getItemName()
    {
        return 'Вариант выбора';
    }
}