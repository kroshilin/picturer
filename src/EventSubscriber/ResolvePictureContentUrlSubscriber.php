<?php

namespace App\EventSubscriber;

use App\Entity\Picture;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolvePictureContentUrlSubscriber
{
    private $storage;
    private $request;

    public function __construct(StorageInterface $storage, RequestStack $requestStack)
    {
        $this->storage = $storage;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $picture = $args->getEntity();
        if ($picture instanceof Picture) {
            $picture->contentUrl =
                $this->request->getScheme() . "://" . $this->request->getHost() .
                $this->storage->resolveUri($picture, 'file');
        }
    }
}