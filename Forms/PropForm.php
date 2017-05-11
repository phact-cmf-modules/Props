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
 * @date 04/05/17 10:07
 */
namespace Modules\Props\Forms;

use Phact\Form\ModelForm;
use Modules\Props\Models\Prop;

class PropForm extends ModelForm
{
    public function getInitFields()
    {
        $instance = $this->getInstance();
        if (!$instance->getIsNew()) {
            $this->exclude[] = 'type';
        }
        return parent::getInitFields();
    }

    public function getModel()
    {
        return new Prop;
    }
}