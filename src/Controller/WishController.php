<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\NewWishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/wishes', name: 'wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAll();


        return $this->render('wish/index.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/wishes/{id}', name: 'wish_show')]
    public function show(Wish $wish): Response
    {

        return $this->render('wish/show.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/wish/create', name: 'app_wish_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(NewWishType::class);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish = $wishForm->getData();
            $wish->setPublished(1);
            $entityManager->persist($wish);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render("wish/create.html.twig", [
            "wishForm" => $wishForm->createView()
        ]);
    }
}

