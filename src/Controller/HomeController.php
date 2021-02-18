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
    public function home(FigureRepository $repo, $page = 1, $groupe = 1){
        $limit = 15;
        $start = $groupe * $limit - $limit;
        $figures = $repo->findBy(array(),array('id' => 'ASC'), $limit, $start);

        return $this->render(
            'home.html.twig', [
                'page' => $page,
                'groupe' => $groupe,
                'figures' => $figures
            ]
        );
    }

    /**
     * @Route("/homepage2/{groupe}", name="homepage2")
     */
    public function home2(FigureRepository $repo, $page = 1, $groupe = 1){
        $limit = 15;
        $start = $groupe * $limit - $limit;
        $figures = $repo->findBy(array(),array('id' => 'ASC'), $limit, $start);

        return $this->render(
                'partials/listeTrick.html.twig', [
                    'page' => $page,
                    'groupe' => $groupe,
                    'figures' => $figures
            ]
        );
    }
}
?>