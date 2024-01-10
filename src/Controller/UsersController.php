<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends InitializableController
{
    /**
     * @Route("/admin/users/index", name="users_index")
     */
    public function index(): Response
    {
        $users = $this->getRepository(User::class)->createQueryBuilder('u')->
            orderBy('u.active','DESC')
            ->getQuery()->getResult();

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/admin/users/down/{id}", name="users_down")
     */
    public function down(User $user): Response
    {
        $roles=array();
        $user->setRoles($roles);
        $this->manager->persist($user);
        $this->manager->flush();

        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/admin/users/up/{id}", name="users_up")
     */
    public function up(Request $request,User $user): Response
    {
        $roles=array('ROLE_ADMIN');
        $user->setRoles($roles);
        $this->manager->persist($user);
        $this->manager->flush();

        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/admin/users/edit/{id}", name="users_edit")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/user.html.twig',array('form'=>$form->createView(),'user'=>$user));
    }

    /**
     * @Route("/admin/users/delete/{id}", name="users_delete")
     */
    public function delete(User $user): Response
    {
        if ($user->getRules()->count()>0) {
            $user->setActive(0);
        }
        else {
            $this->manager->remove($user);
        }

        $this->manager->flush();
        return $this->redirectToRoute('users_index');
    }
}
