<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class StudentController extends AbstractController
{
    #[Route('/student', name: 'student_index')]
    public function studentIndex(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', 
        [
            'students' => $students
        ]);
    }
    
    #[Route('/student/detail/{id}', name: 'student_detail')]
    public function studentDetail($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('Error', 'Student not found !');
            return $this->redirectToRoute('student_index');
        } else { //$student != null
            return $this->render(
                'student/detail.html.twig',
                [
                    'student' => $student
                ]
            );
        }
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function deleteStudent($id)
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($student);
            $manager->flush();
            $this->addFlash('error', "Student has been deleted!");
            return $this->redirectToRoute('student_index');
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/add', name: 'student_add')]
    public function addStudent (Request $request) {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //l???y d??? li???u ???nh t??? file upload
            $image = $student->getImage();
            //t???o t??n uniq
            $imgName = uniqid();
            //l???y ra ??u??i c???a ???nh
            $imgExtension = $image->guessExtension();
            // g???p t??n m???i + ??u??i ????? t???o th??nh file ???nh ho??n thi???n
            $imageName = $imgName . "." . $imgExtension;
            // di chuy???n ???nh v??o th?? m???c ch??? ?????nh
            try {
                $image->move(
                    $this->getParameter('student_image'),
                    $imageName
                    //l??u ?? c???n khai b??o tham s??? ???????ng d???n th?? m???c 
                    // cho "student_cover" ??? file config/service.yaml
                );
            } catch (FileException $e) {
                //throwException($e);
            }
            // l??u t??n v??o database
            $student->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash('success', "Student has been added successfully !");
            return $this->redirectToRoute("student_index");
        }
        return $this->render('student/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_STAFF")
     */
    #[Route('/student/edit/{id}', name: 'student_edit')]
    public function editStudent (Request $request, $id) {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if ($student == null) {
            $this->addFlash('error', 'Student not found');
            return $this->redirectToRoute("student_index");
        } else {
            $form = $this->createForm(StudentType::class, $student);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // l???y d??? li???u ???nh t??? form
                $file = $form['image']->getData();
                // check file ???nh upload c?? b??? null hay kh??ng
                if ($file != null) {
                    //l???y d??? li???u ???nh t??? file upload
                    $image = $student->getImage();
                    //t???o t??n uniq
                    $imgName = uniqid();
                    //l???y ra ??u??i c???a ???nh
                    $imgExtension = $image->guessExtension();
                    // g???p t??n m???i + ??u??i ????? t???o th??nh file ???nh ho??n thi???n
                    $imageName = $imgName . "." . $imgExtension;
                    // di chuy???n ???nh v??o th?? m???c ch??? ?????nh
                    try {
                        $image->move(
                            $this->getParameter('student_image'),
                            $imageName
                            //l??u ?? c???n khai b??o tham s??? ???????ng d???n th?? m???c 
                            // cho "student_cover" ??? file config/service.yaml
                        );
                    } catch (FileException $e) {
                        //throwException($e);
                    }
                    // l??u t??n v??o database
                    $student->setImage($imageName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($student);
                $manager->flush();
                $this->addFlash('success', "Student has been added successfully!");
                return $this->redirectToRoute("student_index");
            }
            return $this->render('student/edit.html.twig', [
                'form' => $form->createView(),
                'student'=>$student
            ]);
        }
    }

}