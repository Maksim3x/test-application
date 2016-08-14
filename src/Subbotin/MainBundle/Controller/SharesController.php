<?php

namespace Subbotin\MainBundle\Controller;

use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Subbotin\MainBundle\Entity\Shares;
use Subbotin\MainBundle\Form\SharesType;
use Symfony\Component\HttpFoundation\Response;

class SharesController extends Controller
{
    /**
     * Список акций
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shares = $em->getRepository('SubbotinMainBundle:Shares')->findBy(array("user" => $this->getUser()->getId()));

        return $this->render('SubbotinMainBundle:shares:index.html.twig', array(
            'shares' => $shares,
        ));
    }

    /**
     * Создание акции
     *
     */
    public function newAction(Request $request)
    {
        $share = new Shares();
        $share->setUser($this->getUser());

        $form = $this->createForm('Subbotin\MainBundle\Form\SharesType', $share);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();

            return $this->redirectToRoute('shares_index');
        }

        return $this->render('SubbotinMainBundle:shares:new.html.twig', array(
            'share' => $share,
            'form' => $form->createView(),
        ));
    }

    /**
     * Редактирование акции
     *
     */
    public function editAction(Request $request, Shares $share)
    {
        $editForm = $this->createForm('Subbotin\MainBundle\Form\SharesType', $share);
        $editForm->handleRequest($request);

	    if($share->getUser()->getId() != $this->getUser()->getId())
		    throw new LogicException("Не найдена запись", Response::HTTP_NOT_FOUND);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();

            return $this->redirectToRoute('shares_edit', array('id' => $share->getId()));
        }

        return $this->render('@SubbotinMain/shares/edit.html.twig', array(
            'share' => $share,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Удаление акции
     *
     */
    public function deleteAction($id)
    {
        if($id && is_numeric($id))
        {
            $share = $this->getDoctrine()->getRepository('SubbotinMainBundle:Shares')->findOneBy(array('id' => $id, 'user' => $this->getUser()->getId()));

	        if(!$share) return new Response('Запись не найдена', Response::HTTP_NOT_FOUND);

            if($share)
            {
                try
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($share);
                    $em->flush();

	                return new Response("SUCCESS");
                }
                catch (\Exception $e)
                {
                    return new Response("Прежде чем удалить акции, убедитесь что она не используется в \"Группах\"");
                }
            }
        }

        return new Response("Не корректный ID");
    }
}
