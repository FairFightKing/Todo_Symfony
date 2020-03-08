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
    public function edit()
    {

    }
}
