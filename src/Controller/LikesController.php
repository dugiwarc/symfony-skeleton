<?php


namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikesController extends AbstractController
{

    /**
     * @param MicroPost $microPost
     * @return JsonResponse
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $microPost) : JsonResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->like($currentUser);
        $this->getDoctrine()->getManager()->flush();


        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param MicroPost $microPost
     * @return JsonResponse
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $microPost): JsonResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->getLikedBy()->removeElement($currentUser);
        $this->getDoctrine()->getManager()->flush();


        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ], Response::HTTP_ACCEPTED);
    }



}