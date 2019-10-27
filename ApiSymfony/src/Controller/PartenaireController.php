<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Form\UserType;

use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\PartenaireRepository;

use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* @Route("/api")
*/
class PartenaireController extends AbstractController
{
    /**
     * @Route("/pdf", name="part", methods={"GET", "POST"})
     */
    public function index(PartenaireRepository $PartenaireRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('partenaire/index.html.twig', [
            'partenaires'=> $PartenaireRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("contrat_de_prestation.pdf", [
            "Attachment" => false
        ]);

    }

    /**
     * @Route("/partenaire", name="add_partenaire", methods={"POST", "GET"})
     * @IsGranted("ROLE_SUPER_ADMIN")
    */

    public function addPartenaire(Request $request, EntityManagerInterface $em,CompteRepository $compte ,UserRepository $user,PartenaireRepository $partenaire ,UserPasswordEncoderInterface $passwordEncoder,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $partenaire= new Partenaire();

        $form=$this->createForm(PartenaireType::class,$partenaire);
        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);

        $compte = new Compte();

        $form=$this->createForm(CompteType::class,$compte);
        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);

        $compte->setNumerocompte(random_int(539004, 9805843));
        $compte->setSolde(0);
        
        $compte->setPartenaire($partenaire);
        
        $user = new User();

        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $values=$request->request->all();
        $form->submit($values);
        $files=$request->files->all()['imageName'];
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $user->setImageFile($files);
        $user->setUpdatedAt(new \DateTime());
        $user->setPartenaire($partenaire);
        $user->setCompte($compte);
        $user->setStatus("actif");
        $user->setProfile("admin");
        $user->setRoles(["ROLE_ADMIN_PARTENAIRE"]);

        
        $errors = $validator->validate($partenaire);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }

        $em->persist($partenaire);
        $em->persist($compte);
        $em->persist($user);

        $em->flush();
            $data = [
                'status' => 201,
                'message' => 'Un partenaire a été ajouté'
            ];

            return new JsonResponse($data, 201);
    }

    /**
     * @Route("/partenaires/{id}", name="show_partenaire", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function list(Partenaire $partenaire,SerializerInterface $serializer, PartenaireRepository $PartenaireRepository) : Response
    {
        $users = $PartenaireRepository->findAll($partenaire->getId());
        $data = $serializer->serialize($users, 'json', [
            'groups' => ['show']
        ]);
        return new Response($data, 200, [
            'Content-Types' => 'applications/json'
        ]);

    }

}
