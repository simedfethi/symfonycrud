<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    #[Route('/', name: 'todo_list')]
    public function index(): Response
    {
        $tasks=$this->getDoctrine()->getRepository(Task::class)->findBy([],[
            'id' => 'DESC'
        ]);

        return $this->render(
          'index.html.twig',
          [
              'controller_name' => 'home',
              'tasks'=>$tasks ,
          ]
        );
    }

    /**
     * @Route("/create",name="create_task" , methods={"POST"})
     */
    public function create (Request $request)
    {
        $entityManager= $this->getDoctrine()->getManager();
      $title=trim($request->request->get('title'));

      if (empty($title))
        return  $this->redirectToRoute('todo_list');

        $task = new Task();
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();
        return  $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/delete/{id}",name="delete_task")
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete (Task $task){
        $entitiesmanager= $this->getDoctrine()->getManager();

        $entitiesmanager->remove($task);
        $entitiesmanager->flush();
        return $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/switchstatus/{id}",name="switchstatus")
     */
    public function switchstatus($id)
    {
        $entitiesmanager= $this->getDoctrine()->getManager();
        $task= new Task();
        $task=  $entitiesmanager->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());
        $entitiesmanager->persist($task);
        $entitiesmanager->flush();
        return $this->redirectToRoute('todo_list');
    }
}
