<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DAG;
use AppBundle\Entity\Fichier;
use AppBundle\Form\FichierType;
use AppBundle\Form\SimpleFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DAGController extends Controller
{
    /**
     * @Route("/dags", name="index_dags")
     */
    public function indexDagsAction()
    {
        return $this->render('AppBundle:DAG:dag.html.twig');
    }

    public function getRepository($entity = 'DAG')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/dags", options = { "expose" = true }, name="list_datatable_dags")
     */
    public function listDatatableDAGsAction()
    {
        $dags = $this->getRepository()->listAll();
        $datas = [];

        foreach ($dags as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
            $temp[] = $sample_data->getDalaisTransmission();
            $temp[] = $sample_data->getStatus();

            $temp[] = '
                <a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
                <span class="space-button"></span>
                <a href="#" class="view-files" title="Visualiser Fichiers"><i class="fa fa-eye fa-lg"></i></a>
                <span class="space-button"></span>
                <a href="#" class="add-files" title="Ajouter Fichier"><i class="fa fa-file fa-lg"></i></a>
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
     * @Route("/api/dag/{dag}/datatable/fichiers", options = { "expose" = true }, name="list_dag_datatable_fichiers")
     */
    public function listDatatableDAGFichierAction($dag)
    {
        $fichiers = $this->getRepository('Fichier')->findByDag($dag);
        $datas = [];

        foreach ($fichiers as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getNom();

            $temp[] = '
                <!--<a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>-->
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
                <span class="space-button"></span>
                <a href="#" class="view" title="Visualiser le document"><i class="fa fa-eye fa-lg"></i></a>
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
     * @Route("/api/dags", options = {"expose" = true}, name="find_all_dags")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/proc/{proc}/dags", options = {"expose" = true}, name="find_all_dags_by_proc")
     */
    public function findDagByProcAction($proc)
    {
        return new JsonResponse($this->getRepository()->findDAGByIdProc($proc));
    }

    /**
     * @Route("/api/add/dag", options = {"expose" = true}, name="add_dag")
     */
    public function addDAGAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $status = $request->request->get('statut');
        $delaisTransmission = $request->request->get('dalaisTransmission');

        $dag = new DAG();
        $dag->setLibelle($libelle);
        $dag->setDescription($description);
        $dag->setStatus($status);
        $dag->setDalaisTransmission($delaisTransmission);

        $em = $this->getEm();
        $em->persist($dag);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/dag/{dag}", options = { "expose" = true }, name="update_dag")
     */
    public function updateDAGAction(Request $request, DAG $dag)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');
        $delaistransmission = $request->request->get('delaistransmission');
        $status = $request->request->get('statut');

        $dag->setLibelle($libelle);
        $dag->setDescription($description);
        $dag->setDelaistransmission($delaistransmission);
        $dag->setStatus($status);

        $em = $this->getEm();
        $em->merge($dag);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/dag/{dag}", options = { "expose" = true }, name="delete_dag")
     */
    public function deleteDAGAction(Request $request, DAG $dag)
    {
        $em = $this->getEm();
        $em->remove($dag);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/dag/remove/fichier/{fichier}", options = { "expose" = true }, name="update_dag_remove_fichier")
     */
    public function updateDagRemoveFichierAction(Fichier $fichier)
    {
        $em = $this->getEm();
        $em->remove($fichier);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/update/dag/{dag}/add/fichier", name="update_dag_add_fichier")
     */
    public function updateDagAddFichierAction(Request $request, DAG $dag)
    {
        $fichier = new Fichier();
        $fichier->setDag($dag);
        $form = $this->createForm(SimpleFileType::class, $fichier);

        $em = $this->getEm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($fichier);
            $em->flush();

            return $this->redirect($this->generateUrl('index_dags'));
        }

        $parameters = array(
            'form' =>  $form->createView(),
            'dag' => $dag
        );
        return $this->render('AppBundle:DAG:fichier.html.twig', $parameters);
    }
}
