<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TP;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TPController extends Controller
{
    /**
     * @Route("/tps", name="index_tps")
     */
    public function indexAction()
    {
        $file = __DIR__.'/../../../web/uploads/git_memo_fr.pdf';
        return $this->render('AppBundle:TP:tp.html.twig', [
            "file" => $file
        ]);
    }


    public function getRepository($entity = 'TP')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/tps", options = { "expose" = true }, name="list_datatable_tps")
     */
    public function listDatatableTPsAction()
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

    /**
     * @Route("/api/tps", options = {"expose" = true}, name="find_all_tps")
     */
    public function findAllAction()
    {
        return new JsonResponse($this->getRepository('TP')->findAll());
    }

    /**
     * @Route("/api/add/tp", options = {"expose" = true}, name="add_tp")
     */
    public function addTPAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp = new TP();
        $tp->setLibelle($libelle);
        $tp->setDescription($description);

        $em = $this->getEm();
        $em->persist($tp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/tp/{tp}", options = { "expose" = true }, name="update_tp")
     */
    public function updateTPAction(Request $request, TP $tp)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $tp->setLibelle($libelle);
        $tp->setDescription($description);

        $em = $this->getEm();
        $em->merge($tp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/tp/{tp}", options = { "expose" = true }, name="delete_tp")
     */
    public function deleteTPAction(Request $request, TP $tp)
    {
        $em = $this->getEm();
        $em->remove($tp);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
