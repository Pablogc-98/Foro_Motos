<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $hasher,SluggerInterface $slugger ): Response
    {
        $usuario = new Usuario();

        $formulario_registro = $this->createForm(UserType::class, $usuario);
        $formulario_registro->handleRequest($request);



        if($formulario_registro->isSubmitted() && $formulario_registro->isValid()){
            $archivo = $formulario_registro->get('foto')->getData();

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
            $role = ["ROLE_USUER"];
            $usuario->setRoles($role);
            $plaintextPassword = $formulario_registro->get('password')->getData();
            $hashedPassword = $hasher->hashPassword(
                $usuario,
                $plaintextPassword
            );
            $usuario->setFoto($newFilename);
            $usuario->setPassword($hashedPassword);
            $this->em->persist($usuario);
            $this->em->flush();
            return $this->redirectToRoute('app_inicio');

        }

        return $this->render('user/index.html.twig', [
            'formulario_registro' => $formulario_registro->createView(),
        ]);
    }
}
