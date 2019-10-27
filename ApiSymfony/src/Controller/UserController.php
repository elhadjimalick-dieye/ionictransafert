<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CompteRepository;
use App\Repository\UserRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* @Route("/api", name="api")
* @IsGranted("ROLE_SUPER_ADMIN")
*/

class UserController extends AbstractController
{

    /**
     * @Route("/user", name="add_user", methods={"POST", "GET"})
    */

    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder,PartenaireRepository $partenaire,CompteRepository $compte ,EntityManagerInterface $entityManager,SerializerInterface $serializer,ValidatorInterface $validator)
    {
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
            $part=$partenaire->findOneBy(["ninea" =>$values["ninea"]]);
            $user->setPartenaire($part);
            $cpte=$compte->findOneBy(["numerocompte" =>$values["numerocompte"]]);
            $user->setCompte($cpte);
            $user->setStatus("actif");
            $user->setProfile($values['profile']);
            if ($values['profile']=="admin") {
                $user->setRoles(["ROLE_ADMIN_PARTENAIRE"]);
            }elseif ($values['profile']=="user") {
                $user->setRoles(["ROLE_USER"]);
            }

            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $data = [
                'status1' => 201,
                'message1' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
    }

    /**
     * @Route("/caissier", name="add_caissier", methods={"POST", "GET"})
    */

    public function addUCaissier(Request $request, UserPasswordEncoderInterface $passwordEncoder,PartenaireRepository $partenaire,CompteRepository $compte ,EntityManagerInterface $entityManager,SerializerInterface $serializer,ValidatorInterface $validator)
    {
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
            $user->setStatus("actif");
            $user->setProfile("caissier");
            $user->setRoles(["ROLE_CAISSIER"]);

            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $data = [
                'status1' => 201,
                'message1' => 'Un caissier a été créé'
            ];

            return new JsonResponse($data, 201);
    }

    /**
     * @Route("/login", name="login", methods={"POST", "GET"})
    */

    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }

    // /**
    //  * @Route("/users/{id}", name="show_user", methods={"GET","POST"})
    //  */
    // public function list(User $user,SerializerInterface $serializer, UserRepository $userRepository)
    // {
    //     $user = $userRepository->findAll($user->getId());
    //     $data = $serializer->serialize($user, 'json', [
    //         'groups' => ['show']
    //     ]);
    //     return new Response($data, 200, [
    //         'Content-Types' => 'applications/json'
    //     ]);

    // }

    /**
     * @Route("/token", name="token", methods={"POST", "GET"})
     * @param Request $request
     * @param JWTEncoderInterface $JWTEncoder
     * @return JsonResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function token(Request $request, JWTEncoderInterface $JWTEncoder)
    {
        $values=json_decode($request->getContent());
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'username' => $values->username,
        ]);
        if (!$user) {
            return new JsonResponse(['l\'utilisateur n\'existe pas']);
        }
        $isValid = $this->passwordEncoder->isPasswordValid($user, $values->password);

        if (!$isValid) {
            return new JsonResponse(['veuillez saisir un mot de pass']);
        }

        if ($user->getStatus() == 'actif') {

            return new JsonResponse(['Veuillez contacter votre administrateur vous etes bloqué']);
        }

        $token = $JWTEncoder->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600,
                'roles'=> $user->getRoles() // 1 hour expiration
            ]);
        return new JsonResponse(['token' => $token]);
    }

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
    $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/liste_users" , name="liste_users" ,methods={"GET", "POST"})
     */
    public function listerUser(UserRepository $userRepository, SerializerInterface $serializer)
    {

        $user = $userRepository->findAll();
        $data = $serializer->serialize($user, 'json', [
            'group' => ['show'],
        ]);
        return new Response($data, 201);

    }
}
