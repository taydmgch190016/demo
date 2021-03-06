<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_index')]
    public function teacherIndex(): Response
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAll();
        return $this->render('teacher/index.html.twig', 
        [
            'teachers' => $teachers
        ]);
    }

    #[Route('/teacher/detail/{id}', name: 'teacher_detail')]
    public function teacherDetail($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('Error', 'teacher not found !');
            return $this->redirectToRoute('teacher_index');
        } else { //$teacher != null
            return $this->render(
                'teacher/detail.html.twig',
                [
                    'teacher' => $teacher
                ]
            );
        }
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/delete/{id}', name: 'teacher_delete')]
    public function deleteTeacher($id)
    {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($teacher);
            $manager->flush();

            return $this->redirectToRoute('teacher_index');
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/add', name: 'teacher_add')]
    public function addTeacher (Request $request) {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //l???y d??? li???u ???nh t??? file upload
            $image = $teacher->getImage();
            //t???o t??n uniq
            $imgName = uniqid();
            //l???y ra ??u??i c???a ???nh
            $imgExtension = $image->guessExtension();
            // g???p t??n m???i + ??u??i ????? t???o th??nh file ???nh ho??n thi???n
            $imageName = $imgName . "." . $imgExtension;
            // di chuy???n ???nh v??o th?? m???c ch??? ?????nh
            try {
                $image->move(
                    $this->getParameter('teacher_image'),
                    $imageName
                    //l??u ?? c???n khai b??o tham s??? ???????ng d???n th?? m???c 
                    // cho "teacher_cover" ??? file config/service.yaml
                );
            } catch (FileException $e) {
                //throwException($e);
            }
            // l??u t??n v??o database
            $teacher->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash('success', "teacher has been added successfully !");
            return $this->redirectToRoute("teacher_index");
        }
        return $this->render('teacher/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/teacher/edit/{id}', name: 'teacher_edit')]
    public function editTeacher (Request $request, $id) {
        $teacher = $this->getDoctrine()->getRepository(Teacher::class)->find($id);
        if ($teacher == null) {
            $this->addFlash('error', 'teacher not found');
            return $this->redirectToRoute("teacher_index");
        } else {
            $form = $this->createForm(TeacherType::class, $teacher);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // l???y d??? li???u ???nh t??? form
                $file = $form['image']->getData();
                // check file ???nh upload c?? b??? null hay kh??ng
                if ($file != null) {
                    //l???y d??? li???u ???nh t??? file upload
                    $image = $teacher->getImage();
                    //t???o t??n uniq
                    $imgName = uniqid();
                    //l???y ra ??u??i c???a ???nh
                    $imgExtension = $image->guessExtension();
                    // g???p t??n m???i + ??u??i ????? t???o th??nh file ???nh ho??n thi???n
                    $imageName = $imgName . "." . $imgExtension;
                    // di chuy???n ???nh v??o th?? m???c ch??? ?????nh
                    try {
                        $image->move(
                            $this->getParameter('teacher_image'),
                            $imageName
                            //l??u ?? c???n khai b??o tham s??? ???????ng d???n th?? m???c 
                            // cho "teacher_cover" ??? file config/service.yaml
                        );
                    } catch (FileException $e) {
                        //throwException($e);
                    }
                    // l??u t??n v??o database
                    $teacher->setImage($imageName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($teacher);
                $manager->flush();
                $this->addFlash('success', "teacher has been added successfully !");
                return $this->redirectToRoute("teacher_index");
            }
            return $this->render('teacher/edit.html.twig', [
                'form' => $form->createView(),
                'teacher'=>$teacher
            ]);
        }
    }

}