<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjetController extends Controller
{
    //    Retoune User
    public function getUser()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @Route("/projets", name="index_projets")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Projet:projet.html.twig');
    }
     /**
     * @Route("/fiche_suivi", name="index_fiche_suivi")
     */
    public function indexAction3()
    {
        return $this->render('AppBundle:suivi:suivi.html.twig');
    }

    /**
     * @Route("/journal_programmation", name="index_journal_programmation")
     */
    public function indexAction2()
    {
        return $this->render('AppBundle:JP:jp.html.twig');
    }

    public function getRepository($entity = 'Projet')
    {
        return $this->getEm()->getRepository("AppBundle:" . $entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/projets", options = { "expose" = true }, name="list_datatable_projets")
     */
    public function listProjetsAction()
    {
        $projets = $this->getRepository()->listAll();
        $datas = [];

        foreach ($projets as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
            $temp[] = $sample_data->getMontant();
            $temp[] = $sample_data->getDateLancement();
            $temp[] = $sample_data->getDateAttribution();
            $temp[] = $sample_data->getDateSignature();
            $temp[] = $sample_data->getDateDemarrage();
            $temp[] = $sample_data->getDateReception();
            $temp[] = $sample_data->getMotif();
            $temp[] = $sample_data->getObservation();
            $temp[] = $sample_data->getContractant();
            $temp[] = $sample_data->getUser()->getUsername();
            $temp[] = $sample_data->getTp()->getLibelle();
            $temp[] = $sample_data->getDateCreationEnBD();
            $temp[] = $sample_data->getStatutProccessus();
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
     * @Route("/api/projets", options = {"expose" = true}, name="find_all_projets")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/add/projet", options = { "expose" = true }, name="add_projet")
     */
    public function addProjetAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $dateLancement = $request->request->get('dateLancement');
        $dateAttribution = $request->request->get('dateAttribution');
        $dateSignature = $request->request->get('dateSignature');
        $dateDemarrage = $request->request->get('dateDemarrage');
        $dateReception = $request->request->get('dateReception');
        $dateArret = $request->request->get('dateArret');
        $montant = $request->request->get('montant');
        $tp = $request->request->get('tp');

        $projet = new Projet();
        $projet->setLibelle($libelle);
        $projet->setDateLancement(new \DateTime($dateLancement));
        $projet->setDateAttribution(new \DateTime($dateAttribution));
        $projet->setDateSignature(new \DateTime($dateSignature));
        $projet->setDateDemarrage(new \DateTime($dateDemarrage));
        $projet->setDateReception(new \DateTime($dateReception));
        $projet->setDateArret(new \DateTime($dateArret));
        $projet->setMontant($montant);
        $projet->setTp($this->getRepository('TP')->find($tp));
        $projet->setUser($this->getUser());

        $em = $this->getEm();
        $em->persist($projet);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/projet/{projet}", options = { "expose" = true }, name="update_projet")
     */
    public function updateProjetAction(Request $request, Projet $projet)
    {
        $libelle = $request->request->get('libelle');
        $dateLancement = $request->request->get('dateLancement');
        $dateAttribution = $request->request->get('dateAttribution');
        $dateSignature = $request->request->get('dateSignature');
        $dateDemarrage = $request->request->get('dateDemarrage');
        $dateReception = $request->request->get('dateReception');
        $dateArret = $request->request->get('dateArret');
        $montant = $request->request->get('montant');
        $statusProcessus = $request->request->get('statutProcessus');
        $tp = $request->request->get('tp');

        $projet->setLibelle($libelle);
        $projet->setDateLancement(new \DateTime($dateLancement));
        $projet->setDateAttribution(new \DateTime($dateAttribution));
        $projet->setDateSignature(new \DateTime($dateSignature));
        $projet->setDateDemarrage(new \DateTime($dateDemarrage));
        $projet->setDateReception(new \DateTime($dateReception));
        $projet->setDateArret(new \DateTime($dateArret));
        $projet->setMontant($montant);
        $projet->setTp($this->getRepository('TP')->find($tp));

        $em = $this->getEm();
        $em->merge($projet);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/delete/projet/{projet}", options = { "expose" = true }, name="delete_projet")
     */
    public function deleteProjetAction(Request $request, Projet $projet)
    {
        $em = $this->getEm();
        $em->remove($projet);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
