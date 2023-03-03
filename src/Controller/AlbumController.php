<?php

namespace App\Controller;

use App\Entity\Album;

use App\Form\AlbumFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;
class AlbumController extends AbstractController
{
    #[Route('/show')]
    public function album(Environment $twig, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $album = new Album();

        $form = $this->createForm(AlbumFormType::class, $album);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //image

            $file = $form->get('image')->getData(); // get the image to be uploaded
//            var_dump($file);

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
//            echo "filename: " . $originalFilename . "." .$file->guessExtension();
//            echo "new filename: " . $newFileName;

            // move the image to the preferred dir
            try {
                $file->move($this->getParameter('album_pictures_dir'), $newFileName);

            }catch (FileException $e){
                echo 'error uploading file';
            }

            // update the album
            $album = $form->getData();
            $album->setAlbumPicture($newFileName);
//            echo "hi";
//            $album->setUserId($this->getUser()->getId());
            $album->setUser($this->getUser());

//            var_dump($album);



            $entityManager->persist($album);
//            var_dump($album);
            $entityManager->flush();

            $album = $doctrine->getRepository(Album::class)->findAll();
            return $this->render('album.html.twig', array('listAlbum' => $album));
//            return new Response('success');
        }

        return new Response($twig->render('albumForm.html.twig', [
            'album_form' => $form->createView()
        ]));

    }

    #[Route('/albums','list_album')]
    public function listAlbum(ManagerRegistry $doctrine): Response
    {
        $listAlbum = $doctrine->getRepository(Album::class)->findAll();
        return $this->render('album.html.twig', array('listAlbum' => $listAlbum));

    }

    public function addAlbum(ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();

        $albumToAdd = new Album();

        $albumToAdd->setUser();
    }


}