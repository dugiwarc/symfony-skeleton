<?php


namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignalController extends AbstractController
{

    /**
     * @param MicroPost $microPost
     * @return JsonResponse
     * @Route("/signal/{id}", name="signal_post")
     */
    public function signal(MicroPost $microPost) : JsonResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->signal($currentUser);
        $this->getDoctrine()->getManager()->flush();


        return new JsonResponse([
            'count' => $microPost->getSignaledBy()->count()
        ], Response::HTTP_ACCEPTED);
    }

}