<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TDR;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TDRController extends Controller
{
    /**
     * @Route("/tdrs", name="index_tdrs")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:TDR:tdr.html.twig');
    }

    public function getRepository($entity = 'TDR')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/tdrs", options = { "expose" = true }, name="list_datatable_tdrs")
     */
    public function listTDRsAction()
    {
        $tdrs = $this->getRepository()->listAll();
        $datas = [];

        foreach ($tdrs as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
            $temp[] = $sample_data->getTp()->getLibelle();
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
     * @Route("/api/tdrs", options = {"expose" = true}, name="find_all_tdrs")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/add/tdr", options = { "expose" = true }, name="add_tdr")
     */
    public function addTDRAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp = $request->request->get('tp');
        $tdr = new TDR();
        $tdr->setLibelle($libelle);
        $tdr->setDescription($description);
        $tdr->setTp($this->getRepository('TP')->find($tp));

        $em = $this->getEm();
        $em->persist($tdr);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/tdr/{tdr}", options = { "expose" = true }, name="update_tdr")
     */
    public function updateTDRAction(Request $request, TDR $tdr)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp = $request->request->get('tp');

        $tdr->setLibelle($libelle);
        $tdr->setDescription($description);
        $tdr->setTp($this->getRepository('TP')->find($tp));

        $em = $this->getEm();
        $em->merge($tdr);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/tdr/{tdr}", options = { "expose" = true }, name="delete_tdr")
     */
    public function deleteTDRAction(Request $request, TDR $tdr)
    {
        $em = $this->getEm();
        $em->remove($tdr);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
