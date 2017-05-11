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
 * @date 11/05/17 09:37
 */
namespace Modules\Props\Forms;

use Modules\Props\Helpers\ObjectFormHelper;
use Modules\Props\Helpers\PropHelper;
use Phact\Form\ModelForm;
use Modules\Props\Models\Product;
use Phact\Helpers\Text;

class ProductForm extends ModelForm
{
    public function getFields()
    {
        return ObjectFormHelper::getPropsFields($this->getInstance());
    }

    public function getModel()
    {
        return new Product;
    }

    public function save($safeAttributes = [])
    {
        $saved = parent::save($safeAttributes);
        if ($saved) {
            ObjectFormHelper::setPropsFields($this->getInstance(), $this->getInitFields());
        }
        return $saved;
    }
}