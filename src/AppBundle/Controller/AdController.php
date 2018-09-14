<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\AdType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\Category;

use AppBundle\Entity\Ad;
/**
 * Création d'une base de donnée dans laquelle on va venir ajouter des éléments pour Meilleur Coin
 * CREATION D'UNE PREFIXE
 * @Route(name="annonce_", path="/annonce")
 */
class AdController extends Controller
{
    /**
     * @Route(path="/accueil", name="accueil")
     */
    public function testAction() {

        return $this->render('Meilleur_coin/accueil.html.twig');
    }

    /**
     * @Route(name="new", path="/new")
     */

    public function newAction(Request $request) {

        $ad = new Ad();

        //je gagne une ligne de code car le formBuilder est au niveau de CountryType
        $form = $this->createForm(AdType::class, $ad);

        $form = $this->createFormBuilder($ad)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, array(
                'attr' => array('class' => 'tinymce'),
            ))
            ->add('city', TextType::class)
            ->add('zip', IntegerType::class)
            ->add('price', MoneyType::class)
            //->add('dateCreated', DateType::class )

                        ->add('category', EntityType::class, [
                            'class' => Category::class,
                            'choice_label' => 'name',
                            'placeholder' => '-- Choisir une catégorie --',
                        ])
                        //On fait une checkbox afin que l'user puisse cocher des villes à ajouter
                        //On peut avoir besoin d'avoir des champs dnas le formulaire qui ne soient pas mappés
                        ->add('terms', CheckboxType::class, [
                            'label' =>'Je sauvegarde ma catégorie en base de données',
                            'mapped' =>false,
                            'required'=>false,
                        ])

            ->getForm();

        //On dit à notre form de prendre toutes les infos du formulaire via request
        $form->handleRequest($request);

        //On teste que le formulaire est bien envoyé
        if ($form->isSubmitted()&& $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ad -> setDateCreated(new \ DateTime());//Sert à créer une date qui se met au bon jour automatiquement
            $entityManager->persist($ad);
            $entityManager->flush();
            return $this -> redirectToRoute("annonce_list");
        }
        //dump($city);

        return $this->render('Meilleur_coin/deposer_ad.html.twig', [
            'form' => $form->createView()
        ]);

        //return new Response('<html><body></body></html>')
    }


    /**
     * @Route(name="add", path="/add/")
     */
    public function addAction() {
        $entityManager = $this->getDoctrine()->getManager();

        $ad = new Ad();
        $ad -> setTitle('Lampe');
        $ad -> setDescription('Lampe moche et usée');
        $ad -> setCity ('Montauban');
        $ad -> setZip(82000);
        $ad -> setPrice(10.50);
        $ad -> setDateCreated(new \DateTime());

        $ad = new Ad();
        $ad -> setTitle('Robe');
        $ad -> setDescription('Robe tâchée et trouée');
        $ad -> setCity ('Toulouse');
        $ad -> setZip(31000);
        $ad -> setPrice(20.50);
        $ad -> setDateCreated(new \DateTime());

        $ad = new Ad();
        $ad -> setTitle('jeu vidéo Castlevania');
        $ad -> setDescription('Le meilleur jeu du monde !');
        $ad -> setCity ('Paris');
        $ad -> setZip(75000);
        $ad -> setPrice(15.50);
        $ad -> setDateCreated(new \DateTime());

        //Lors de la création d'un nouvel objet, je dois venir le persister
        $entityManager->persist($ad);
        //Pour sauvegarder en base de données, je dois faire un flush
        $entityManager->flush();

        return new Response("Ajouter une annonce");
    }




    /**
     * @Route(name="remove", path="/remove/{id}")
     */

    public function removeAction($id) {
        $ad = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
            ->find($id)
        ;
        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException();
        }
//On supprime $cad de la bdd
        $this
            ->getDoctrine()
            ->getManager()
            ->remove($ad)
        ;
//on flush pour que ça marche
        $this
            ->getDoctrine()
            ->getManager()
            ->flush()
        ;
        dump($ad);
        return new Response('<html><body></body></html>');
    }

    /**
     * @route(name="list", path="/list")
     */
  public function listAction(RouterInterface $router)
    {
        // J'ai déplacé le DQL directement dans mon AdRepository
        $ads = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
            //->findAll()
//On veut afficher uniquement les prix inférieurs à 10€ (ne fonctionne pas forcément !) :
        ->findAdsWithPriceLowerThan10()
        ;
        return $this->render(
            'Meilleur_coin/list.html.twig',
            ['ads' => $ads]
        );
    }

    /**
     * creer un repository
     * @Route(name="edit", path="/edit")
     */
    public function editAction()
    {
        $adRepository = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
        ;


        $voiture = $adRepository->findOneBy([
            'title' => 'moto' //should be the name in country table
        ]);

        $voiture->setName('newName : Caravane');

        //j'ai pas besoin de faire un persist car c'est une MAJ
        // par contre j'ai besoin de faire un flush pour execurer le SQL en BDD
        $this
            ->getDoctrine()
            ->getManager()
            ->flush()
        ;


        return new Response('<html><body></body></html>');
    }

    /**
     * @Route(name="detail", path="/detail/{id}")
     */

    public function detailAction($id)
    {
        // Aller chercher l'annonce sur la base de donnée ( utiliser la méthode find($id) sur le repository

        // Maintenant, tu vas avoir un $ad qui sera une annonce

        // Ensuite, tu vas envoyer le $ad à un template twig qui va afficher le titre, le prix... etc
        // Tu vas créer un fichier detail.html.twig qui affichera ad


        // Un fois que tu auras cette route, quand tu feras /annonce/detail/2 , tu afficeras les détails de l'annonce 2

        // Quand tu voudras faire un lien Twig vers cette route, tu devras faire  {{ path('annonce_detail', {'id': 2}) }} => ca va générer /annonce/detail/2

        $ad = $this
            ->getDoctrine()
            ->getRepository(Ad::class)
            ->find($id);

        return $this->render('Meilleur_coin/detail.html.twig', [
            'ad' => $ad
        ]);
    }
}


?>



