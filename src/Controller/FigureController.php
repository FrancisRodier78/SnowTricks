<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Document;
use App\Form\FigureType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Service\FileUploader;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    /**
     * Permet de voir une figure de snowboard
     * 
     * @Route("/show/{slug}", name="figure_show")
     *
     * @return Response
     */
    public function show(Figure $figure, Request $request, EntityManagerInterface $manager) {
        $commentaire = new Commentaire();
        $commentaire->setCreationDate(new \DateTime('now'));
        $commentaire->setAuthor($this->getUser());
        $commentaire->setFigure($figure);

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($commentaire);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire a bien été enregistré !"
            );
        };
    
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de crééer trick
     * 
     * @Route("/create/", name="figure_create")
     * @IsGranted("ROLE_USER")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader) {
        $figure = new Figure();
        $figure->setCreationDate(new \DateTime('now'));
        $figure->setModifDate(null);
        $figure->setAuthorId($this->getUser());

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $imageDefaut = $form->get('imageDefaut')->getData();

            if ($imageDefaut) {
                $imageDefautName = $fileUploader->upload($imageDefaut);
                $figure->setImageDefaut($imageDefautName);
            }

            $manager->persist($figure);
            $manager->flush();

            $this->addFlash(
                'success',
                "La figure a bien été enregistrée !"
            );

            return $this->redirectToRoute('figure_show', [
                'slug' => $figure->getSlug()
            ]);
        };
    
        return $this->render('figure/create_modif.html.twig', [
            'figure' => $figure,
            'form' =>$form->createView()
        ]);

    }

    /**
     * Permet de modifier un trick
     * 
     * @Route("/modif/{slug}", name="figure_modif")
     * @Security("is_granted('ROLE_USER') and user === figure.getAuthorId()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function modif(Figure $figure, Request $request, EntityManagerInterface $manager) {
        $figure->setModifDate(new \DateTime('now'));

        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();

            $this->addFlash(
                'success',
                "La figure a bien été enregistrée !"
            );

            return $this->redirectToRoute('figure_show', [
                'slug' => $figure->getSlug()
            ]);
        };
    
        return $this->render('figure/create_modif.html.twig', [
            'figure' => $figure,
            'form' =>$form->createView()
        ]);

    }

    /**
     * Permet de supprimer un trick
     * 
     * @Route("/supp/{slug}", name="figure_supp")
     * @Security("is_granted('ROLE_USER') and user === figure.getAuthorId()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function supp(Figure $figure, Request $request, EntityManagerInterface $manager) {
        $manager->remove($figure);
        $manager->flush();

        $this->addFlash(
            'success',
            "La figure {$figure->getFigureName()} a bien été supprimée !"
        );

        return $this->redirectToRoute('homepage');
    }
}
