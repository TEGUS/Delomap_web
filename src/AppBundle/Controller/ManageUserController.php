<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Admin;
use UserBundle\Entity\Agent;
use UserBundle\Entity\User;

class ManageUserController extends Controller
{
    public function getRepository($entity = 'User')
    {
        return $this->getEm()->getRepository("UserBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/manage/user", name="index_manage_user")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:manage-user:user.html.twig');
    }

    /**
     * @Route("/api/datatable/users", options = { "expose" = true }, name="list_datatable_users")
     */
    public function listDatatableUsersAction()
    {
        $users = $this->getRepository()->listAll();
        $datas = [];

        foreach ($users as $sample_data) {
            $template_enabled = $sample_data->isEnabled() ?
                '<a class="setDissabled btn btn-success" title="Désactiver">Oui</a>' :
                '<a class="setEnabled btn btn-danger" title="Activer">Non</a>';

            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getUsername();
            $temp[] = $sample_data->getEmail();
            $temp[] = $template_enabled;
            $temp[] = $this->my_get_date($sample_data->getDateCreation());
            $temp[] = $this->my_get_date($sample_data->getLastLogin());
            $temp[] = $sample_data->getRoles();

            $temp[] = '
                <a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>
                <!--<a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
                <span class="space-button"></span>-->
                <a href="#" class="profil" title="Profil">Profil</a>
            ';

            $datas[] = $temp;
        }

        return new JsonResponse([
            "draw" => 1,
            "recordsTotal" => count($datas),
            "recordsFiltered" => count($datas),
            "data" => $datas
        ]);
    }

    /**
     * @Route("/api/add/user", options = {"expose" = true}, name="add_user")
     */
    public function addTPAction(Request $request)
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $type = $request->request->get('type');
        $enabled = $request->request->get('enabled');

//        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
//        $userManager = $this->container->get('pugx_user_manager');
//        $user = new $userManager->createUser();

        $user = null;
        if ($type == 'agent') {
            $user = new Agent();
//            $discriminator->setClass('UserBundle\Entity\Agent');
        } elseif ($type == 'admin') {
            $user = new Admin();
//            $discriminator->setClass('UserBundle\Entity\Admin');
        }

        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEmail($email);
        $user->setEnabled($enabled);

//        $userManager->updateUser($user);
        $em = $this->getEm();
        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/enableOrDisable/user/{user}/bool/{bool}", options = { "expose" = true }, name="enable_or_disable_user")
     */
    public function enableOrDisableUserAction(Request $request, User $user, $bool)
    {
        $user->setEnabled($bool);

        $em = $this->getEm();
        $em->merge($user);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    private function my_get_date($date)
    {
        return $date === null ? null : $date->format('Y-m-d');
    }
}
