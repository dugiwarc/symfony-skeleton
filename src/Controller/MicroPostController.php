<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class MicroPostController
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;


    public function __construct(MicroPostRepository $microPostRepository, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, RouterInterface $router, FlashBagInterface $flashBag)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index() : Response
    {
        $html = $this->render('micro-post/index.html.twig', [
            'posts' => $this->microPostRepository->findBy([],['time' => 'DESC'])
        ]);

        return new Response($html);
    }

    /**
    * @Route("/edit/{id}", name="micro_post_edit")
    * @Security("is_granted('edit', microPost)", message="Access denied")
    */
    public function edit(MicroPost $microPost, Request $request)
    {
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return new Response($this->render('micro-post/add.html.twig', ['form'=>$form->createView()]));
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', microPost)", message="Access denied")
     */
    public function delete(MicroPost $microPost): RedirectResponse
    {
        $this->entityManager->remove($microPost);
        // Only after calling flush all queries will be executed (insert/delete)
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'Micro post was deleted');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return new Response($this->render('micro-post/add.html.twig', ['form'=>$form->createView()]));
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $userWithPosts): Response
    {
        $html = $this->render('micro-post/user-posts.html.twig', [
//            'posts' => $this->microPostRepository->findBy([
//                'users' => $userWithPosts
//            ],['time' => 'DESC'])
            'posts' => $userWithPosts->getPosts(),
            'user' => $userWithPosts
        ]);

        return new Response($html);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post): Response
    {
        return new Response($this->render('micro-post/post.html.twig', ['post'=>$post]));
    }

}
