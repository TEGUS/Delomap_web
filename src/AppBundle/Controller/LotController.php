<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LotController extends Controller
{
    /**
     * @Route("/lots", name="index_lots")
     */
    public function indexTpsAction()
    {
        return $this->render('AppBundle:Lot:lot.html.twig');
    }

    public function getRepository($entity = 'Lot')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/lots", options = { "expose" = true }, name="list_lots")
     */
    public function listLotsAction()
    {
        $lots = $this->getRepository()->listAll();
        $datas = [];

        foreach ($lots as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
            $temp[] = $sample_data->getProjet()->getLibelle();
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
     * @Route("/api/add/lot", options = { "expose" = true }, name="add_lot")
     */
    public function addLotAction(Request $request)
    {
        $em = $this->getEm();

        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $projet = $request->request->get('projet');

        $lot = new Lot();
        $lot->setLibelle($libelle);
        $lot->setDescription($description);
        $lot->setProjet($this->getRepository('Projet')->find($projet));

        $em->persist($lot);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/lot/{lot}", options = { "expose" = true }, name="update_lot")
     */
    public function updateLotAction(Request $request, Lot $lot)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $projet = $request->request->get('projet');

        $lot->setLibelle($libelle);
        $lot->setDescription($description);
        $lot->setProjet($this->getRepository('Projet')->find($projet));

        $em = $this->getEm();
        $em->merge($lot);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/lot/{lot}", options = { "expose" = true }, name="delete_lot")
     */
    public function deleteLotAction(Request $request, Lot $lot)
    {
        $em = $this->getEm();
        $em->remove($lot);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
