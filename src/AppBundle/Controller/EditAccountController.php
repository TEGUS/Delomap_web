<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\UserType;
use UserBundle\Form\UserWithPictureOnlyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EditAccountController extends Controller
{
    public function getRepository($entity = 'User')
    {
        return $this->getEm()->getRepository("UserBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    //    Retoune User
    public function getUser()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @Route("/update/user/add/picture", name="update_user_add_picture")
     */
    public function updateUserAddPictureAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserWithPictureOnlyType::class, $user);

        $em = $this->getEm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->merge($user);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        $parameters = array(
            'form' =>  $form->createView(),
        );

        return $this->render('UserBundle:Profile:edit_picture.html.twig', $parameters);
    }

    /**
     * @Route("/update/user/info", name="update_user_info")
     */
    public function updateUserInfoAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $em = $this->getEm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->merge($user);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        $parameters = array(
            'form' =>  $form->createView(),
        );

        return $this->render('UserBundle:Profile:edit_info.html.twig', $parameters);
    }
}
