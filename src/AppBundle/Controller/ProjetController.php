<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projet;
use AppBundle\Entity\TDRSpecific;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

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
            $temp[] = $sample_data->getUser()->username;
            $temp[] = $sample_data->getTp()->getLibelle();
            $temp[] = $sample_data->getDateCreationEnBD();
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
        $em = $this->getEm();

        $libelle = $request->request->get('nom');
        $dateLancement = $request->request->get('date_deb');
        $dateArret = $request->request->get('date_fin');
        $montant = $request->request->get('cout');
        $tp = $request->request->get('tp');
        $proc = $request->request->get('proc');
        $tdrs = $request->request->get('tdr');
        $cctps = $request->request->get('cctp');

        $projet = new Projet();
        $projet->setLibelle($libelle);
        $projet->setDateLancement($dateLancement);
        $projet->setDateArret($dateArret);
        $projet->setMontant($montant);
        $projet->setTp($this->getRepository('TP')->find($tp));
        $projet->setUser($this->getUser());
        //Creation du projet
        $em->persist($projet);

        //Ajout de la proccédure choisie
        $projet->addProc($this->getRepository('Proc')->find($proc));

        //Ajout des TDR Specifiques
        foreach ($tdrs as $tdr) {
            $tdrSpecific = new TDRSpecific();
            $tdrSpecific->setService($tdr['service']);
            $tdrSpecific->setDate(new Date($tdr['date']));
            $tdrSpecific->setProjet($projet);
            $em->persist($tdrSpecific);
        }

        //Ajout des CCTP Specifiques
        foreach ($cctps as $cctp) {
            $cctpSpecific = new TDRSpecific();
            $cctpSpecific->setService($cctp['service']);
            $tdrSpecific->setDate(new Date($tdr['date']));
            $cctpSpecific->setProjet($projet);
            $em->persist($cctpSpecific);
        }

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
        $description = $request->request->get('description');
        $tp = $request->request->get('tp');

        $projet->setLibelle($libelle);
        $projet->setDescription($description);
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
