<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service')]
    public function index($name): Response
    {
        $classe ="3A7";
        $services = [[
            'name'=> 'Service 1',
            'desc'=>'description 1 '
        ],
        ['name'=> 'Service 2',
        'desc'=>'description 2  ']
    ];

        return $this->render('service/index.html.twig', [
            'c' => $classe,
            'name' => $name,
            'services'=> $services
            
        ]);
    }
    public function goToIndex(): Response
{
    return $this->redirectToRoute('home');
}

}
