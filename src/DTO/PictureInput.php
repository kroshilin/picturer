<?php
/**
 * Created by PhpStorm.
 * User: kroshilin
 * Date: 2019-05-25
 * Time: 18:38
 */

namespace App\DTO;

use App\Entity\Tag;

class PictureInput
{
    public function __construct(int $id, array $tags)
    {
        $this->id = $id;
        foreach ($tags as $tag) {
            $tagEntity = new Tag();
            $tagEntity->name = $tag['name'];
            $this->tags[] = $tagEntity;
        }
    }

    /** @var int  */
    public $id;

    /** @var Tag[] */
    public $tags = [];
}