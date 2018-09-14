<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

use AppBundle\Entity\Category;


/**
 * @Route(name="category_", path="/category")
 */
class CategoryController extends Controller
{

    /**
     * @route(name="list", path="/list")
     */
    public function listAction(RouterInterface $router)
    {
        // J'ai déplacé le DQL directement dans mon AdRepository
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
//On veut afficher uniquement les prix inférieurs à 10€ (ne fonctionne pas forcément !) :
        //->findAdsWithPriceLowerThan10();
        return $this->render(
            'Meilleur_coin/category_list.html.twig',
            ['categories' => $categories]
        );

    }

}