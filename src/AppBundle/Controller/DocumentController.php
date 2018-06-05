<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DAG;
use AppBundle\Entity\Document;
use AppBundle\Entity\Projet;
use AppBundle\Form\DocumentModifieType;
use AppBundle\Form\DocumentSigneType;
use AppBundle\Form\DocumentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    public function getRepository($entity = 'Document')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/document", name="index_document")
     */
    public function indexDocumentsAction(Request $request)
    {
        return $this->render('AppBundle:Document:document.html.twig');
    }

    /**
     * @Route("/new/document", name="new_document")
     */
    public function newDocumentsAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $em = $this->getEm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $document->setDateSave(new \DateTime('now'));
            $document->setDateUpload(new \DateTime('now'));
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('index_document'));
        }

        $parameters = array(
            'form' => $form->createView()
        );
        return $this->render('AppBundle:Document:new_document_signe.html.twig', $parameters);
    }

    /**
     * @Route("/new/document/signe/{document}", name="new_document_signe")
     */
    public function newDocumentSigneAction(Request $request, Document $document)
    {
        /* $document = $this->getRepository()->findOneBy([
            "projet" => $projet,
            "dag" => $dag
        ]); */

        // $document = $projet;
        /*
        dump($document);
        die();
        
        $document = new Document();
        $document->setProjet($projet);
        $document->setDag($dag);*/
        $form = $this->createForm(DocumentSigneType::class, $document);
        $em = $this->getEm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $document->setDateSave(new \DateTime('now'));
            $document->setDateUpload(new \DateTime('now'));
            $em->persist($document);

            $projet = $document->getProjet();
            $list_docs_projet = $this->getRepository()->findBy([
                "projet" => $projet->getId()
            ]);
            $bool = true;
            foreach ($list_docs_projet as $p) {
                if ($p->getFichierSigne() == null) {
                    $bool = false;
                    break;
                }
            }
            if ($bool) {
                $projet->setStatutProccessus(4);
                $em->merge($projet);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('index_projets'));
        }

        $parameters = array(
            'form' => $form->createView()
        );
        return $this->render('AppBundle:Document:document_signe.html.twig', $parameters);
    }

    /**
     * @Route("/new/document/modifie/{document}", name="new_document_modifie")
     */
    public function newDocumentModifieAction(Request $request, Document $document)
    {
        // $document = new Document();
        // $document->setProjet($projet);
        // $document->setDag($dag);

        // $document = $projet;

        $form = $this->createForm(DocumentModifieType::class, $document);
        $em = $this->getEm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('index_projets'));
        }

        $parameters = array(
            'form' => $form->createView()
        );
        return $this->render('AppBundle:Document:document_modifie.html.twig', $parameters);
    }

    /**
     * @Route("/api/datatable/documents", options = { "expose" = true }, name="list_datatable_documents")
     */
    public function listDocumentsAction()
    {
        $documents = $this->getRepository()->listAll();
        $datas = [];

        foreach ($documents as $sample_data) {
            $fichierSigne = $sample_data->getFichierSigne();
            $options = '
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
                <span class="space-button"></span>
            ';

            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getProjet()->getLibelle();
            $temp[] = $sample_data->getDag()->getLibelle();
            $temp[] = $this->my_get_date($sample_data->getDateSignature());
            $temp[] = $this->my_get_date($sample_data->getDateUpload());
            $temp[] = $fichierSigne != null ? $options . '
                <a href="#" class="view '.$sample_data->getId().'" title="Visualiser le document"><i class="fa fa-eye fa-lg"></i></a>
                ' :
                $options;

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
     * @Route("/api/projet/{projet}/documents", options = { "expose" = true }, name="list_documents_projet")
     */
    public function listDocumentByProjetAction($projet)
    {
        $documents = $this->getRepository()->findByProjet2($projet);
        $datas = [];
        foreach ($documents as $sample_data) {
            $dag = $sample_data->getDag() === null ? null : $sample_data->getDag();
            $fichierModifie = $sample_data->getFichierModifie() === null ? null : $sample_data->getFichierModifie();
            $fichierSigne = $sample_data->getFichierSigne() === null ? null : $sample_data->getFichierSigne();
            $doc_model = null;

            if ($dag != null) {
                $fichiers = $this->getRepository('Fichier')->findByDag($dag->getId());
                $doc_model = count($fichiers) != 0 ? $fichiers[count($fichiers) - 1]->getNom() : null;
            } else {
                $doc_model = null;
            }

            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $dag === null ? null : $dag->getLibelle();
            $temp[] = $doc_model;
            $temp[] = $this->my_get_date($sample_data->getDateSignature());
            $temp[] = $this->my_get_date($sample_data->getDateUpload());
            $temp[] = $this->my_get_date($sample_data->getDateSave());
            $temp[] = $this->my_get_date($sample_data->getDateCreation());
            $temp[] = $fichierModifie === null ? null : $fichierModifie->getNom();
            $temp[] = $fichierSigne === null ? null : $fichierSigne->getNom();
            $datas[] = $temp;
        }

        return new JsonResponse([
            "data" => $datas
        ]);
    }

    /**
     * @Route("/api/add/document", options = { "expose" = true }, name="add_document")
     */
    public function addDocumentAction(Request $request)
    {
        $datesignature = $request->request->get('datesignature');
        $dateupload = $request->request->get('dateupload');

        $document = new Document();
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
    public function deleteDocumentAction(Request $request, Document $document)
    {
        $em = $this->getEm();
        $em->remove($document);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/find/document/{document}", options = { "expose" = true }, name="find_document")
     */
    public function findDocumentAction(Document $document)
    {
        $doc = $this->getRepository()->findById($document);

        $fichierSigne = $doc[0]->getFichierSigne();
        if ($fichierSigne != null) {
            $data = $fichierSigne->getNom();
        } else {
            $data = null;
        }
        return new JsonResponse([
            "data" => $data
        ]);
    }


    private function my_get_date($date)
    {
        return $date === null ? null : $date->format('Y-m-d');
    }

    private function getValueOfElement($element, $getValue)
    {
        return $element === null ? null : $getValue;
    }
}
