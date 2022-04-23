<?php

namespace App\Controller;

use App\Form\ClassType;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
/**
 * @IsGranted("ROLE_USER")
 */
class ClassController extends AbstractController
{
    
    #[Route('/class', name: 'class_index')]
    public function classIndexAction(): Response
    {
        $classes = $this->getDoctrine()->getRepository(Classroom::class)->findAll();
        if ($classes == null) {
            $this->addFlash('errorEmpty', 'List class is empty');
        } else {
            return $this->render('class/index.html.twig', [
                'classes' => $classes,
            ]);
        }
    }
    #[Route('/class/detail/{id}', name: 'class_detail')]
    public function classDetailAction($id): Response
    {
        $class = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        if ($class == null) {
            $this->addFlash('error', 'class not found');
            return $this->redirect('class_index');
        } else {
            return $this->render('class/detail.html.twig', [
                'class' => $class
            ]);
        }
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/class/edit/{id}', name: 'class_edit')]
    public function classEditAction(Request $request, $id)
    {
        $class = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassType::class, $class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($class);
            $manager->flush();
            $this->addFlash('success',"class has been updated successfully !");
            return $this->redirectToRoute("class_index");
        }
        return $this->render('class/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
   /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/class/add', name: 'class_add')]
    public function classAddAction(Request $request)
    {
        $class = new Classroom();
        $form = $this->createForm(ClassType::class, $class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($class);
            $manager->flush();
            $this->addFlash('success',"class has been added successfully !");
            return $this->redirectToRoute("class_index");
        }
        return $this->render('class/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/class/delete/{id}', name: 'class_delete')]
    public function classDeleteAction($id)
    {
        $class = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        if($class ==null){
              $this->addFlash('error','class not found');
        }
        elseif(count($class->getStudent()) >= 1){
            $this->addFlash("error", "Can't delete a class that has students in it!");
        }
        else{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($class);
            $manager->flush();
            $this->addFlash('success','class has been delete successfully');
        }
        return $this->redirectToRoute('class_index');
    }
}