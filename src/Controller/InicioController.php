<?php

namespace App\Controller;

use App\Entity\Interacciones;
use App\Entity\Post;
use App\Form\ComentariosFormType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class InicioController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    #[Route('/', name: 'app_inicio')]
    public function index(Request $request, SluggerInterface $slugger ): Response
    {
        //Instacciamos un objeto de la Entidad para poder crear varios objetos a traves del forulario 
        $post = new Post();
        $posts = $this->em->getRepository(Post::class)->findAllPost();
        
        $formulario = $this->createForm(PostType::class, $post);
        $formulario->handleRequest($request);
        

        if($formulario->isSubmitted() && $formulario->isValid()) { 

            $archivo = $formulario->get('archivo')->getData();
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

            $usuario = $this->getUser();
            $post->setUsuario($usuario);
            if ( !empty($newFilename)) {
                $post->setArchivo($newFilename);
            }else {
                $post->setArchivo('');
            }
            
            $this->em->persist($post);
            $this->em->flush();
            return $this->redirectToRoute('app_inicio');
            
        }
        $usuario = $this->getUser();

        return $this->render('inicio/index.html.twig', [
            'formulario' => $formulario->createView(),
            'posts' => $posts,
            'user'=> $usuario,
        ]);
    }

    #[Route('/post/detalles/{id}', name: 'postDetails')]
    public function postDetails(Post $post, Request $request, $id)
    {
        $cometario = new Interacciones();
        $form_comentario = $this->createForm(ComentariosFormType::class, $cometario );
        $form_comentario->handleRequest($request);

        if($form_comentario->isSubmitted() && $form_comentario->isValid()) {
            $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id]);
            $user = $this->getUser();
            $cometario->setFavorito(true);
            $cometario->setPost($post);
            $cometario->setUsuario($user);
            $this->em->persist($cometario);
            $this->em->flush();
            
        }
        $comentarios = $this->em->getRepository(Interacciones::class)->findBy(['post' => $id]);
        $usuario = $this->getUser();
        return $this->render('inicio/post-detalles.html.twig',[
            'post'=>$post,
            'form'=> $form_comentario->createView(),
            'comentarios'=> $comentarios,
            'usuario'=> $usuario

        ]);
    }

    
    #[Route('/post/{categoria}', name: 'postCategorias')]
    public function postCategoria( $categoria)
    {
            $post = $this->em->getRepository(Post::class)->buscarCategoria($categoria);
            $usuario = $this->getUser();
        return $this->render('inicio/post-categoria.html.twig',[
            'posts'=>$post,
            'usuario'=> $usuario

        ]);
    }


    //En esta función llamamos al constructor de la Entidad para borrar un post en la base de datos
    #[Route('/remove/post/{id}', name: 'remove_post')]
    public function remove($id){

        //Lo primero es buscar el post que queremos actualizar
        $post = $this->em->getRepository(Post::class)->find(id:$id);
        $interac =$this->em->getRepository(Interacciones::class)->findBy(['post'=>$id]);
        foreach($interac as $int){
            $this->em->remove($int);
        }
        $this->em->remove($post);
        //NO HAY QUE HACER EL PERSIST()
        //Como este post ya está en la base de datos no hay que ralizar esta parte del código $this->em->persist($post);
        $this->em->flush();
        return $this->redirectToRoute('app_inicio');
    }

    

}
