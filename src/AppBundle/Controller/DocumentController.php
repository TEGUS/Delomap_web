<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    /**
     * @Route("/document", name="index_document")
     */
    public function indexDocumentsAction()
    {
        return $this->render('AppBundle:Document:document.html.twig');
    }

    public function getRepository($entity = 'Document')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/document", options = { "expose" = true }, name="list_document")
     */
    public function listDocumentsAction()
    {
        $documents = $this->getRepository()->listAll();
        $datas = [];

        foreach ($documents as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getDatesave();
            $temp[] = $sample_data->getDatesignature();
            $temp[] = $sample_data->getTp()->getDateUpload();
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
     * @Route("/api/add/document", options = { "expose" = true }, name="add_document")
     */
    public function addDocumentAction(Request $request)
    {
        $datesave = $request->request->get('datesave');
        $datesignature= $request->request->get('datesignature');
        $dateupload= $request->request->get('dateupload');
        $document = new Document();
        $document->setDatesave($datesave);
        $document->setDatesignature($datesignature);
        $document->setDateupload($dateupload);

        $em = $this->getEm();
        $em->persist($document);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/document/{document}", options = { "expose" = true }, name="update_document")
     */
    public function updateDOCUMENTAction(Request $request, Document $document)
    {
        $datesignature = $request->request->get('datesignature');
        $dateupload = $request->request->get('dateupload');
        $datesave = $request->request->get('datesave');

        $document->setDatesignature($datesignature);
        $document->setDateupload($dateupload);
        $document->setDatesave($datesave);

        $em = $this->getEm();
        $em->merge($document);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/document/{document}", options = { "expose" = true }, name="delete_document")
     */
    public function deleteDOCUMENTAction(Request $request, Document $document)
    {
        $em = $this->getEm();
        $em->remove($document);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
