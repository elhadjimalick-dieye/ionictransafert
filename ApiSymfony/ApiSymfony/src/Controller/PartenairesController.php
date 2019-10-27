<?php

namespace App\Controller;

use App\Entity\ClientEnvoie;
use App\Entity\ClientRetrait;
use App\Entity\Compte;
use App\Entity\Depot;
use App\Entity\Partenaire;
use App\Entity\Tarifs;
use App\Entity\Transaction;
use App\Entity\User;
use App\Form\CompteType;
use App\Form\EnvoieType;
use App\Form\PartenaireType;
use App\Form\RetraitType;
use App\Form\TransactionType;
use App\Form\UserType;
use App\Repository\ClientEnvoieRepository;
use App\Repository\ClientRetraitRepository;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class PartenairesController extends AbstractController
{
    /**
     * @Route("/pdf", methods={"GET"})
     */
    public function index(PartenaireRepository $partenaireRepository)
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('partenaires/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("partenaires.pdf", [
            "Attachment" => true,
        ]);

        // return $this->renderView('partenaires/index.html.twig', [
        //     'partenaires' => $partenaireRepository->findAll(),
        // ]);
    }

    /**
     * @Route("/partenaires", name="partenaires")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, UserRepository $user, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $partenaire = new Partenaire();

        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);

        $entrep = $values['nom'];
        $recup = substr($entrep, 0, 2);
        while (true) {
            if (time() % 1 == 0) {
                $alea = rand(100000000, 999999999);
                break;
            }
            slep(1);
        }
        $concat = $recup . $alea;

        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);
        $form->submit($values);
        $compte->setSolde(0);
        $compte->setNumcompte($concat);
        $compte->setPartenaire($partenaire);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $file = $request->files->all()[
            "imageName"
        ];
        $form->submit($values);
        $user->setPartenaire($partenaire);
        $user->setcompte($compte);
        $user->setPassword($passwordEncoder->encodePassword($user, $values['password']));
        $profil = $user->getProfil();
        $role = [];
        if ($profil == "adminpartenaire") {
            $role = ["ROLE_ADMIN"];
        } elseif ($profil == "caissier") {
            $role = ["ROLE_CAISSIER"];
        }
        $user->setRoles($role);

        $entityManager = $this->getDoctrine()->getManager();
        $errors = $validator->validate($user);

        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json',
            ]);
        }
        $user->setImageFile($file);
        $entityManager->persist($partenaire);
        $entityManager->persist($compte);
        $entityManager->persist($user);

        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le partenaire a été créé',
        ];
        return new JsonResponse($data, 201);

    }
    /**
     * @Route("/adduser", name="usersimple")
     */
    public function adduser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent(), true);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $form->submit($values);
        $user->setPassword($passwordEncoder->encodePassword($user, $values['password']));
        $profil = $user->getProfil();
        $role = [];
        if ($profil == "adminpartenaire") {
            $role = ["ROLE_ADMIN_PARTENAIRE"];
        } elseif ($profil == "user") {
            $role = ["ROLE_USER_SIMPLE"];
        }
        $user->setRoles($role);

        $entityManager = $this->getDoctrine()->getManager();
        $errors = $validator->validate($user);

        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);
        $form->submit($values);
        $partenaire->setUser($user);

        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json',
            ]);
        }
        $entityManager->persist($user);
        $entityManager->persist($partenaire);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'L\'utilisateur a été créé',
        ];
        return new JsonResponse($data, 201);

    }

    /**
     * @Route("/envoie", methods={"POST"})
     *
     */
    public function envoie(Request $request, EntityManagerInterface $entityManager)
    {

        $trans = new Transaction();
        $form = $this->createForm(TransactionType::class, $trans);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);

        $trans->setDate(new \Datetime());
        $trans->setStatut('envoie');
        while (true) {
            if (time() % 1 == 0) {
                $alea = rand(99999999, 10000000);
                break;
            }
            slep(1);
        }
        $argent = $form->get('montant')->getData();
        $frais = $this->getDoctrine()->getRepository(Tarifs::class)->findAll();

        foreach ($frais as $values) {
            $values->getMin();
            $values->getMax();
            $values->getValeur();
            if ($argent >= $values->getmin() && $argent <= $values->getMax()) {
                $commission = $values->getValeur();
                $trans->setFrais($commission);
                $trans->setCommiEvoie(($commission * 10) / 100);
                $trans->setCommitRetrait(($commission * 20) / 100);
                $trans->setCommiSystem(($commission * 40) / 100);
                $trans->setCommiEtat(($commission * 30) / 100);
            }
        }
        $trans->setCode($alea);

        $arg = $this->getUser();

        $comp = $arg->getCompte();

        $solde = $comp->getSolde();
        $trans->setUser($arg);

        $envoie = new ClientEnvoie();
        $form = $this->createForm(EnvoieType::class, $envoie);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);

        $trans->setClientEnvoie($envoie);

        $retrait = new ClientRetrait();
        $form = $this->createForm(RetraitType::class, $retrait);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);

        $trans->setClientRetrait($retrait);

        if ($solde > $trans->getMontant()) {
            $mos = $solde - $trans->getMontant() + $trans->getCommiEvoie();

            $comp->setSolde($mos);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($envoie);
            $entityManager->persist($retrait);
            $entityManager->persist($comp);
            $entityManager->persist($trans);
            $entityManager->flush();
            /* $config = array(
            'clientId' => 'dT2dAajN6AjG0Doa3Tbz7YkXzXYDnAyw',
            'clientSecret' => 'R9d6kAXZ8qeWa7jn',
            );

            $osms = new Osms($config);

            // retrieve an access token
            $response = $osms->getTokenFromConsumerKey();

            if (!empty($response['access_token'])) {
            $senderAddress = 'tel:+221772086894';
            $receiverAddress = 'tel:+221' . $retrait->getTelR();
            $message = 'Bonjour ' . $retrait->getNomR() . ', Vous venez de recevoir un transfert de '
            . $envoie->getTransaction()->getMontant() . 'FCFA de la part de ' . $envoie->getNomE() . ' '
            . $envoie->getTelE() . ' que vous pouvez retirer sur tout le réseau SenebsTrqnsfert avec ce code: ' . $envoie->getCode() . '. Merci !';
            $senderName = 'senebaTransfert';

            $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
            } else {
            // error
            }*/
            return new Response('Le transfert a été effectué avec succés.        Voici le code : ' . $trans->getCode());
        }
        return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');

    }

    /**
     * @Route("/partenaire", name="partenaire", methods={"GET"})
     */
    public function listePartenaire(SerializerInterface $serializer, PartenaireRepository $partenaireRepository)
    {
        $partenaire = $partenaireRepository->findAll();
        $data = $serializer->serialize($partenaire, 'json', [
            'groups' => ['list'],
        ]);
        return new Response($data, 201);
    }

    /**
     * @Route("/listedepot", name="listedepot", methods={"GET"})
     */
    public function listerDepot(SerializerInterface $serializer, DepotRepository $depotRepository)
    {
        $depot = $depotRepository->findAll();
        $data = $serializer->serialize($depot, 'json', [
            'groups' => ['show'],
        ]);
        return new Response($data, 201);
    }

    /**
     * @Route("/compte", name="show_compte", methods={"GET", "POST"})
     */
    public function listerCompte(CompteRepository $compteRepository, SerializerInterface $serializer)
    {
        $compte = $compteRepository->findAll();
        $data = $serializer->serialize($compte, 'json', [
            'groups' => ['default'],
        ]);
        return new Response($data, 201);
    }

    /**
     * @Route("/liste_transaction", name="list_transaction", methods={"GET", "POST"})
     */
    public function listetrans(TransactionRepository $trans, SerializerInterface $serializer)
    {
        $transac = $trans->findAll();
        $data = $serializer->serialize($transac, 'json', [
            'groups' => ['show'],
        ]);

        return new Response($data, 201);
    }
    /**
     * @route("/listeretrait", name="listeretrait", methods={"GET", "POST"})
     */
    public function listerretrait(ClientRetraitRepository $retrait, SerializerInterface $serializer)
    {
        $retirer = $retrait->findAll();
        $data = $serializer->serialize($retirer, 'json', [
            'groups' => ['show'],
        ]);
        return new JsonResponse($data, 201);
    }
    /**
     * @route("/listerenvoi", name="listerenvoi", methods={"GET", "POST"})
     */
    public function listerenvoi(ClientEnvoieRepository $envoie, SerializerInterface $serializer)
    {
        $envoyer = $envoie->findAll();
        $data = $serializer->serialize($envoyer, 'json', [
            'groups' => ['show'],
        ]);
        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/retrait", name="tranfertok" ,methods={"POST"})
     */
    public function retrait(Request $request, EntityManagerInterface $entityManager, TransactionRepository $transaction)
    {

        $trans = new Transaction();
        $form = $this->createForm(TransactionType::class, $trans);
        $user = $this->getUser();
        $data = $request->request->all();
        $form->submit($data);
        $toto = $data['code'];
        $tro = $transaction->findOneBy(['code' => $toto]);

        if (!$tro) {
            return new Response('Le code saisi est incorecte .Veuillez ressayer un autre  ');
        }

        $statut = $tro->getStatut();
        if ($tro->getCode() == $toto && $statut == "retrait") {
            return new Response('Le code saisi est déjà retiré  ');
        }

        $tro->getClientRetrait()->setCin($data["cni"]);
        $tro->getClientRetrait()->setDateretrait(new \DateTime());
        $tro->setStatut("retrait");
        $tro->setUser($user);
        $entityManager->flush();

        return new Response('le retrait de  ' . $tro->getMontant())+' est fait';

    }
}
