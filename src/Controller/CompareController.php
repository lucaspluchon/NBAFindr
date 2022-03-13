<?php

namespace App\Controller;

use App\Entity\Comparaison;
use App\Service\PlayerApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class CompareController extends AbstractController
{
    #[Route('/compare_search', name: 'search_compare')]
    public function index(PlayerApi $api): Response
    {

        $request = Request::createFromGlobals();
        $page = $request->query->get("page");
        if ($page == null)
            $page = 1;
        return $this->render('compare/index.html.twig', [
            'controller_name' => 'CompareController',
            'players' => $api->getAllPlayers($page)["data"],
            'page' => $page
        ]);
    }

    #[Route('/compare_history')]
    public function history(ManagerRegistry $doctrine): Response
    {
        return $this->render('compare/history.twig', [
            'controller_name' => 'CompareController',
            'comparaisons' => $doctrine->getRepository(Comparaison::class)->findAll()
        ]);
    }

    #[Route('/compare', name: 'compare')]
    public function compare(PlayerApi $api, ManagerRegistry $doctrine): Response
    {
        $request = Request::createFromGlobals();
        $player1 = $request->query->get("player1");
        $player2 = $request->query->get("player2");

        $player1Api = $api->info_fromId($player1);
        $player2Api = $api->info_fromId($player2);

        if ($player1Api && $player2Api != null)
        {
            $repo = $doctrine->getManager();
            $comparaison = new Comparaison();
            $comparaison->setDate(new \DateTime('@'.strtotime('now')));
            $comparaison->setPlayer1($player1Api["name"]);
            $comparaison->setPlayer2($player2Api["name"]);
            $repo->persist($comparaison);
            $repo->flush();
        }

        return $this->render('compare/compare.twig', [
            'controller_name' => 'CompareController',
            'player1' => $player1Api,
            'player2' => $player2Api
        ]);
    }
}
