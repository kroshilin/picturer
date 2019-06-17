<?php
/**
 * Created by PhpStorm.
 * User: kroshilin
 * Date: 2019-05-25
 * Time: 15:59
 */

namespace App\Resolver;


use App\Controller\PictureController;

class QueryControllerFactory extends ControllerFactory
{
    protected $map = [
        'picture' => [PictureController::class, 'show'],
        'pictures' => [PictureController::class, 'list'],
        'randomPicture' => [PictureController::class, 'randomPicture'],
    ];
}