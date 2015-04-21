<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Form\Type\PersonType;
use AppBundle\Form\Type\ReassessType;
use AppBundle\Services\Importing\PersonImportParameters;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @Route("/people", name="person_list")
     */
    public function indexAction()
    {
        $personRepository = $this->getDoctrine()->getRepository("AppBundle:Person");

        $people = $personRepository->findAll();

        return $this->render('person/list.html.twig', [
            'people' => $people
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/person/import", name="person_import")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importAction(Request $request)
    {
        $importParameters = new PersonImportParameters();
        $form = $this->createFormBuilder($importParameters)
            ->add('file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $importer = $this->get('orbital.person_importer');
            $importer->import($importParameters);

            return $this->redirectToRoute('person_list');
        }

        return $this->render('person/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/person/create", name="person_create")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(new PersonType(), $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute(
                'person_detail',
                ['id' => $person->getId()]
            );
        }

        return $this->render('person/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/person/{id}", name="person_detail")
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction($id)
    {
        $doctrine = $this->getDoctrine();
        $personRepository = $doctrine->getRepository("AppBundle:Person");

        /** @var Person $person */
        $person = $personRepository->find($id);
        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id ' . $id
            );
        }

        $badges = $doctrine->getRepository('AppBundle:BadgeHolder')
            ->findBy([
                'person' => $person->getId()
            ], ['date_awarded' => 'DESC']);

        $records = $doctrine->getRepository('AppBundle:Record')
            ->findByPerson($person->getId());

        $scoreRepository = $doctrine->getRepository('AppBundle:Score');
        $recent_scores = $scoreRepository
            ->findBy([
                'person' => $person->getId()
            ], ['date_shot' => 'DESC'], 5);

        $pbs = $scoreRepository->getPersonalBests($person);

        return $this->render('person/detail.html.twig', [
            'person' => $person,
            'badges' => $badges,
            'records' => $records,
            'scores' => $recent_scores,
            'pbs' => $pbs
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/person/{id}/edit", name="person_edit")
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);
        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id ' . $id
            );
        }

        $form = $this->createForm(new PersonType(), $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute(
                'person_detail',
                ['id' => $person->getId()]
            );
        }

        return $this->render('person/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/person/{id}/reassess", name="person_handicap_reassess")
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handicapReassessAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);
        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id ' . $id
            );
        }

        $data = [];
        $form = $this->createForm(new ReassessType(), $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->get('orbital.handicap.manager')->reassess($person, $data['start_date'], $data['end_date']);

            return $this->redirectToRoute(
                'person_detail',
                ['id' => $person->getId()]
            );
        }

        return $this->render('person/reassess.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/person/{id}/delete", name="person_delete")
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->find($id);

        if (!$person) {
            throw $this->createNotFoundException(
                'No person found for id ' . $id
            );
        }

        if ($request->isMethod("POST")) {
            //TODO probably shouldn't ever delete people...
            $em->remove($person);
            $em->flush();

            return $this->redirectToRoute('person_list');
        }

        return $this->render('person/delete.html.twig', [
            'person' => $person
        ]);
    }
}