<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DAG;
use AppBundle\Entity\Proc;
use AppBundle\Entity\TP;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProcController extends Controller
{
    /**
     * @Route("/procs", name="index_procs")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Proc:proc.html.twig');
    }

    public function getRepository($entity = Proc::class)
    {
        return $this->getEm()->getRepository($entity);
    }

    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/datatable/procs", options = { "expose" = true }, name="list_datatable_procs")
     */
    public function listProcsAction()
    {
        $procs = $this->getRepository()->listAll();
        $datas = [];

        foreach ($procs as $sample_data) {
            $temp = [];
            $temp[] = $sample_data->getId();
            $temp[] = $sample_data->getLibelle();
            $temp[] = $sample_data->getDescription();
            $temp[] = $this->getRepository(DAG::class)->findDAGByIdProc($sample_data->getId());
            $temp[] = '
                <a href="#" class="edit" title="Modifier"><i class="fa fa-edit fa-lg fa-primary"></i></a>
                <span class="space-button"></span>
                <a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a>
                <span class="space-button"></span>
                <a href="#" class="add_doc" title="Ajouter un document à générer"><i class="fa fa-list fa-lg fa-defaultgit "></i></a>
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
     * @Route("/api/procs", options = {"expose" = true}, name="find_all_procs")
     */
    public function findAllAction()
    {
        return new JsonResponse([
            "data" => $this->getRepository()->findAll()
        ]);
    }

    /**
     * @Route("/api/add/proc", options = { "expose" = true }, name="add_proc")
     */
    public function addProcAction(Request $request)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');

        $proc = new Proc();
        $proc->setLibelle($libelle);
        $proc->setDescription($description);

        $em = $this->getEm();
        $em->persist($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }

    /**
     * @Route("/api/update/proc/{proc}", options = { "expose" = true }, name="update_proc")
     */
    public function updateProcAction(Request $request, Proc $proc)
    {
        $libelle = $request->request->get('libelle');
        $description = $request->request->get('description');

        $proc->setLibelle($libelle);
        $proc->setDescription($description);

        $em = $this->getEm();
        $em->merge($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }


    /**
     * @Route("/api/proc/{proc}/add/tp", options = { "expose" = true }, name="update_proc_add_tp")
     */
    public function updateProcAddTPAction(Request $request, Proc $proc)
    {
        $tp = $request->request->get('tp');;
        $proc->addTp($this->getRepository(TP::class)->find($tp));

        $em = $this->getEm();
        $em->merge($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }


    /**
     * @Route("/api/proc/{proc}/remove/tp", options = { "expose" = true }, name="update_proc_remove_tp")
     */
    public function updateProcRemoveTPAction(Request $request, Proc $proc)
    {
        $tp = $request->request->get('tp');;
        $proc->removeTp($this->getRepository(TP::class)->find($tp));

        $em = $this->getEm();
        $em->merge($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }


    /**
     * @Route("/api/proc/{proc}/add/dag", options = { "expose" = true }, name="update_proc_add_dag")
     */
    public function updateProcAddDAGAction(Request $request, Proc $proc)
    {
        $dag = $request->request->get('dag');
        $proc->addDag($this->getRepository(DAG::class)->find(intval($dag)));

        $em = $this->getEm();
        $em->merge($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }


    /**
     * @Route("/api/proc/{proc}/remove/dag", options = { "expose" = true }, name="update_proc_remove_dag")
     */
    public function updateProcRemoveDAGAction(Request $request, Proc $proc)
    {
        $dag = $request->request->get('dag');;
        $proc->removeDag($this->getRepository(DAG::class)->find  (intval($dag)));

        $em = $this->getEm();
        $em->merge($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }


    /**
     * @Route("/api/delete/proc/{proc}", options = { "expose" = true }, name="delete_proc")
     */
    public function deleteProcAction(Request $request, Proc $proc)
    {
        $em = $this->getEm();
        $em->remove($proc);
        $em->flush();

        return new JsonResponse([
            "data" => true,
        ]);
    }
}
