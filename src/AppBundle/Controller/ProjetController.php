<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CCTPSpecific;
use AppBundle\Entity\Projet;
use AppBundle\Entity\TDRSpecific;
use AppBundle\Form\CCTPSpecificType;
use AppBundle\Form\CCTPSpecificTypeWithoutProjet;
use AppBundle\Form\TDRSpecificType;
use AppBundle\Form\TDRSpecificTypeWithoutProjet;
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
            $temp[] = $this->my_get_date($sample_data->getDateLancement());
            $temp[] = $this->my_get_date($sample_data->getDateAttribution());
            $temp[] = $this->my_get_date($sample_data->getDateSignature());
            $temp[] = $this->my_get_date($sample_data->getDateDemarrage());
            $temp[] = $this->my_get_date($sample_data->getDateReception());
            $temp[] = $sample_data->getMotif();
            $temp[] = $sample_data->getObservation();
            $temp[] = $sample_data->getContractant();
            $temp[] = $sample_data->getUser()->getUsername();
            $temp[] = $sample_data->getTp()->getId();
            $temp[] = $sample_data->getTp()->getLibelle();
            $temp[] = $sample_data->getDateCreationEnBD();
            $temp[] = $this->format_status($sample_data->getStatutProccessus());
            $temp[] = '
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float edit" title="Modifier"><i class="material-icons">mode_edit</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float remove" title="Supprimer"><i class="material-icons">clear</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float add-cctp" title="Ajouter CCTP"><i class="material-icons">add</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float add-tdr" title="Ajouter TDR"><i class="material-icons">add_circle</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float details" title="Visualiser le projet"><i class="material-icons">visibility</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float proc" title="Ajouter une procedure"><i class="material-icons">timeline</i></a>
                <span class="space-button2"></span>
                <a type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float docs" title="Mise à jour des documents"><i class="material-icons">description</i></a>
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
        if ($dateLancement != null && $dateLancement != '') $projet->setDateLancement(new \DateTime($dateLancement));
        if ($dateAttribution != null && $dateAttribution != '') $projet->setDateAttribution(new \DateTime($dateAttribution));
        if ($dateSignature != null && $dateSignature != '') $projet->setDateSignature(new \DateTime($dateSignature));
        if ($dateDemarrage != null && $dateDemarrage != '') $projet->setDateDemarrage(new \DateTime($dateDemarrage));
        if ($dateReception != null && $dateReception != '') $projet->setDateReception(new \DateTime($dateReception));
        if ($dateArret != null && $dateArret != '') $projet->setDateArret(new \DateTime($dateArret));
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
        if ($dateLancement != null && $dateLancement != '') $projet->setDateLancement(new \DateTime($dateLancement));
        if ($dateAttribution != null && $dateAttribution != '') $projet->setDateAttribution(new \DateTime($dateAttribution));
        if ($dateSignature != null && $dateSignature != '') $projet->setDateSignature(new \DateTime($dateSignature));
        if ($dateDemarrage != null && $dateDemarrage != '') $projet->setDateDemarrage(new \DateTime($dateDemarrage));
        if ($dateReception != null && $dateReception != '') $projet->setDateReception(new \DateTime($dateReception));
        if ($dateArret != null && $dateArret != '') $projet->setDateArret(new \DateTime($dateArret));
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
     * @Route("/api/update/projet/{projet}/choose/proc", options = { "expose" = true }, name="update_projet_choose_proc")
     */
    public function updateProjetChooseProcAction(Request $request, Projet $projet)
    {
        $proc = $request->request->get('proc');
        $projet->setProc($this->getRepository('Proc')->find($proc));
        $projet->setStatutProccessus(3);

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

    /**
     * @Route("/update/projet/{projet}/add/cctp", name="update_projet_add_cctp")
     */
    public function updateProjetAddCCTPAction(Request $request, $projet)
    {
        $projet = $this->getRepository()->find($projet);
        $cctp = new CCTPSpecific();
        $form = $this->createForm(CCTPSpecificType::class, $cctp);

        if ($projet != null) {
            $projet->setStatutProccessus(2);
            $cctp->setProjet($projet);
            $form = $this->createForm(CCTPSpecificTypeWithoutProjet::class, $cctp);
        }

        $em = $this->getEm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($projet != null) {
                $em->merge($projet);
            }

            $em->persist($cctp);
            $em->flush();

            return $this->redirect($this->generateUrl('index_projets'));
        }

        $parameters = array(
            'form' => $form->createView(),
            'projet' => $projet
        );
        return $this->render('AppBundle:CCTP:cctp.html.twig', $parameters);
    }

    /**
     * @Route("/update/projet/{projet}/add/tdr", name="update_projet_add_tdr")
     */
    public function updateProjetAddTDRAction(Request $request, $projet)
    {
        $projet = $this->getRepository()->find($projet);
        $tdr = new TDRSpecific();
        $form = $this->createForm(TDRSpecificType::class, $tdr);

        if ($projet != null) {
            $projet->setStatutProccessus(2);
            $tdr->setProjet($projet);

            $form = $this->createForm(TDRSpecificTypeWithoutProjet::class, $tdr);
        }

        $em = $this->getEm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($projet != null) {
                $em->merge($projet);
            }

            $em->persist($tdr);
            $em->flush();

            return $this->redirect($this->generateUrl('index_projets'));
        }

        $parameters = array(
            'form' => $form->createView(),
            'projet' => $projet
        );
        return $this->render('AppBundle:TDR:tdr.html.twig', $parameters);
    }

    private function format_status($status)
    {
        switch ($status) {
            case 1:
                return '<span class="label label-default">Créé</span>';
            case 2:
                return '<span class="label label-warning">Initialisé</span>';
            case 3:
                return '<span class="label label-warning">Procédure choisie</span>';
            case 4:
                return '<span class="label label-success">Terminé</span>';
        }
        return '';
    }

    private function my_get_date($date)
    {
        return $date === null ? null : $date->format('Y-m-d');
    }
}
