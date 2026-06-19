<?php

namespace App\Controller;

use App\Entity\Transport;
use App\Form\MarchandiseType;
use App\Form\PersonneType;
use App\Form\TransportType;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
       
        $nombreAddProspects    = $entityManager->getRepository(Transport::class)->count([]);
        $nombreProspects = $nombreAddProspects + 4500;
        $nombreTarifications = (int) round($nombreProspects * 0.90);
        $nombreSouscriptions = (int) round($nombreProspects * 0.62);

        $transp = new Transport();

        $form = $this->createForm(TransportType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getOrigin()->getName() . ': ' . $error->getMessage();
            }
            $logger->error('Formulaire invalide', ['errors' => $errors]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // 1. Sauvegarde en base
                $entityManager->persist($transp);
                $entityManager->flush();

                // 2. Préparation des données
                $telephone = $this->formatPhoneNumber($transp->getTele());

                $data = [

                    'nom'           => $transp->getNom() ?? '',
                    'prenom'        => $transp->getPrenom() ?? '',
                    'phone'         => $telephone ?? '',
                    'email'         => $transp->getEmail() ?? '',
                    'raisonSociale' => $transp->getRaison(), 
                    'lastAssure'    => $transp->getAncienne(),
                    'motifResil'    => $transp->getMotif(),
                     
                    
                    
                      'typeProspect' => "2",
                       'source' => "3",
                       'activites' => "1",
                       'url' => "11",
                       'product'       => '/api/products/1',  //produit >3t5
                ];

                // 3. Envoi via cURL
                $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                // 4. Vérification
                if ($httpCode >= 200 && $httpCode < 300) {
                    $this->addFlash('success', 'Votre demande a été enregistrée et transmise avec succès !');
                    $logger->info('Données envoyées à l\'API avec succès', [
                        'status_code' => $httpCode,
                        'response' => $response
                    ]);
                } else {
                    $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                    $logger->error('Erreur lors de l\'envoi à l\'API', [
                        'status_code' => $httpCode,
                        'error' => $curlError,
                        'response' => $response
                    ]);
                }

                return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
                $logger->error('Erreur lors de l\'enregistrement du formulaire transp', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            }
        return $this->render('home/index.html.twig', [
             'form'                => $form->createView(),
             'nombreProspects'     => $nombreProspects,
             'nombreTarifications' => $nombreTarifications,
             'nombreSouscriptions' => $nombreSouscriptions,
        ]);
    }

    #[Route('/assurance-transport-marchandise', name: 'app_marchandises')]
    public function marchandises(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
         $transp = new Transport();

        $form = $this->createForm(TransportType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getOrigin()->getName() . ': ' . $error->getMessage();
            }
            $logger->error('Formulaire invalide', ['errors' => $errors]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // 1. Sauvegarde en base
                $entityManager->persist($transp);
                $entityManager->flush();

                // 2. Préparation des données
                $telephone = $this->formatPhoneNumber($transp->getTele());

                $data = [

                    'nom'           => $transp->getNom() ?? '',
                    'prenom'        => $transp->getPrenom() ?? '',
                    'phone'         => $telephone ?? '',
                    'email'         => $transp->getEmail() ?? '',
                    'raisonSociale' => $transp->getRaison(), 
                    'lastAssure'    => $transp->getAncienne(),
                    'motifResil'    => $transp->getMotif(),
                     
                    
                    
                      'typeProspect' => "2",
                       'source' => "3",
                       'activites' => "1",
                       'url' => "11",
                       'product'       => '/api/products/1',  //produit >3t5
                ];

                // 3. Envoi via cURL
                $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                // 4. Vérification
                if ($httpCode >= 200 && $httpCode < 300) {
                    $this->addFlash('success', 'Votre demande a été enregistrée et transmise avec succès !');
                    $logger->info('Données envoyées à l\'API avec succès', [
                        'status_code' => $httpCode,
                        'response' => $response
                    ]);
                } else {
                    $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                    $logger->error('Erreur lors de l\'envoi à l\'API', [
                        'status_code' => $httpCode,
                        'error' => $curlError,
                        'response' => $response
                    ]);
                }

                return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
                $logger->error('Erreur lors de l\'enregistrement du formulaire transp', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            }
        return $this->render('home/marchandises.html.twig', [
             'form' => $form->createView(),
        ]);
    }

    #[Route('/assurance-vehicule-transport-de-marchandises', name: 'app_vehicule_marchandises')]
    public function vehiculeMarchandises(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $transp = new Transport();
        $form = $this->createForm(VehiculeType::class, $transp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getOrigin()->getName() . ': ' . $error->getMessage();
            }
            $logger->error('Formulaire invalide', ['errors' => $errors]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($transp);
                $entityManager->flush();

                $telephone = $this->formatPhoneNumber($transp->getTele());
                $data = [
                    'nom'           => $transp->getNom() ?? '',
                    'prenom'        => $transp->getPrenom() ?? '',
                    'phone'         => $telephone ?? '',
                    'email'         => $transp->getEmail() ?? '',
                    'raisonSociale' => $transp->getRaison(),
                    'lastAssure'    => $transp->getAncienne(),
                    'motifResil'    => $transp->getMotif(),
                    'typeProspect'  => "2",
                    'source'        => "3",
                    'activites'     => "1",
                    'url'           => "12",
                    'product'       => '/api/products/1',
                ];

                $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $response  = curl_exec($ch);
                $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($httpCode >= 200 && $httpCode < 300) {
                    $this->addFlash('success', 'Votre demande a été enregistrée et transmise avec succès !');
                    $logger->info('Données envoyées à l\'API avec succès', ['status_code' => $httpCode, 'response' => $response]);
                } else {
                    $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                    $logger->error('Erreur lors de l\'envoi à l\'API', ['status_code' => $httpCode, 'error' => $curlError, 'response' => $response]);
                }

                return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
                $logger->error('Erreur formulaire vehicule marchandises', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }

        return $this->render('home/assurance-vehicule-transport-marchandises.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/assurance-transport-de-marchandise/assurance-marchandise-transportee', name: 'app_assurance_marchandise_transportee')]
    public function assuranceMarchandiseTransportee(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $transp = new Transport();
        $form = $this->createForm(TransportType::class, $transp, ['show_assurer' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getOrigin()->getName() . ': ' . $error->getMessage();
            }
            $logger->error('Formulaire invalide', ['errors' => $errors]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($transp);
                $entityManager->flush();

                $telephone = $this->formatPhoneNumber($transp->getTele());
                $data = [
                    'nom'           => $transp->getNom() ?? '',
                    'prenom'        => $transp->getPrenom() ?? '',
                    'phone'         => $telephone ?? '',
                    'email'         => $transp->getEmail() ?? '',
                    'raisonSociale' => $transp->getRaison(),
                    'lastAssure'    => $transp->getAncienne(),
                    'motifResil'    => $transp->getMotif(),
                    'typeProspect'  => "2",
                    'source'        => "3",
                    'activites'     => "1",
                    'url'           => "12",
                    'product'       => '/api/products/1',
                ];

                $ch = curl_init('https://aksam.azurewebsites.net/api/prospects');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $response  = curl_exec($ch);
                $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($httpCode >= 200 && $httpCode < 300) {
                    $this->addFlash('success', 'Votre demande a été enregistrée et transmise avec succès !');
                    $logger->info('Données envoyées à l\'API avec succès', ['status_code' => $httpCode, 'response' => $response]);
                } else {
                    $this->addFlash('warning', 'Votre demande a été enregistrée, mais un problème est survenu lors de la transmission.');
                    $logger->error('Erreur lors de l\'envoi à l\'API', ['status_code' => $httpCode, 'error' => $curlError, 'response' => $response]);
                }

                return $this->redirectToRoute('app_reponse', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande.');
                $logger->error('Erreur formulaire vehicule marchandises', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        }

        return $this->render('home/assurance-marchandise-transportee.html.twig', [
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

     /**
     * Formatage du numéro de téléphone
     */
    private function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }
        if (str_starts_with($phone, '0')) {
            return '+33' . substr($phone, 1);
        }
        return $phone;
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
