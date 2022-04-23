<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class CourseController extends AbstractController
{
   
    #[Route('/course', name: 'course_index')]
    public function courseIndexAction(): Response
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        if ($courses == null) {
            $this->addFlash('errorEmpty', 'List course is empty');
        } else {
            return $this->render('course/index.html.twig', [
                'courses' => $courses,
            ]);
        }
    }
    #[Route('/course/detail/{id}', name: 'course_detail')]
    public function courseDetailAction($id): Response
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if ($course == null) {
            $this->addFlash('error', 'course not found');
            return $this->redirect('course_index');
        } else {
            return $this->render('course/detail.html.twig', [
                'course' => $course
            ]);
        }
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/course/edit/{id}', name: 'course_edit')]
    public function courseEditAction(Request $request, $id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success',"course has been updated successfully !");
          
            return $this->redirectToRoute("course_index");
        }
        return $this->render('course/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/course/add', name: 'course_add')]
    public function courseAddAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash('success',"Course has been added successfully !");
            return $this->redirectToRoute("course_index");
        }
        return $this->render('course/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/course/delete/{id}', name: 'course_delete')]
    public function courseDeleteAction($id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        if($course ==null){
              $this->addFlash('error','course not found');
              return $this->redirect('course_index');
        }
        else{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($course);
            $manager->flush();
            $this->addFlash('success','course has been delete successfully');
           return $this->redirectToRoute('course_index');
        }
    }
}