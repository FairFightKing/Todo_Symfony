<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="tasks")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();
        $tasks = $pdo->getRepository(Task::class)->findAll();

        $task = new Task();
        $form = $this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($task);
            $pdo->flush();
        }

        return $this->render('task/index.html.twig',[
            'tasks' => $tasks,
            'add_task_form' => $form->createView()
        ]);
    }
    /**
     * @Route("/task/edit/{id}",name="edit_task")
     * */
    public function edit(Task $task=null, Request $request)
    {
        if ($task != null) {
            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($task);
                $pdo->flush();
            }

            return $this->render('task/edit.html.twig', [
                'task' => $task,
                'form_task_edit' => $form->createView()
            ]);
        } else {
            $this->addFlash('error', 'Task Not found');
            return $this->redirectToRoute('tasks');
        }
    }
    /**
     * @Route("/task/delete/{id}", name="delete_task")
     */
    public function delete(Task $task=null){
        if ($task != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($task);
            $pdo->flush();
            $this->addFlash('sucess', 'delete sucess');
        } else {
            $this->addFlash('error', 'task Not found');
        }
        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/task/status/{id}?from={from}?user={user}", name="modify_status_task")
     */
    public function status(Task $task=null,$from=null,$user=null){
        if ($task!=null) {
            $pdo = $this->getDoctrine()->getManager();
            $task->setStatus(!$task->getStatus());
            $pdo->persist($task);
            $pdo->flush();

        } else {
            $this->addFlash('error', 'Task Not found');
        }
        if ($from === 'task'){
            return $this->redirectToRoute('tasks');
        } elseif ($from === 'user'){
            return $this->redirectToRoute('edit_user',['id' => $user]);
        } else {
            $this->addFlash('error', 'Not valid arg from');
            return $this->redirectToRoute('users');
        }
    }
}
