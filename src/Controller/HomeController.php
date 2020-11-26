<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="homepage")
     */
    public function home(FigureRepository $repo){
        $figures = $repo->findAll();

        return $this->render(
            'home.html.twig', [
                'figures' => $figures
            ]
        );
    }
}


?>