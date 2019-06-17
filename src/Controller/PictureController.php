<?php
/**
 * Created by PhpStorm.
 * User: kroshilin
 * Date: 2019-05-25
 * Time: 13:47
 */

namespace App\Controller;


use App\DTO\PictureInput;
use App\Entity\Picture;
use App\Entity\Tag;
use App\Repository\PicturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class PictureController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(array $files)
    {
        $pictures = [];
        foreach ($files as $file) {
            $picture = new Picture();
            $picture->file = $file;

            $this->em->persist($picture);
            $pictures[] = $picture;
        }

        $this->em->flush();

        return $pictures;
    }

    public function update(PictureInput $pictureInput)
    {
        /** @var Picture $p */
        $p = $this->em->getRepository(Picture::class)->find($pictureInput->id);
        $p->tags->clear();
        foreach ($pictureInput->tags as $tag) {
            if (!$ttag = $this->em->find(Tag::class, $tag->name)) {
                $ttag = new Tag();
                $ttag->name = $tag->name;
            }
            $p->tags->add($ttag);
        }
        $this->em->persist($p);
        $this->em->flush();

        return $p;
    }

    public function show(int $id)
    {
        return $this->em->getRepository(Picture::class)->find($id);

    }

    public function list(Argument $args)
    {
        /** @var PicturesRepository $picturesRepo */
        $picturesRepo = $this->em->getRepository(Picture::class);
        $paginator = new Paginator(function ($offset, $limit) use ($picturesRepo) {
            return $picturesRepo->getPaginatedData($offset, $limit);
        });

        return $paginator->auto($args, function () use ($picturesRepo) {
            return $picturesRepo->countAll();
        });
    }

    public function randomPicture(string $tag)
    {
        /** @var PicturesRepository $picturesRepo */
        $picturesRepo = $this->em->getRepository(Picture::class);
        $pic = $picturesRepo->getRandomPictureByTag($tag);
        return $pic;
    }

    public function delete(PictureInput $pictureInput)
    {
        $picture = $this->em->getRepository(Picture::class)->find($pictureInput->id);
        $this->em->remove($picture);
        $this->em->flush();

        return $pictureInput->id;
    }

}