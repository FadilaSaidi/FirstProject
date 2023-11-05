<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\VoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    #[Route('/vote', name: 'app_vote')]
    public function index(): Response
    {
        return $this->render('vote/index.html.twig', [
            'controller_name' => 'VoteController',
        ]);
    }


    #[Route('/addVote', name: 'vote_add')] 
    public function addVote(Request $req , ManagerRegistry $manager):Response{ // request http fondation 

        $vote = new Vote(); // pour la modif on va faire rep->find(id)
        $form = $this->createForm(VoteType::class, $vote); // il va me generer un formulaire 
        $form->handleRequest($req); // pour faire lajout dans la base si non lorsque je clique sur save ca va pas marché

        $em = $manager->getManager();
        if($form->isSubmitted() ){ // pour verifier les champs de formulaire
        $em->persist($vote); // amalna instance w toul persist w flush or que instance jdida kol chy null w hatina condition que username not null
        $em->flush();
        return $this->redirectToRoute("vote_listDB");// pour revenir a la liste des auteurs 
    }
         return $this->renderForm('vote/add.html.twig',[
        'f'=>$form
    ]);
}

#[Route('/getVote', name: 'vote_listDB')]
    public function getAll(VoteRepository $repo):Response //author repository pour faire un appel a la fonction findAll()
    {
        /* select * from author */
        $list = $repo->findAll();
        return $this->render('vote/index.html.twig', [
            'vote' => $list

        ]);
    }


    #[Route('/deleteVote/{id}', name: 'vote_delete')] 
public function deleteVote(ManagerRegistry $manager, $id, VoteRepository $repo):Response{

    $author = $repo->find($id);
    $em = $manager->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute("vote_listDB");
}

#[Route('/getOnee/{id}', name: 'vote_listD')]
public function getOnee(VoteRepository $repo, $id): Response
{
    $vote = $repo->find($id);
    
    return $this->render('vote/show.html.twig', [
        'vote' => $vote,
    ]);
}


}