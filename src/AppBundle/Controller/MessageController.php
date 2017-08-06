<?php

namespace AppBundle\Controller;

use \DateTime;
use \DateInterval;
use AppBundle\Entity\Message;
use SensioLabs\Security\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Message controller.
 *
 * @Route("message")
 */
class MessageController extends Controller
{

    /**
     * Creates a new message entity.
     *
     * @Route("/new", name="message_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $message = new Message();
        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok',
                'message_url' => $this->generateUrl('message_show_key', array('access_key' => $message->getAccessKey()), UrlGeneratorInterface::ABSOLUTE_URL)));
        }

        return $this->render('message/new.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{access_key}/check_password/{password}", name="message_check_password")
     * @Method("GET")
     */
    public function checkPasswordyAction($access_key, $password='')
    {
        $repository = $this->getDoctrine()
            ->getRepository(Message::class);

        $message = $repository->findOneByAccessKey($access_key);
        if (!$message) {
            throw $this->createNotFoundException('The message does not exist');
        }

        if ($message->getPassword() === $password) {
            $status = "ok";
        } else {
            $status = "error";
        }
        return new JsonResponse(array('status' => $status));
    }

    /**
     * Finds and displays a message entity.
     *
     * @Route("/{access_key}", name="message_show_key")
     * @Method("GET")
     */
    public function showByKeyAction($access_key)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Message::class);

        $message = $repository->findOneByAccessKey($access_key);
        if (!$message) {
            throw $this->createNotFoundException('The message does not exist');
        }

        $em = $this->getDoctrine()->getManager();


        switch ($message->getDestructWay()) {
            case Message::DESTRUCT_READ:
                if ($message->getDestructOption() > 0) {
                    $message->setDestructOption($message->getDestructOption() - 1);
                    $em->persist($message);
                } else {
                    $em->remove($message);
                    $message = null;
                }
                $em->flush();
                break;
            case Message::DESTRUCT_TIME:
                $dt = $message->getCreated();
                $dt->add(new DateInterval('PT'.$message->getDestructOption().'H'));

                if ($dt < new DateTime()) {
                    $em->remove($message);
                    $em->flush();
                    $message = null;
                }
                break;
            default:
                throw new RuntimeException('Something went wrong');
        }

        if ($message) {
            return $this->render('message/show.html.twig', array(
                'message' => $message

            ));
        } else {
            throw $this->createNotFoundException('The message does not exist');
        }
    }

}
