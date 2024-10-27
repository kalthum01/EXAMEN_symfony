<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TheatrePlay;
use App\Form\TheatrType;
use App\Repository\TheatrePlayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class TheatreController extends AbstractController
{
    #[Route('/displayplay', name: 'app_displayplay')]
    public function DisplayPlay(TheatrePlayRepository $tr): Response
    {
        $list = $tr->findAll();
        return $this->render(
            'theatre/list.html.twig',
            [
                "list" => $list
            ]
        );
    }
    #[Route('/displayAdd', name: 'Add_Show')]
    public function DisplayAdd(ManagerRegistry $doctrine, Request $request): Response
    {
        $tr = new TheatrePlay();
        $form = $this->createForm(TheatrType::class, $tr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager(); // appel entity manager
            $em->persist($tr); // insert into
            $em->flush(); // d'envoyer tout ce qui a été persisté avant à la base de données
            return $this->redirectToRoute('app_displayplay');
        }
        return $this->render('theatre/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/Update/{id}', name: 'th_update')]
    public function update(ManagerRegistry $doctrine, Request $request, $id, TheatrePlayRepository $tr): response
    {
        $theatre = $tr->find($id);
        $form = $this->createForm(TheatrType::class, $theatre); // creation formulaire à partir de classe authortype
        $form->handleRequest($request); //traiter les données recus 
        if ($form->isSubmitted()) //verifier  form envoyer valide 
        {
            $em = $doctrine->getManager(); // appel entity manager
            $em->flush(); // d'envoyer tout ce qui a été persisté avant à la base de données
            return $this->redirectToRoute('app_displayplay');
        }
        return $this->render('theatre/update.html.twig', ['form' => $form->createView()]);
    }
    #[route('/delete/{id}', name: 'app_delete')]
    public function delete(TheatrePlayRepository $tr, int $id, EntityManagerInterface $entityManager): Response
    {
        $trr = $tr->find($id);
        $entityManager->remove($trr);
        $entityManager->flush();
        return $this->redirectToRoute('app_displayplay');
    }
}
