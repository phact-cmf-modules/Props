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
 * @date 11/05/17 10:09
 */

namespace Modules\Props\Contrib;


use Phact\Orm\Manager;

class PropsManager extends Manager
{
    public $querySetClass = PropsQuerySet::class;
}