<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Vote;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Repository\VoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    #[Route('/joueur', name: 'app_joueur')]
    public function index(): Response
    {
        return $this->render('joueur/index.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }


    #[Route('/addJoueur', name: 'joueur_add')] 
    public function addJoueur(Request $req , ManagerRegistry $manager):Response{ // request http fondation 

        $joueur = new Joueur(); // pour la modif on va faire rep->find(id)
        $form = $this->createForm(JoueurType::class, $joueur); // il va me generer un formulaire 
        $form->handleRequest($req); // pour faire lajout dans la base si non lorsque je clique sur save ca va pas marché

        $em = $manager->getManager();
        if($form->isSubmitted() ){ // pour verifier les champs de formulaire
        $em->persist($joueur); // amalna instance w toul persist w flush or que instance jdida kol chy null w hatina condition que username not null
        $em->flush();
        return $this->redirectToRoute("joueur_listDB");// pour revenir a la liste des auteurs 
    }
         return $this->renderForm('joueur/add.html.twig',[
        'f'=>$form
    ]);
}


#[Route('/getJoueur', name: 'joueur_listDB')]
    public function getAll(JoueurRepository $repo):Response //author repository pour faire un appel a la fonction findAll()
    {
        /* select * from author */
        $list = $repo->findAll();
        return $this->render('joueur/index.html.twig', [
            'joueur' => $list

        ]);
    }


    #[Route('/updateJoueur/{id}', name: 'joueur_update')] 
    public function updateJoueur(Request $req , ManagerRegistry $manager , JoueurRepository $repo , $id):Response{ // request http fondation 

        $joueur = $repo->find($id); // pour la modif on va faire rep->find(id)
        $form = $this->createForm(JoueurType::class, $joueur); // il va me generer un formulaire 
        $form->handleRequest($req); // pour faire lajout dans la base si non lorsque je clique sur save ca va pas marché

        $em = $manager->getManager();
        if($form->isSubmitted() ){
        $em->persist($joueur); // amalna instance w toul persist w flush or que instance jdida kol chy null w hatina condition que username not null
        $em->flush();
        return $this->redirectToRoute("joueur_listDB");// pour revenir a la liste des auteurs 
    }
         return $this->renderForm('joueur/add.html.twig',[
        'f'=>$form
    ]);

}

#[Route('/getVote/{vote}', name: 'joueurVote_oneDB')]
    public function getOne(VoteRepository $repo, $vote):Response // repository pou faire appel a la fonction find($id)
    {
        $author = $repo->find($vote);
        return $this->render('joueur/index.html.twig', [
            'joueur' => $author

        ]);
    }
    
}