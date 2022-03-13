<?php

namespace App\Controller;

use App\Service\PlayerApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/', name: 'app_search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    #[Route('/player', name: 'app_search_player')]
    public function search_player(PlayerApi $api): Response
    {
        $request = Request::createFromGlobals();
        $name = $request->query->get("name");

        return $this->render('search/player.html.twig', [
            'controller_name' => 'SearchController',
            'player_infos' => $api->info_fromName($name)
        ]);
    }

}
