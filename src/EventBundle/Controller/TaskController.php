<?php

namespace EventBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EventBundle\Entity\Task;
use EventBundle\Form\TaskType;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('EventBundle:Task')->findAll();

        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    /**
     * Creates a new Task entity.
     *
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm('EventBundle\Form\TaskType', $task);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $entitys = $em->getRepository('EventBundle:Task')->findAll();
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($task);
            $em->flush();

            return $this->render('EventBundle:Default:calendar.html.twig', array(
                'task' => $task,
                'entitys'=>$entitys,
                'form' => $form->createView(),
            ));
        }


        return $this->render('EventBundle:Default:calendar.html.twig', array(
            'task' => $task,
            'entitys'=>$entitys,
            'form' => $form->createView(),
        ));
    }
        public function addTask($task){
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->render('EventBundle:Default:calendar.html.twig', array(
                'task' => $task));
        }
    public function showJsonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EventBundle:task')->findOne($id);
        $response = new Response(json_encode($entity->toArray()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function dropAction($id){
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository()->find($id);

    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction(Request $request, Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('EventBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
        }

        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a Task entity.
     *
     * @param Task $task The Task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
