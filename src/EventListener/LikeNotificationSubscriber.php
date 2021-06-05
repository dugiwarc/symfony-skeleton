<?php


namespace App\EventListener;


use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\PersistentCollection;

class LikeNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        // TODO: Implement getSubscribedEvents() method.
          return [
              Events::onFlush
          ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        /**
         * @var PersistentCollection $collectionUpdate
         */
        foreach($unitOfWork->getScheduledCollectionUpdates() as $collectionUpdate) {
            if(!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            }

            if($collectionUpdate->getMapping()['fieldName'] !== 'likedBy') {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if(!count($insertDiff)) {
                return;
            }

            /**
             * @var MicroPost
             */
            $microPost = $collectionUpdate->getOwner();
            $likedBy = reset($insertDiff);

            // Don't notify me when I'm liking my own post
            if($likedBy->getId() === $microPost->getUser()->getId()) {
                return;
            }

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy($likedBy);

            $entityManager->persist($notification);
            $unitOfWork->computeChangeSet(
                $entityManager->getClassMetadata(LikeNotification::class),
                $notification
            );
        }

    }
}