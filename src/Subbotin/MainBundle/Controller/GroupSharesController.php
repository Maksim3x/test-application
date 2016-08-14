<?php

namespace Subbotin\MainBundle\Controller;

use Subbotin\MainBundle\Form\SharesType;
use Subbotin\Service\FinanceService;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Subbotin\MainBundle\Entity\GroupShares;
use Subbotin\MainBundle\Form\GroupSharesType;
use Symfony\Component\HttpFoundation\Response;

class GroupSharesController extends Controller
{
    /**
     * Список групп акций
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groupShares = $em->getRepository('SubbotinMainBundle:GroupShares')->findBy(array("user" => $this->getUser()->getId()));

        return $this->render('@SubbotinMain/groupshares/index.html.twig', array(
            'groupShares' => $groupShares,
        ));
    }

    /**
     * создание группы акций
     *
     */
    public function newAction(Request $request)
    {
        $groupShare = new GroupShares();
        $groupShare->setUser($this->getUser());

        $form = $this->createForm(new GroupSharesType($this->getUser()), $groupShare);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupShare);
            $em->flush();

            return $this->redirectToRoute('groupshares_index');
        }

        return $this->render('@SubbotinMain/groupshares/new.html.twig', array(
            'groupShare' => $groupShare,
            'form' => $form->createView(),
        ));
    }

    /**
     * Редактирование группы акций
     *
     */
    public function editAction(Request $request, GroupShares $groupShare)
    {
        $editForm = $this->createForm(new GroupSharesType($this->getUser()), $groupShare);
        $editForm->handleRequest($request);

	    if($groupShare->getUser()->getId() != $this->getUser()->getId())
		    throw new LogicException("Не найдена запись", Response::HTTP_NOT_FOUND);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupShare);
            $em->flush();

            return $this->redirectToRoute('groupshares_edit', array('id' => $groupShare->getId()));
        }

        return $this->render('@SubbotinMain/groupshares/edit.html.twig', array(
            'groupShare' => $groupShare,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Удаление группы акций
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if($id && is_numeric($id))
        {
	        $groupShare = $this->getDoctrine()->getRepository('SubbotinMainBundle:GroupShares')->findOneBy(array('id' => $id, 'user' => $this->getUser()->getId()));

	        if(!$groupShare) return new Response('Запись не найдена', Response::HTTP_NOT_FOUND);

            if($groupShare)
            {
                try
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($groupShare);
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

    /**
     * Рисование графика
     * @param $groupId
     * @return Response
     */
    public function drawGraphicAction($groupId)
    {
		$group = $this->getDoctrine()->getRepository('SubbotinMainBundle:GroupShares')->findOneBy(array("id" => $groupId, "user" => $this->getUser()->getId()));

	    if(!$group)
	    	throw new LogicException("Группа не существует", Response::HTTP_NOT_FOUND);

		$jsonData = [];
	    $finance = $this->get('finance_service');

	    foreach ($group->getShares() as $share)
	    {
		    $finance->setShare($share->code);
		    $jsonData[] = $finance->getJsonData();
	    }

	    return $this->render('SubbotinMainBundle:groupshares:draw_graphics.html.twig', array(
			'data' => $jsonData,
	    ));
    }
}
