<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Usuario;
use App\Form\PerfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PerfilController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/perfil', name: 'app_perfil')]
    public function index(Request $request, SluggerInterface $slugger): Response
    {   
        $user = $this->getUser();
        $username = $user->getUserIdentifier();
        $form = $this->createForm(PerfilType::class);
        $form->handleRequest($request);

        $usuario = $this->em->getRepository(Usuario::class)->findOneBy(['username'=>$username]);
           
        $posts = $this->em->getRepository(Post::class)->userPost($username);
        
        if($form->isSubmitted() && $form->isValid()){
            $archivo = $form->get('foto')->getData();

            if($archivo){
                $originalFilename = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);     
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$archivo->guessExtension();
                try {
                    $archivo->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Hay un problemita con tu archivo');
                }                
            }
            $usuario->setFoto($newFilename);
            $this->em->flush();

        }


       // $post = $this->em->getRepository(Post::class)->finddBy(['username'=> $user.username])
        return $this->render('perfil/index.html.twig', [
            'user' => $user,
            'form'=>$form->createView(),
             'posts' =>$posts,

        ]);
    }
}
