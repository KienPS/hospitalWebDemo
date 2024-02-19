<?php
namespace App\Controller;

use App\Entity\Admission;
use App\Entity\Patient;
use App\Form\AdmissionType;
use App\Repository\AdmissionRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdmissionController extends AbstractController
{
    #[Route('/admissions', name: 'admission_list')]
    public function admission_list(AdmissionRepository $admissionRepository): Response
    {
        $admissions = $admissionRepository->findAll();
        return $this->render('admission/list.html.twig', [
            'admissions' => $admissions,
            'notfound' => false,
        ]);
    }

    #[Route('/admissions/search', name: 'admission_search')]
    public function patient_search(Request $request, AdmissionRepository $admissionRepository): Response
    {
        $id = $request->get('search');

        if ($id) {
            $admission = $admissionRepository->find($id);
            if ($admission) {
                return $this->render('admission/list.html.twig', [
                    'admissions' => [$admission],
                    'notfound' => false,
                ]);
            }
            else {
                return $this->render('admission/list.html.twig', [
                    'admissions' => $admissionRepository->findAll(),
                    'notfound' => true,
                ]);
            }
        }
        else {
            return $this->redirectToRoute('admission_list', ['notfound' => false]);
        }
    }

    #[Route('/admissions/create', name: 'admission_create')]
    public function admission_create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $admission = new Admission();

        $form = $this->createForm(AdmissionType::class, $admission)->add('save', SubmitType::class, [
            'label' => 'Create',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($admission);
            $entityManager->flush();

            return $this->redirectToRoute('admission_list');
        }

        return $this->render('admission/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admissions/{id}', name: 'admission_show')]
    public function admission_show(Admission $admission): Response
    {
        return $this->render('admission/show.html.twig', [
            'admission' => $admission,
        ]);
    }

    #[Route('/admissions/{id}/edit', name: 'admission_edit')]
    public function admission_edit(Request $request, Admission $admission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdmissionType::class, $admission)->add('save', SubmitType::class, [
            'label' => 'Update',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admission_list');
        }

        return $this->render('admission/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}