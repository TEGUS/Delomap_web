<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MailingController extends Controller
{
    /**
     * @Route("/api/send/mail", options = { "expose" = true }, name="send_mail")
     */
    public function sendMailAction(Request $request)
    {
        $from = $this->getParameter('email.from');
        $to = $request->request->get('to');
        $files = $request->request->get('files');
        $object = $request->request->get('object');
        $content = $request->request->get('content');

        $message = \Swift_Message::newInstance()
            ->setSubject(trim($object) == '' ? 'Documents via DELOMAP APP' : trim($object))
            ->setFrom(trim($from))
            ->setTo($to)
            ->setBody(trim($content) == '' ? 'Via DELOMAP App' : trim($content));

//        foreach ($files as $file) {
//            $message->attach(\Swift_Attachment::fromPath($file));
//        }

        $result = $this->get('mailer')->send($message) ? true : false;

        return new JsonResponse([
            "data" => $result,
            "data_message" => $message->toString()
        ]);
    }
}
