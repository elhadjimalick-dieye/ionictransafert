<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Tarifs;
use App\Form\FraisType;
use App\Form\TransactionType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Flex\Response;

/**
 * @Route("/api", name="api")
 */
class TransactionController extends AbstractController
{
    /** 
     * @Route("/envoie", name="add_envoie", methods={"POST", "GET"})
     */

    public function addTransaction(Request $request, UserRepository $user, CompteRepository $compte, EntityManagerInterface $entityManager)
    {
        $envoie = new Transaction();

        $form = $this->createForm(TransactionType::class, $envoie);
        $form->handleRequest($request);
        $values = $request->request->all();
        $form->submit($values);

        while (true) {
            if (time() % 1 == 0) {
                $alea = rand(100, 1000000000);
                break;
            } else {
                slep(1);
            }
        }

        $envoie->setCode($alea);
        $envoie->setType("envoyer");
        $envoie->setDateEnvoie(new \Datetime());
        $util = $this->getUser();
        $envoie->setUser($util);

        $cpte = $util->getCompte();
        $envoie->setCompte($cpte);


        $valeur = $form->get('montant')->getData();

        $tarif = $this->getDoctrine()->getRepository(Tarifs::class)->findAll();

        foreach ($tarif as $values) {

            $values->getBorneinferieur();
            $values->getBornesuperieur();
            $values->getValeur();

            if ($valeur >= $values->getBorneInferieur() && $valeur <= $values->getBorneSuperieur()) {

                $com = $values->getValeur();
                $envoie->setFrais($com);

                $envoie->setCometat(($com * 30) / 100);
                $envoie->setComsystem(($com * 40) / 100);
                $envoie->setComenvoie(($com * 10) / 100);
                $envoie->setComretrait(($com * 20) / 100);
            }
        }

        if ($cpte->getSolde() > $envoie->getMontant()) {

            $montant = $cpte->getSolde() - $envoie->getMontant() + $envoie->getComenvoie();

            $cpte->setSolde($montant);

            $entityManager->persist($cpte);
            $entityManager->persist($envoie);
            $entityManager->flush();

            return new Response('Le transfert a été effectué avec succés. Voici le code : ' . $envoie->getCode());
        } else {

            return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');
        }
    }

    /**
     * @Route("/retrait", name="add_retrait" ,methods={"POST", "GET"})
     */

    public function retrait(Request $request, EntityManagerInterface $entityManager, TransactionRepository $transaction)
    {

        $trans = new Transaction();
        $form = $this->createForm(TransactionType::class, $trans);
        $user = $this->getUser();
        $data = $request->request->all();
        $form->submit($data);
        $code = $data['code'];

        $trouve = $transaction->findOneBy(['code' => $code]);

        if (!$trouve) {
            return new Response('Le code saisi est incorecte .Veuillez ressayer un autre  ');
        }

        $statut = $trouve->getType();

        if ($trouve->getCode() == $code && $statut == "retrait") {
            return new Response('Le code saisi est déjà retiré  ');
        }

        $trouve->setCniEx($data["cni"]);

        $trouve->setDateRetrait(new \DateTime());

        $trouve->setType("retrait");
        $trouve->setUser($user);

        $entityManager->flush();

        return new Response('Vous venez de retirer  ' . $trouve->getMontant());
    }

    /** 
     * @Route("/frais", name="frais", methods={"GET", "POST"})
     */

    public function frais(Request $request)
    {
        $frais = new Transaction();
        $form = $this->createForm(FraisType::class, $frais);
        $data = $request->request->all();
        $form->submit($data);
     $frais = $form->get('montant')->getData();
        $tarifs = $this->getDoctrine()->getRepository(Tarifs::class)->findAll();
        $commission = 0;
        foreach ($tarifs as $values) {

            $values->getBorneinferieur();
            $values->getBornesuperieur();
            $values->getValeur();

            if ($frais >= $values->getBorneInferieur() && $frais <= $values->getBorneSuperieur()) {

                $commission = $values->getValeur();
            }
        }
        return new JsonResponse($commission, 200);
    }

    public $dateFrom;
    private $dateTo;
    public function __construct()
    {
        $this->dateFrom = 'dateFrom';
        $this->dateTo = 'dateTo';
    }
    /** 
     * @Route("/transaction", name="list_transaction", methods={"GET", "POST"})
     */

    public function index(Request $request, TransactionRepository $trans, SerializerInterface $serializer)
    {

      
        $user = $this->getUser();
        $values = json_decode($request->getContent());

        if (!$values) {
            $values= $request ->request->all();
        }
        $debut = new \DateTime($values->dateFrom);
        $fin = new \DateTime($values->dateTo);

        try {
            $repo1 = $this->getDoctrine()->getRepository(Transaction::class);
            $detail = $repo1->getByDate($debut, $fin, $user);
            if ($detail == []) {
                return $this->json([
                    'message' => 'aucune transaction pour cette intervale! verifier la date'
                ]);
            }
        } catch (ParseException $exception) {
            $exception = [
                'status' => 500,
                'message' => 'Vous devez renseignes tous les champs'
            ];
            return new JsonResponse($exception, 500);
        }
        $data      = $serializer->serialize($detail, 'json', ['groups' => ['show']]);
    

        return new SymfonyResponse($data, 200,['Content-Type'=>'application/json']);
    }
}
