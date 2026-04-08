<?php

namespace App\Controller;

use App\Entity\Transport;
use App\Form\MarchandiseType;
use App\Form\PersonneType;
use App\Form\TransportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager,): Response
    {
        $transp = new Transport();

        $form = $this->createForm(TransportType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transp);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse');
        }
        return $this->render('home/index.html.twig', [
             'form' => $form->createView(),
        ]);
    }

    #[Route('/transport-de-marchandises', name: 'app_marchandises')]
    public function marchandises(Request $request, EntityManagerInterface $entityManager,): Response
    {
        $transp = new Transport();

        $form = $this->createForm(MarchandiseType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transp);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse');
        }
        return $this->render('home/marchandises.html.twig', [
             'form' => $form->createView(),
        ]);
    }

     #[Route('/transport-de-personnes', name: 'app_personne')]
    public function personne(Request $request, EntityManagerInterface $entityManager,): Response
    {
        $transp = new Transport();

        $form = $this->createForm(PersonneType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transp);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse');
        }
        return $this->render('home/personne.html.twig', [
             'form' => $form->createView(),
        ]);
    }

     #[Route('/reponse', name: 'app_reponse')]
    public function reponse(): Response
    {
        return $this->render('home/reponse.html.twig', [
           
        ]);
    }

     #[Route('/politique-legale', name: 'app_politique')]
    public function politique(): Response
    {
        return $this->render('home/politique.html.twig', [
           
        ]);
    }

     #[Route('/mention-legale', name: 'app_mention')]
    public function mention(): Response
    {
        return $this->render('home/mentions-legales.html.twig', [
           
        ]);
    }
}
