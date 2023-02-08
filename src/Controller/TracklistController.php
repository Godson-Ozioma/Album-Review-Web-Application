<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Review;
use App\Entity\Tracklist;
use App\Form\ReviewFormType;
use App\Form\TrackFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TracklistController extends AbstractController
{
    #[Route('/tracklist', name: 'app_tracklist')]
    public function tracks(EntityManagerInterface $entityManager): Response
    {


        $albumId =null;
        $album = null;

        //delete a track
        if (isset($_GET["deleteTrackId"])){
            $trackId = $_GET["deleteTrackId"];
            $track = $entityManager->getRepository(Tracklist::class)->findOneBy(array('id' => $trackId));
            $albumId = $track->getAlbum()->getId();
//            echo 'albumID' . $albumId;
            $album = $entityManager->getRepository(Album::class)->findOneBy(array('id' => $albumId));
            $entityManager->remove($track);

            $entityManager->flush();

        }

        if(isset($_GET["viewTrackId"])){
            $albumId = $_GET["viewTrackId"];

            $album = $entityManager->getRepository(Album::class)->findoneBy(array('id' => $albumId));

        }

        $listTracks = $entityManager->getRepository(Tracklist::class)->findBy(array('album' => $album));
        return $this->render('tracklist/tracklist.html.twig', array('listTracks' => $listTracks, 'album' => $album));
    }

    #[Route('/tracking', name: 'add_tracks')]
    public function createTrack(EntityManagerInterface $entityManager, Request $request): Response
    {
        $track = new Tracklist();

        $form = $this->createForm(TrackFormType::class, $track);
        $form->handleRequest($request);


        if (isset($_GET['createTrackId'])){
            $albumId = $_GET['createTrackId'];




            if ($form->isSubmitted() && $form->isValid()){
//            if (isset($_GET['createTrackId'])){

                $album = $entityManager->getRepository(Album::class)->findOneBy(array('id'=>$albumId));

                $track = $form->getData();
//                $albumId = $_GET['createTrackId'];

//                $album = $entityManager->getRepository(Album::class)->findOneBy(array('id'=>$albumId));
                $track->setAlbum($album);

//                echo 'track'.$track->getTrackName();

                $entityManager->persist($track);
                $entityManager->flush();

                $listTracks = $entityManager->getRepository(Tracklist::class)->findBy(array('album' => $album));
                return $this->render('tracklist/tracklist.html.twig', array('listTracks' => $listTracks, 'album' => $album));
            }
//            echo 'ok' . $form->isSubmitted();

        }

//        return $this->render('Reusable Component/customModal.html.twig', array('tracklist_form' => $form->createView()));
        return $this->render('tracklist/createTrack.html.twig', array('tracklist_form' => $form->createView()));
    }
}
