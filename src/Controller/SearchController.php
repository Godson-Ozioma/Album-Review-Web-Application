<?php

namespace App\Controller;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search')]
    public function search(EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {
//        $searchResult = $entityManager->getRepository(Album::class)->findAll();
        $searchResult = null;

        $searchInput = '';
        //if the submit button was pressed
        if (isset($_POST['searchButton'])){
            $searchInput = $_POST['searchInput'];
//            echo 'input: ' . $searchInput;
            $searchResult = $entityManager->getRepository(Album::class)->findAll();
            $searchResult = $entityManager->getRepository(Album::class)->findSearchedAlbums($searchInput, $entityManager);

        }

        return $this->render('search.html.twig', array('listAlbum' => $searchResult));


    }

    public function findSearchedAlbums($input, EntityManagerInterface $entityManager) : array{
        $query = $entityManager->createQuery(
            'SELECT album FROM App\Entity\Album album WHERE album.albumName = :input'
        )->setParameter('input', $input);

        return $query->getResult();
    }



}