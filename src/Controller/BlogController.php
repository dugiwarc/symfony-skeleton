<?php


namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class BlogController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;


    public function __construct(SessionInterface $session, RouterInterface $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request): Response
    {
        return $this->render('blog/index.html.twig',
            [
                'posts' => $this->session->get('posts')
            ]
        );
     }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", schemes={"http", "https"})
     */
    public function test(Request $request, $age): Response
    {
        // $age = $request->query->get('age', 10);
        // $age = $request->attributes->get('age');

        return new Response("Vous avez $age ans !");
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add(): RedirectResponse
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title'.rand(1, 500),
            'text' => 'Some random text nr'.rand(1, 500),
            'date' => new \DateTime(),
        ];
        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id): Response
    {
        $posts = $this->session->get('posts');

        if(!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        $html = $this->render(
            'blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id]
            ]
        );

        return new Response($html);
    }
}