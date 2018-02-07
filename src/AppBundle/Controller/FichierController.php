<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fichier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FichierController extends Controller
{
    /**
     * @Route("/fichiers", name="index_fichiers")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:DAG:fichier.html.twig');
    }


    public function getRepository($entity = 'Fichier')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/fichiers", options = { "expose" = true }, name="list_datatable_fichiers")
     */
    public function listDatatableFichiersAction()
    {
        $fichiers = $this->getRepository()->listAll();
        $datas = [];

        foreach ($fichiers as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getNom();
            $temp[] = $sample_data->getDateCreation();
            $temp[] = $sample_data->getUpdatedAt();
            $temp[] = $sample_data->getDag()->getLibelle();
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
     * @Route("/api/fichiers", options = {"expose" = true}, name="find_all_fichiers")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/add/fichier", options = {"expose" = true}, name="add_fichier")
     */
    public function addFichierAction(Request $request)
    {
        $docFile = $request->files->get('docFile');
        $dag = $request->request->get('dag');

        $fichier = new Fichier();
        $fichier->setDocFile($docFile);
        $fichier->setDag($this->getRepository('DAG')->find($dag));

        $em = $this->getEm();
        $em->persist($fichier);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/fichier/{fichier}", options = { "expose" = true }, name="update_fichier")
     */
    public function updateFichierAction(Request $request, Fichier $fichier)
    {
        $docFile = $request->files->get('docFile');
        $dag = $request->request->get('dag');

        $fichier->setDocFile($docFile);
        $fichier->setDag($this->getRepository('DAG')->find($dag));

        $em = $this->getEm();
        $em->merge($fichier);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/fichier/{fichier}", options = { "expose" = true }, name="delete_fichier")
     */
    public function deleteFichierAction(Request $request, Fichier $fichier)
    {
        $em = $this->getEm();
        $em->remove($fichier);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
