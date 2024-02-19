<?php
namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Admission;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use App\Repository\AdmissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class PatientController extends AbstractController
{
    #[Route('/patients', name: 'patient_list')]
    public function patient_list(PatientRepository $patientRepository): Response
    {
        $patients = $patientRepository->findAll();
        return $this->render('patient/list.html.twig', [
            'patients' => $patients,
            'notfound' => false,
        ]);
    }

    #[Route('/patients/search', name: 'patient_search')]
    public function patient_search(Request $request, PatientRepository $patientRepository): Response
    {
        $id = $request->get('search');

        if ($id) {
            $patient = $patientRepository->find($id);
            if ($patient) {
                return $this->render('patient/list.html.twig', [
                    'patients' => [$patient],
                    'notfound' => false,
                ]);
            }
            else {
                return $this->render('patient/list.html.twig', [
                    'patients' => $patientRepository->findAll(),
                    'notfound' => true,
                ]);
            }
        }
        else {
            return $this->redirectToRoute('patient_list', ['notfound' => false]);
        }
    }

    #[Route('/patients/create', name: 'patient_create')]
    public function patient_create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();

        $form = $this->createForm(PatientType::class, $patient)->add('save', SubmitType::class, [
            'label' => 'Create',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('patient_list');
        }

        return $this->render('patient/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/patients/{id}', name: 'patient_show')]
    public function patient_show(Patient $patient, AdmissionRepository $admissionRepository): Response
    {
        $admissions = $admissionRepository->findBy(['patient_id' => $patient->getId()], ['date_visit' => 'DESC']);
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
            'admissions' => $admissions,
        ]);
    }

    #[Route('/patients/{id}/edit', name: 'patient_edit')]
    public function patient_edit(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PatientType::class, $patient)->add('save', SubmitType::class, [
            'label' => 'Update',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('patient_list');
        }

        return $this->render('patient/edit.html.twig', [
            'form' => $form->createView(),
            'patient' => $patient,
        ]);
    }

    #[Route('/patients/{id}/delete', name: 'patient_delete', methods: ['POST', 'DELETE'])]
    public function patient_delete(Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($patient);
        $entityManager->flush();

        return $this->redirectToRoute('patient_list');
    }


}