<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Review;
use App\Form\ReviewFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/creator', name: 'create_review')]

    public function createReview(Request $request, EntityManagerInterface $entityManager): ?Response
    {
        $review = new Review();

        $form = $this->createForm(ReviewFormType::class, $review);

        $form->handleRequest($request);
        $albumID = null;
        if(isset($_GET["id"])){
            $albumID = $_GET["id"];

            if ($form->isSubmitted() && $form->isValid()){ // create album

                // check for duplicate
                $album = $entityManager->getRepository(Album::class)->findOneBy(array('id' => $albumID));
                $existingReview = $entityManager->getRepository(Review::class)->findOneBy(array('album' => $albumID, 'user' => $this->getUser()));

                if($existingReview == null){
//                    echo "no duplicate";
                    $review = $form->getData();

                    $review->setUser($this->getUser());
                    $album = $entityManager->getRepository(Album::class)->findOneBy(array('id' => $albumID)); // get the album from the db
                    $review->setAlbum($album);

//                    echo "album id: " . $review->getAlbum()->getId();

                    $entityManager->persist($review);
                    $entityManager->flush();
                }else if(isset($_GET["editId"])){ //edit album
//                    echo "editing review";
                    $newReview = $form->getData();
                    $review = $entityManager->getRepository(Review::class)->find($_GET["editId"]);

                    $review->setComment($newReview->getComment());
                    $review->setRating($newReview->getRating());

//                    echo "album id: " . $review->getAlbum()->getId();

                    $entityManager->persist($review);
                    $entityManager->flush();


                }
//                else{
//                    // duplicate found
//                    echo "duplicate found!";
//                    return null;
//
//                }



                $listReview = $entityManager->getRepository(Review::class)->findBy(array('album' => $album));
                return $this->render('review/reviews.html.twig', array('listReview' => $listReview));
            }

            return $this->render('review/reviewForm.html.twig', [
                'review_form' => $form->createView(),
            ]);

        }

        return $this->render('loginError.html.twig');

    }



    #[Route('/reviews', name: 'list_reviews')]
    public function listReview(EntityManagerInterface $entityManager): Response
    {
        //delete a review
        if (isset($_GET["deleteId"])){
            $reviewId = $_GET["deleteId"];
            $review = $entityManager->getRepository(Review::class)->findOneBy(array('id' => $reviewId));
            $entityManager->remove($review);

            $entityManager->flush();

        }

        //list reviews
        $albumId = null;
        if(isset($_GET["albumId"])){
            $albumId = $_GET["albumId"];

            $album = $entityManager->getRepository(Album::class)->findoneBy(array('id' => $albumId));
            $listReview = $entityManager->getRepository(Review::class)->findBy(array('album' => $album));
        }




        return $this->render('review/reviews.html.twig', array('listReview' => $listReview));
    }

}
