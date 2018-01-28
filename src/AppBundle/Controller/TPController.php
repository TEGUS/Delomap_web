<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TPController extends Controller
{
    /**
     * @Route("/home/tps", name="index_tps")
     */
    public function indexTpsAction()
    {
        return $this->render('AppBundle:TP:tp.html.twig');
    }


    public function getRepository() {
        return $this->getDoctrine()->getManager()->getRepository("AppBundle:TP");
    }

    /**
     * @Route("/tps", options = { "expose" = true }, name="list_tps")
     */
    public function listTPsAction()
    {
        $tps = $this->getRepository()->listAll();
        $datas = [];

        foreach ($tps as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
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
}
