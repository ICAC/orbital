<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Round;
use AppBundle\Form\Type\RoundType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RoundController extends Controller
{
    /**
     * @Route("/rounds", name="round_list", methods={"GET"})
     */
    public function indexAction()
    {
        $roundRepository = $this->getDoctrine()->getRepository("AppBundle:Round");

        $rounds = $roundRepository->findAllGrouped();

        return $this->render('round/list.html.twig', [
            'groupedRounds' => $rounds[0],
            'categories' => $rounds[1],
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/round/create", name="round_create", methods={"GET", "POST"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $round = new Round();
        $form = $this->createForm(RoundType::class, $round);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($round);
            $em->flush();

            return $this->redirectToRoute(
                'round_detail',
                ['id' => $round->getId()]
            );
        }

        return $this->render('round/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/round/{id}", name="round_detail", methods={"GET"})
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction($id)
    {
        $doctrine = $this->getDoctrine();

        $roundRepository = $doctrine->getRepository("AppBundle:Round");
        $round = $roundRepository->find($id);
        if (!$round) {
            throw $this->createNotFoundException(
                'No round found for id ' . $id
            );
        }

        $records = $doctrine
            ->getRepository("AppBundle:Record")
            ->getByRound($round);

        return $this->render('round/detail.html.twig', [
            'round' => $round,
            'records' => $records
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/round/{id}/edit", name="round_edit", methods={"GET", "POST"})
     *
     * @param integer $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $round = $em->getRepository('AppBundle:Round')->find($id);
        if (!$round) {
            throw $this->createNotFoundException(
                'No round found for id ' . $id
            );
        }

        $form = $this->createForm(RoundType::class, $round);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'round_detail',
                ['id' => $round->getId()]
            );
        }

        return $this->render('round/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/round/{id}/delete", name="round_delete", methods={"GET", "POST"})
     *
     * @param integer $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $round = $em->getRepository('AppBundle:Round')->find($id);

        if (!$round) {
            throw $this->createNotFoundException(
                'No round found for id ' . $id
            );
        }

        if ($request->isMethod("POST")) {
            $em->remove($round);
            $em->flush();

            return $this->redirectToRoute('round_list');
        }

        return $this->render('round/delete.html.twig', [
            'round' => $round
        ]);
    }
}
