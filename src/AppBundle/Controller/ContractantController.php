<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contractant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContractantController extends Controller
{
    /**
     * @Route("/administrations", name="index_list_administrations")
     */
    public function indexAdministrationsAction()
    {
        return $this->render('AppBundle:Contractant:administrations.html.twig');
    }

    /**
     * @Route("/acteurs", name="index_list_acteurs")
     */
    public function indexActeursAction()
    {
        return $this->render('AppBundle:Contractant:acteurs.html.twig');
    }

    public function getRepository($entity = 'Contractant')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/acteurs", options = { "expose" = true }, name="list_acteur")
     */
    public function listActeursAction()
    {
        $contractants = $this->getRepository()->listAll();
        $datas = [];

        foreach ($contractants as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getEmail();
            $temp[] = $sample_data->getNom();
            $temp[] = $sample_data->getTel();
            $temp[] = '
                <a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
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
     * @Route("/api/administrations", options = { "expose" = true }, name="list_administration")
     */
    public function listAdministrationsAction()
    {
        $contractants = $this->getRepository()->listAll('administration');
        $datas = [];

        foreach ($contractants as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getEmail();
            $temp[] = $sample_data->getNom();
            $temp[] = $sample_data->getTel();
            $temp[] = '
                <a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
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
     * @Route("/api/add/contractant", options = { "expose" = true }, name="add_contractant")
     */
    public function addContractantAction(Request $request)
    {
        $email = $request->request->get('email');
        $nom = $request->request->get('nom');
        $tel = $request->request->get('tel');

        $contractant = new Contractant();
        $contractant->setEmail($email);
        $contractant->setNom($nom);
        $contractant->setTel($tel);

        $em = $this->getEm();
        $em->persist($contractant);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/contractant/{contractant}", options = { "expose" = true }, name="update_contractant")
     */
    public function updateContractantAction(Request $request, Contractant $contractant)
    {
        /** $id = $request->request->get('id');*/
        $email = $request->request->get('email');
        $nom = $request->request->get('nom');
        $tel = $request->request->get('tel');

        /**$contractant->setId($id);*/
        $contractant->setEmail($email);
        $contractant->setNom($nom);
        $contractant->setTel($tel);
        //$contractant->setTp($this->getRepository('TP')->find($tp));*/

        $em = $this->getEm();
        $em->merge($contractant);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/contractant/{contractant}", options = { "expose" = true }, name="delete_contractant")
     */
    public function deleteContractantAction(Request $request, Contractant $contractant)
    {
        $em = $this->getEm();
        $em->remove($contractant);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
