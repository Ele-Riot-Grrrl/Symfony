<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Ad;
/**
 * Création d'une base de donnée dans laquelle on va venir ajouter des éléments pour Meilleur Coin
 * CREATION D'UNE PREFIXE
 * @Route(name="annonce_", path="/annonce")
 */
class AdController extends Controller
{
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
}
?>



