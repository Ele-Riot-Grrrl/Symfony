<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MeilleurcoinController
{
    /**
    /**
     * @Route("/accueil", name="accueil")
     */
    public function testAction() {
        return new Response ( 'Bienvenue sur le Meilleur Coin, mieux que le Bon Coin !');
    }

}

?>