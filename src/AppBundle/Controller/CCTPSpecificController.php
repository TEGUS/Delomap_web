<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CCTPSpecific;
use AppBundle\Form\CCTPSpecificType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CCTPSpecificController extends Controller
{
    public function getRepository($entity = 'CCTPSpecific')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/projet/{projet}/cctp_specifics_specifics", options = { "expose" = true }, name="list_cctp_specific_of_projet")
     */
    public function listProjetCCTPSpecificAction($projet)
    {
        $cctp_specifics_specifics = $this->getRepository()->listAllByProjet($projet);
        $datas = [];

        foreach ($cctp_specifics_specifics as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getService();
            if ($sample_data->getFichier()) {
                $temp[] = '
                <a href="' . $this->container->get('assets.packages')->getUrl("uploads/docs/" . $sample_data->getFichier()->getNom()) . '" title="Télécharger">' . $sample_data->getFichier()->getNom() . '</a>
                ';
            } else {
                $temp[] = '';
            }
            $temp[] = $this->my_get_date($sample_data->getDateCreation());
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
     * @Route("/api/add/cctp_specific", options = { "expose" = true }, name="add_cctp_specific")
     */
    public function addCCTPSpecificAction(Request $request)
    {
        $service = $request->request->get('service');
        $date = $request->request->get('date');
        $projet = $request->request->get('projet');

        $cctp_specific = new CCTPSpecific();

        if ($service != null && $service != '')
            $cctp_specific->setService($service);

        if ($date != null && $date != '')
            $cctp_specific->setDate($date);

        if ($projet != null && $projet != '')
            $cctp_specific->setProjet($this->getRepository('Projet')->find($projet));

        $em = $this->getEm();
        $em->persist($cctp_specific);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/cctp_specific/{cctp_specific}", options = { "expose" = true }, name="update_cctp_specific")
     */
    public function updateCCTPSpecificAction(Request $request, CCTPSpecific $cctp_specific)
    {
        $service = $request->request->get('service');
        $date = $request->request->get('date');
        $projet = $request->request->get('projet');

        if ($service != null && $service != '')
            $cctp_specific->setService($service);
        
        if ($date != null && $date != '')
            $cctp_specific->setDate($date);
        
        if ($projet != null && $projet != '')
            $cctp_specific->setProjet($this->getRepository('Projet')->find($projet));

        $em = $this->getEm();
        $em->merge($cctp_specific);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/cctp_specific/{cctp_specific}", options = {"expose" = true}, name="delete_cctp_specific")
     */
    public function deleteCCTPSpecificAction(Request $request, CCTPSpecific $cctp_specific)
    {
        $em = $this->getEm();
        $em->remove($cctp_specific);
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
