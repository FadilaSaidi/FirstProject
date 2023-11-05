<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an arrapublic function findPublishedBookByName($name)
public function getBooksByAuthors()
 {
        return $this->createQueryBuilder('b')
            ->Join('b.author', 'a')
            ->where('b.title like :searchTerm')
            ->andWhere('b.category = :c')
            ->setParameter('c','action')
            ->setParameter('searchTerm',"%m%")
            ->orderBy('a.username', 'DESC')
            ->getQuery()
            ->getResult();

    }

public function books() {
    return $this -> createQueryBuilder('b')
    ->select('count(b.title) as nb')
    ->where('b.category = :c')
    ->setParameter('c','Thriller')
    ->getQuery()
    ->getSingleScalarResult();

}
public function nbbooks() {
   $em=$this->getEntityManager();
   return $em ->createQuery('
    SELECT count(b) FROM App\Entity\Book b WHERE b.category= :c ') 
   ->setParameter('c','thriller')
   ->getSingleScalarResult();
}
public function getBookByRef($ref){
    return $this->createQueryBuilder('b')
    ->where('b.ref = :r')
    ->setParameter('r', $ref)
    ->getQuery()
    ->getResult();


}
public function getBooksList(){
    return $this->createQueryBuilder('b')
    ->join('b.author','a')
    ->where('a.nb_books >= 10')
    ->andWhere('b.publicationDate > 2023')
    ->getQuery()
    ->getResult();
}

}






    


//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
