<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/allUsers', name: 'list_users')]
    public function allUsers(EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {

        // delete user
        if (isset($_GET['deleteUserId'])){
            $userId = $_GET['deleteUserId'];
            $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $userId));
            $entityManager->remove($user);

            $entityManager->flush();
        }
        // make admin

        if (isset($_GET['makeAdminId'])){
            $userId = $_GET['makeAdminId'];
            $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $userId));
            $roles = ["ROLE_ADMIN"];
            $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
        }


        // make super admin
        if (isset($_GET['makeSuperAdminId'])){
            $userId = $_GET['makeSuperAdminId'];
            $user = $entityManager->getRepository(User::class)->findOneBy(array('id' => $userId));
            $roles = ["ROLE_SUPER_ADMIN"];
            $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        $listUsers = $entityManager->getRepository(User::class)->findAll();
        return $this->render('allUsers.html.twig', array('listUsers' => $listUsers));
    }

}