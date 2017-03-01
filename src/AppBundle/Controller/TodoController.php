<?php

namespace AppBundle\Controller;

use AppBundle\Entity;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction()
    {
        $todos=$this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
        return $this->render('todo/index.html.twig',array("todos"=>$todos));
    }

    /**
     * @Route("/todos/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo=new Todo;
        $form=$this->createFormBuilder($todo)
        ->add('name',TextType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('category',TextType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('description',TextareaType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('priority',ChoiceType::class, array("choices"=>array("Low"=>"Low","Normal"=>"Normal","High"=>"High"),'attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('due_date',DatetimeType::class, array('attr'=>array("class"=>"formcontrol","style"=>"margin-bottom:15px")))
        ->add('save',SubmitType::class, array('label'=>'Create Todo','attr'=>array("class"=>"btn btn-primary","style"=>"margin-bottom:15px")))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //die("PASSED");
            $name=$form['name']->getData();
            $category=$form['category']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $dueDate=$form['due_date']->getData();

        }
        return $this->render('todo/create.html.twig',array("form"=>$form->createView()));
    }

    /**
     * @Route("/todos/edit/{id}", name="todo_edit")
     */
    public function editAction($id,Request $request)
    {
    
        return $this->render('todo/edit.html.twig');
    }

    /**
     * @Route("/todos/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
    
        return $this->render('todo/details.html.twig');
    }
}
