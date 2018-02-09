<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CCTP;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CCTPController extends Controller
{
    /**
     * @Route("/cctp", name="index_cctp")
     */
    public function indexTpsAction()
    {
        return $this->render('AppBundle:CCTP:cctp.html.twig');
    }

    public function getRepository($entity = 'CCTP')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/cctps", options = { "expose" = true }, name="list_cctp")
     */
    public function listCCTPsAction()
    {
        $cctps = $this->getRepository()->listAll();
        $datas = [];

        foreach ($cctps as $sample_data) {
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
     * @Route("/api/add/cctp", options = { "expose" = true }, name="add_cctp")
     */
    public function addCCTPAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp = $request->request->get('tp');
        $cctp = new CCTP();
        $cctp->setLibelle($libelle);
        $cctp->setDescription($description);
        $cctp->setTp($this->getRepository('TP')->find($tp));

        $em = $this->getEm();
        $em->persist($cctp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/cctp/{cctp}", options = { "expose" = true }, name="update_cctp")
     */
    public function updateCCTPAction(Request $request, CCTP $cctp)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp = $request->request->get('tp');

        if ($libelle != null && $libelle != '')
            $cctp->setLibelle($libelle);
        
        if ($description != null && $description != '')
            $cctp->setDescription($description);
        
        if ($tp != null && $tp != '')
            $cctp->setTp($this->getRepository('TP')->find($tp));

        $em = $this->getEm();
        $em->merge($cctp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/cctp/{cctp}", options = {"expose" = true}, name="delete_cctp")
     */
    public function deleteCCTPAction(Request $request, CCTP $cctp)
    {
        $em = $this->getEm();
        $em->remove($cctp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
