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
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
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
     * @Route("/show/{slug}/{page}", name="figure_show", requirements={"page": "\d+"})
     *
     * @return Response
     */
    public function show(Figure $figure, CommentaireRepository $repo1, DocumentRepository $repo2, Request $request, EntityManagerInterface $manager, $page = 1) {
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
    
        $limit = 10;
        $start = $page * $limit - $limit;
        $commentaires = $repo1->findBy(['figure' => $figure->getId()], ['id' => 'desc'], $limit, $start);

        $total = count($figure->getCommentaires());
        if($total == 0) {
            $pages = 0;
        } else {
            $pages = ceil($total/$limit);
        }

        $document = $repo2->findOneBy(['figurePicture' => $figure->getId()]);

        // Si le figurePicture n'existe pas alors le document est null
        if($document == NULL) {
            // Si le figureVideo n'existe pas alors le document est null
            $document = $repo2->findOneBy(['figureVideo' => $figure->getId()]);
        };

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'document' => $document,
            'form' =>$form->createView(),
            'pages' => $pages,
            'page' => $page,
            'commentaires' => $commentaires 
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
     * @Security("(is_granted('ROLE_USER') and user === figure.getAuthorId()) or (is_granted('ROLE_ADMIN'))", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function modif(Figure $figure, Request $request, EntityManagerInterface $manager, FileUploader $fileUploader) {
        $figure->setModifDate(new \DateTime('now'));
        $figure->setImageDefaut('');

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
     * Permet de supprimer un trick
     * 
     * @Route("/supp/{slug}", name="figure_supp")
     * @Security("(is_granted('ROLE_USER') and user === figure.getAuthorId()) or (is_granted('ROLE_ADMIN'))", message="Cette figure ne vous appartient pas, vous ne pouvez pas la supprimer")
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

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/supp-commentaire/{id}/{slug}", name="commentaire_supp")
     * @Security("is_granted('ROLE_USER')", message="Ce commentaire ne vous appartient pas, vous ne pouvez pas le supprimer")
     *
     * @param Commentaire $commentaire
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function supp_commentaire(Commentaire $commentaire, Request $request, EntityManagerInterface $manager, $slug) {
        $manager->remove($commentaire);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire {$commentaire->getId()} a bien été supprimée !"
        );

        return $this->redirectToRoute('figure_show', [
            'slug' => $slug,
            'page' => 1
        ]);
    }

    /**
     * Permet de modifier une imageDefaut
     * 
     * @Route("/modif-imageDefaut/{slug}", name="imageDefaut_modif")
     * @Security("(is_granted('ROLE_USER') and user === figure.getAuthorId()) or (is_granted('ROLE_ADMIN'))", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function modif_imageDefaut(Figure $figure, Request $request, EntityManagerInterface $manager, FileUploader $fileUploader) {
        $figure->setModifDate(new \DateTime('now'));
        //dump($figure->getImageDefaut());
        //die;
        $figure->setImageDefaut('');

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
     * Permet de supprimer une imageDefaut
     * 
     * @Route("/supp-imageDefaut/{slug}", name="imageDefaut_supp")
     * @Security("(is_granted('ROLE_USER') and user === figure.getAuthorId()) or (is_granted('ROLE_ADMIN'))", message="Cette image ne vous appartient pas, vous ne pouvez pas la supprimer")
     *
     * @param Figure $figure
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function supp_imageDefaut(Figure $figure, Request $request, EntityManagerInterface $manager) {
        $figure->setImageDefaut('');
        $manager->persist($figure);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'image par defaut {$figure->getFigureName()} a bien été supprimée !"
        );

        return $this->redirectToRoute('homepage');
    }
}
