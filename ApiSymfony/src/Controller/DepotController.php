<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Form\DepotType;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
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

class DepotController extends AbstractController
{
    /**
     * @Route("/depot", name="add_depot", methods={"POST", "GET"})
     * @IsGranted("ROLE_CAISSIER")
    */

    public function addDepot(Request $request, EntityManagerInterface $em ,CompteRepository $compte,UserRepository $user ,SerializerInterface $serializer,ValidatorInterface $validator)
    {
            $depot = new Depot();

            $form=$this->createForm(DepotType::class,$depot);
            $form->handleRequest($request);
            $values=$request->request->all();
            $form->submit($values);

            $depot->setDatedepot(new \DateTime);
            $util=$user->findOneBy(["username" =>$values["username"]]);
            $depot->setUser($util);

            $cpte=$compte->findOneBy(["numerocompte" =>$values["numerocompte"]]);
            $cpte->setSolde($cpte->getSolde() + $values['montant']);
            $depot->setCompte($cpte);

            $errors = $validator->validate($depot);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $em->persist($depot);
            $em->flush();

            $data = [
                'status' => 201,
                'message' => 'Une depot a été faite'
            ];

            return new JsonResponse($data, 201);

    }

    /**
     * @Route("/listdepot/{id}", name="show_depot", methods={"GET","POST"})
     */
    public function listdepot(Depot $depot,SerializerInterface $serializer, DepotRepository $depotRepository)
    {
        $depot = $depotRepository->findAll($depot->getId());
        $data = $serializer->serialize($depot, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Types' => 'applications/json'
        ]);

    }
}
