<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();
        $users = $pdo->getRepository(User::class)->findAll();

        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($user);
            $pdo->flush();
        }

        return $this->render('user/index.html.twig',[
            'users' => $users,
            'add_user_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user")
     * */
    public function edit(User $user,Request $request)
    {
        if ($user != null) {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($user);
            $pdo->flush();
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form_user_edit' => $form->createView()
        ]);
        } else {
        return $this->redirectToRoute('users');
        }
    }
}
