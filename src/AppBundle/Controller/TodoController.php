<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;

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

            $now=new \DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($dueDate);
            $todo->setCreateDate($now);

            $query=$this->getDoctrine()->getManager();
            $query->persist($todo);
            $query->flush();

            $this->addFlash(
                'notice',
                'Todo Added Successfully!'
            );

            return $this->redirectToRoute('todo_list');    

        }
        return $this->render('todo/create.html.twig',array("form"=>$form->createView()));
    }

    /**
     * @Route("/todos/edit/{id}", name="todo_edit")
     */
    public function editAction($id,Request $request)
    {
        $todo=$this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        $now=new \DateTime('now');    
        $todo->setName($todo->getName());
        $todo->setCategory($todo->getCategory());
        $todo->setDescription($todo->getDescription());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $todo->setCreateDate($now);

        $form=$this->createFormBuilder($todo)
        ->add('name',TextType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('category',TextType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('description',TextareaType::class, array('attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('priority',ChoiceType::class, array("choices"=>array("Low"=>"Low","Normal"=>"Normal","High"=>"High"),'attr'=>array("class"=>"form-control","style"=>"margin-bottom:15px")))
        ->add('due_date',DatetimeType::class, array('attr'=>array("class"=>"formcontrol","style"=>"margin-bottom:15px")))
        ->add('save',SubmitType::class, array('label'=>'Update Todo','attr'=>array("class"=>"btn btn-primary","style"=>"margin-bottom:15px")))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //die("PASSED");
            $name=$form['name']->getData();
            $category=$form['category']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $dueDate=$form['due_date']->getData();

            $now=new \DateTime('now');
            $query=$this->getDoctrine()->getManager();
            $todo=$this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
    
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($dueDate);
            $todo->setCreateDate($now);

            $query->flush();

            $this->addFlash(
                'notice',
                'Todo Updated Successfully!'
            );

            return $this->redirectToRoute('todo_list');    

        }
        return $this->render('todo/edit.html.twig',array("form"=>$form->createView()));
    }

    /**
     * @Route("/todos/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
        $details=$this->getDoctrine()
                ->getRepository('AppBundle:Todo')
                ->find($id);

        return $this->render('todo/details.html.twig',array('details'=>$details));
    }

    /**
     * @Route("/todos/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $todo=$this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);

        $em->remove($todo);
        $em->flush();

        $this->addFlash(
            'notice',
            'Todo Deleted Successfully!'
        );

        return $this->redirectToRoute('todo_list');  
        
    }
}
