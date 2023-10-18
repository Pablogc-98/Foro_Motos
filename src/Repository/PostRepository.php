<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPost(){
        return $this -> getEntityManager()
        ->createQuery('
        SELECT post.id, post.tipo, post.archivo, post.titulo, post.descripcion, post.fecha, usuario.id AS usuario_id, usuario.foto AS fotoUsuario, usuario.username AS nombreUsuario
        FROM App:Post post
        JOIN post.usuario usuario
        ORDER BY post.id DESC
        ')
        ->getResult();
    }
    
    public function buscarCategoria($categoria){
        return $this-> getEntityManager()->
        createQuery('
        SELECT post.id, post.tipo, post.archivo, post.titulo, post.descripcion, post.fecha, usuario.id AS usuario_id, usuario.foto AS fotoUsuario, usuario.username AS nombreUsuario
        FROM App:Post post
        JOIN post.usuario usuario
        WHERE post.tipo = :categoria
        ORDER BY post.id DESC')
        ->setParameter('categoria', $categoria)
        ->getResult();
    }
    public function userPost($username){
        return $this-> getEntityManager()->
        createQuery('
        SELECT post.id, post.tipo, post.archivo, post.titulo, post.descripcion, post.fecha, usuario.id AS usuario_id, usuario.foto AS fotoUsuario, usuario.username AS nombreUsuario
        FROM App:Post post
        JOIN post.usuario usuario
        WHERE usuario.username = :username
        ORDER BY post.id DESC')
        ->setParameter('username', $username)
        ->getResult();
    }
    public function buscarId($id){
        return $this-> getEntityManager()->
        createQuery('
        SELECT post.id, post.tipo, post.archivo, post.titulo, post.descripcion, post.fecha, usuario.id AS usuario_id, usuario.foto AS fotoUsuario, usuario.username AS nombreUsuario
        FROM App:Post post
        JOIN post.usuario usuario
        WHERE post.tipo = :id
        ')
        ->setParameter('id', $id)
        ->getResult();
    }
    
    public function buscarPost($titulo){
        return $this -> getEntityManager()
        ->createQuery('
        SELECT post.id, post.tipo, post.archivo, post.titulo, post.descripcion, post.fecha, usuario.id AS usuario_id, usuario.foto AS fotoUsuario, usuario.username AS nombreUsuario
        FROM App:Post post
        JOIN post.usuario usuario
        WHERE post.titulo LIKE :titulo
        ORDER BY post.id DESC
        ')
        ->setParameter('titulo', '%'.$titulo.'%')
        ->getResult();

    } 


//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
