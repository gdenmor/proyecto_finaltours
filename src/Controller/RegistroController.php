<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistroController extends AbstractController
{

    #[Route('/register',name:'app_registro')]
    public function registro(Request $request, EntityManagerInterface $entity, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $foto = $form->get('foto')->getData();

            if ($foto) {
                $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $foto->guessExtension();
                $foto->move('public/css/imagenes/' . $newFilename, $newFilename);
                $user->setFoto($newFilename);
            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(["ROLE_USER"]);

            $entity->persist($user);
            $entity->flush();
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }
}
