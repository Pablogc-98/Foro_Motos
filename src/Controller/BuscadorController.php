<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\BuscadorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BuscadorController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    #[Route('/buscador', name: 'app_buscador')]
    public function index(Request $request): Response
    {
        $post =new Post();

        $buscador= $this->createForm(BuscadorType::class, $post);
        $buscador->handleRequest($request);

        
        $titulo = $buscador->get('titulo')->getData();
        $posts = $this->em->getRepository(Post::class)->buscarPost($titulo);


        return $this->render('buscador/index.html.twig', [
            'form'=> $buscador->createView(),
            'posts'=>$posts,
        ]);
    }
}
