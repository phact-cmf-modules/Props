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
 * @date 10/05/17 14:46
 */
namespace Modules\Props;

use Modules\Props\Helpers\PropHelper;
use Modules\Props\Models\PropVariant;
use Phact\Main\Phact;
use Phact\Module\Module;
use Modules\Admin\Traits\AdminTrait;

class PropsModule extends Module
{
    use AdminTrait;

    public $modelClass = null;

    public static function onApplicationRun()
    {
        $modelClass = Phact::app()->getModule('Props')->modelClass;
        Phact::app()->event->on($modelClass . '::afterDelete', function($sender) {
            PropHelper::objectDeleted($sender);
        });
    }

    public static function getVerboseName()
    {
        return "Свойства";
    }
}