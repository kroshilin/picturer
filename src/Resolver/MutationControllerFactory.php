<?php
/**
 * Created by PhpStorm.
 * User: kroshilin
 * Date: 2019-05-25
 * Time: 15:59
 */

namespace App\Resolver;


use App\Controller\PictureController;

class MutationControllerFactory extends ControllerFactory
{
    protected $map = [
        'updatePicture' => [PictureController::class, 'update'],
        'uploadPictures' => [PictureController::class, 'create'],
        'deletePicture' => [PictureController::class, 'delete'],
    ];
}