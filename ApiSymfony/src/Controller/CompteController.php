<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;

use App\Repository\CompteRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/api", name="api")
*/
class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="add_compte", methods={"POST", "GET"})
     * @IsGranted("ROLE_SUPER_ADMIN")
    */

    public function addcompte(Request $request, EntityManagerInterface $entityManager, PartenaireRepository $partenaire ,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $compte = new Compte();

        $form=$this->createForm(CompteType::class,$compte);
        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);

        $compte->setNumerocompte(random_int(539004, 9805843));
        $compte->setSolde(0);
        $part=$partenaire->findOneBy(["ninea" =>$values["ninea"]]);
        $compte->setPartenaire($part);

        $errors = $validator->validate($compte);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }

        $entityManager->persist($compte);
        $entityManager->flush();

        $data = [
            'status' => 201,
            'message' => 'Le compte vient d\'etre créer.'
        ];
        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/comptes/{id}", name="show_compte", methods={"GET","POST"})
     */
    public function listcompte(Compte $compte,SerializerInterface $serializer, CompteRepository $compteRepository)
    {
        $compte = $compteRepository->findAll($compte->getId());
        $data = $serializer->serialize($compte, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Types' => 'applications/json'
        ]);

    }

}
