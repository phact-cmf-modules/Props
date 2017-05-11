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
 * @date 11/05/17 10:08
 */
namespace Modules\Props\Controllers;

use Modules\Props\Models\Product;
use Phact\Controller\Controller;

class PropController extends Controller
{
    public function index()
    {
        $qs = Product::objects()->getQuerySet();
        $qs->filter([
            'prop@1' => 1,
            'prop@2__gt' => 4
        ]);
        $qs->order([
            '-prop@1'
        ]);
    }
}