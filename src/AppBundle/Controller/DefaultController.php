<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle::index.html.twig');
    }

    /**
     * @Route("/work/team", name="team")
     */
    public function teamAction()
    {
        $team = [
            [
                'nom' => 'TEGUIA KOUAM Aurélien',
                'bio' => '',
                'email' => 'tegaurelien@hotmail.fr'
            ],
            [
                'nom' => 'TEGUIA KOUAM Aurélien',
                'bio' => '',
                'email' => 'tegaurelien@hotmail.fr'
            ],
            [
                'nom' => 'TEGUIA KOUAM Aurélien',
                'bio' => '',
                'email' => 'tegaurelien@hotmail.fr'
            ],
            [
                'nom' => 'TEGUIA KOUAM Aurélien',
                'bio' => '',
                'email' => 'tegaurelien@hotmail.fr'
            ]
        ];
        return $this->render('AppBundle::team.html.twig', [
            'team' => $team
        ]);
    }
}
