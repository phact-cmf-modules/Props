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
 * @date 11/05/17 09:36
 */
namespace Modules\Props\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Props\Forms\ProductForm;
use Modules\Props\Models\Product;

abstract class ProductAdmin extends Admin
{
    public static $ownerAttribute = 'example';

    public function getSearchColumns()
    {
        return ['name'];
    }
    
    public function getModel()
    {
        return new Product;
    }
    
    public static function getName()
    {
        return 'Products';
    }

    public static function getItemName()
    {
        return 'Product';
    }

    public function getForm()
    {
        return new ProductForm();
    }
}