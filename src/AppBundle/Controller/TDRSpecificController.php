<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TDRSpecific;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TDRSpecificController extends Controller
{

    public function getRepository($entity = 'TDRSpecific')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/projet/{projet}/tdr_specifics_specifics", options = { "expose" = true }, name="list_tdr_specific_of_projet")
     */
    public function listProjetCCTPSpecificAction($projet)
    {
        $tdr_specifics_specifics = $this->getRepository()->listAllByProjet($projet);
        $datas = [];

        foreach ($tdr_specifics_specifics as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getService();
//            $temp[] = $sample_data->getFichier()->getNom();
//            $temp[] = $this->my_get_date($sample_data->getDateCreation());
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
     * @Route("/api/tdrs_specifics", options = {"expose" = true}, name="find_all_tdrs_specifics")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/add/tdr_specific", options = { "expose" = true }, name="add_tdr_specific")
     */
    public function addTDRSpecificAction(Request $request)
    {
        $service = $request->request->get('service');
        $date = $request->request->get('date');
        $projet = $request->request->get('projet');
        $tdr = new TDRSpecific();
        $tdr->setService($service);
        $tdr->setDate($date);
        $tdr->setProjet($this->getRepository('Projet')->find($projet));

        $em = $this->getEm();
        $em->persist($tdr);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/tdr_specific/{tdr_specific}", options = { "expose" = true }, name="update_tdr_specific")
     */
    public function updateTDRSpecificAction(Request $request, TDRSpecific $tdr_specific)
    {
        $service = $request->request->get('service');
        $date = $request->request->get('date');
        $projet = $request->request->get('projet');

        $tdr->setService($service);
        $tdr->setDate($date);
        $tdr->setProjet($this->getRepository('Projet')->find($projet));

        $em = $this->getEm();
        $em->merge($tdr_specific);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/tdr_specific/{tdr_specific}", options = { "expose" = true }, name="delete_tdr_specific")
     */
    public function deleteTDRSpecificAction(Request $request, TDRSpecific $tdr_specific)
    {
        $em = $this->getEm();
        $em->remove($tdr_specific);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
