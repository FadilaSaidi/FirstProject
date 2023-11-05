<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\Request; 
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorController extends AbstractController
{

    private $authors = array(
        array('id' => 1, 'picture' => '/images/Victor_Hugo.jpg', 'username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william_shakespeare.jpg', 'username' => 'William Shakespeare', 'email' =>
            'william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
    );


    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }



    #[Route('/List', name: 'author_list')]
   public function list(): Response
   {
       return $this->render('author/list.html.twig', [
           'authors' => $this->authors, 
       ]);
   }


   #[Route('/author/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        // Recherchez l'auteur correspondant dans le tableau $this->authors
        $author = null;
        foreach ($this->authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    
    #[Route('/add-authors', name: 'add_authors')]
    public function addAuthors(EntityManagerInterface $entityManager): Response
    {
        // Créez des objets Author et ajoutez-les à la base de données
        $authorsData = [
            ['username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100, 'picture' => '/images/Victor_Hugo.jpg'],
            ['username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200, 'picture' => '/images/william_shakespeare.jpg'],
            // Ajoutez d'autres auteurs ici
        ];

        foreach ($authorsData as $authorData) {
            $author = new Author();
            $author->setUsername($authorData['username']);
            $author->setEmail($authorData['email']);
           // $author->setNbBooks($authorData['nb_books']);
            //$author->setPicture($authorData['picture']);

            $entityManager->persist($author);
        }

        $entityManager->flush();

        return new Response('Auteurs ajoutés à la base de données.');
    }


 /*   #[Route('/authors-list', name: 'authors_list')]
    public function listAuthors(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $authors = $entityManager->getRepository(Author::class)->findAll();

        return $this->render('author/list_authors.html.twig', [
            'authors' => $authors,
        ]);
    }*/
    #[Route('/Affiche', name: 'app_Affiche')]


    public function Affiche (AuthorRepository $repository)
        {
            $author=$repository->findAll() ; //select *
            return $this->render('author/Affiche.html.twig',['author'=>$author]);
        }

    #[Route('/add-static-author', name: 'add_static_author')]
    public function addStaticAuthor(ManagerRegistry $Manager): Response
    {
        // Créez un auteur avec des données statiques
        $author = new Author();
        $author->setUsername('Auteur Statique');
        $author->setEmail('auteur.statique@example.com');

      $em=$Manager->getManager();
        // Persistez l'auteur dans la base de données
        //executer les requtes insert delete update 
        $em->persist($author);//créer la requéte 
        $em->flush(); //réelement exécuter la requéte 
            return new Response ('author added');
       // return $this->redirectToRoute('app_Affiche');
    }


    #[Route('/add-author', name: 'add_author')]
    public function addAuthor(ManagerRegistry $manager,Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        //$form->add('Ajouter' , SubmitType::class ); on a ajouter dans AuthorType 

       $form->handleRequest($request);
       $em=$manager->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_Affiche'); // Redirigez vers une autre page après l'ajout de l'auteur
        }
        return $this->renderForm('author/addauthor.html.twig',//
        ['f'=>$form]);//->createView()
   }





    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(ManagerRegistry $manager ,AuthorRepository $repository, $id, Request $request):Response
    {
        $author = $repository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
       // $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        $em=$manager->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer
            // les modifications en base de données.
            return $this->redirectToRoute("app_Affiche");
        }

        return $this->render('author/addauthor.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    #[route('/delete/{id}',name:'app_delete')]
 public function delete ($id, AuthorRepository $repo,ManagerRegistry $manager):Response{
    $author=$repo->find($id);
    $em=$this->getDoctrine()->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("app_Affiche");


 }
 #[route('/byEmail',name:'authors_by_email')]
 public function listAuthorsByEmail(AuthorRepository $repo): Response
 {
    $author = $repo->listAuthorByEmail();
    // $authors = $this->$repo->listAuthorByEmail();

     return $this->render('author/authors_by_email.html.twig', [
         'authors' => $author,
     ]);
 }
 



 #[Route('/get', name: 'author_get')]
 public function getList(AuthorRepository $repo): Response
 {
     
     
     return $this->render('author/listDB.html.twig', [
         'authors' => $repo->getAuthors(7)
     ]);
 }







    #[Route('/getAll', name: 'author_listDB')]
    public function getAll(AuthorRepository $repo): Response
    {
        /* Select * from author */
        $list = $repo->findAll();
        return $this->render('author/listDB.html.twig', [
            'authors' => $list
        ]);
    }
    #[Route('/getOne/{id}', name: 'author_listD')]
    public function getOne(AuthorRepository $repo, $id): Response
    {
        /* Select * from author */
        $author = $repo->find($id);
        return $this->render('author/detailsDB.html.twig', [
            'author' => $author
        ]);
    }
}




    

