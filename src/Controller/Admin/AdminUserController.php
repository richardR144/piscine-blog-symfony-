<?php
namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('admin/users/insert', 'admin_insert_user')]
    public function insertAdmin(UserPasswordHasherInterface $passwordhasher, Request $request, EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\Response
    {

        if ($request->getMethod() === "POST") {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = new User();

            try {

                $hashedPassword = $$passwordhasher->hashPassword(
                    $user,
                    $password
                );

                $user->setEmail($email);
                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_ADMIN']);

                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('succes', 'utilisateur créé');

            } catch (\Exception $exception) {
//Eviter de renvoyer le message directement
//récupéré depuis les erreurs SQL

                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('admin/page/insert_user.html.twig');

    }
}