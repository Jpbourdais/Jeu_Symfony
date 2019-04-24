<?php

namespace App\Controller;

use App\Entity\Partie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PartieController extends AbstractController
{
    /**
     * @Route("/partie", name="partie")
     */
    public function index()
    {
        return $this->render('partie/index.html.twig', [
            'controller_name' => 'PartieController',
        ]);
    }

    /**
     * @Route("/all-parties", name="all-parties")
     */
    public function allPartie(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Partie::class);
        $allparties = $repo->findAll();

        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom de la nouvelle partie',
                    'name' => 'nom',
                    'id' => 'nom'
                ]
            ])
            ->add('envoyer', SubmitType::class)
            ->getForm();
        return $this->render('partie/allparties.html.twig', [
            'formpartie' => $form->createView(),
            'parties' => $allparties
        ]);
    }

    /**
     * @Route("/add-partie", name="add-partie")
     */
    public function addPartie(Request $request){
        $form = $request->request->get('form');
        $nom = $form['nom'];
            //1 FEU <= 2 VENT
            //2 VENT <= 3 TERRE
            //3 TERRE <= 1 FEU
        if($nom != ''){
            $des = [1 => 4, 2 => 4, 3 => 4, 4 => 0];
            $maitre = [['ID' => 1, 'ELEMENT' => 4, 'FORCE' => 4]];
            $cartesj1 = [
                ['ID' => 2, 'ELEMENT' => 1, 'FORCE' => 1],
                ['ID' => 3, 'ELEMENT' => 1, 'FORCE' => 2],
                ['ID' => 4, 'ELEMENT' => 1, 'FORCE' => 3],
                ['ID' => 5, 'ELEMENT' => 2, 'FORCE' => 1],
                ['ID' => 6, 'ELEMENT' => 2, 'FORCE' => 2],
                ['ID' => 7, 'ELEMENT' => 2, 'FORCE' => 3],
                ['ID' => 8, 'ELEMENT' => 3, 'FORCE' => 1],
                ['ID' => 9, 'ELEMENT' => 3, 'FORCE' => 2],
                ['ID' => 10, 'ELEMENT' => 3, 'FORCE' => 3]
            ];
            $cartesj2 = [
                ['ID' => 2, 'ELEMENT' => 1, 'FORCE' => 1],
                ['ID' => 3, 'ELEMENT' => 1, 'FORCE' => 2],
                ['ID' => 4, 'ELEMENT' => 1, 'FORCE' => 3],
                ['ID' => 5, 'ELEMENT' => 2, 'FORCE' => 1],
                ['ID' => 6, 'ELEMENT' => 2, 'FORCE' => 2],
                ['ID' => 7, 'ELEMENT' => 2, 'FORCE' => 3],
                ['ID' => 8, 'ELEMENT' => 3, 'FORCE' => 1],
                ['ID' => 9, 'ELEMENT' => 3, 'FORCE' => 2],
                ['ID' => 10, 'ELEMENT' => 3, 'FORCE' => 3]
            ];

            shuffle($cartesj1);
            shuffle($cartesj2);

            $terrainj1 = [
                1 => [$maitre[0], $cartesj1[0], $cartesj1[1], $cartesj1[2]],
                2 => [$cartesj1[3], $cartesj1[4], $cartesj1[5]],
                3 => [$cartesj1[6], $cartesj1[7]],
                4 => [$cartesj1[8]],
                5 => [],
                6 => [],
                7 => [],
                8 => [],
                9 => [],
                10 => [],
                11 => []
            ];

            $terrainj2 = [
                1 => [$maitre[0], $cartesj2[0], $cartesj2[1], $cartesj2[2]],
                2 => [$cartesj2[3], $cartesj2[4], $cartesj2[5]],
                3 => [$cartesj2[6], $cartesj2[7]],
                4 => [$cartesj2[8]],
                5 => [],
                6 => [],
                7 => [],
                8 => [],
                9 => [],
                10 => [],
                11 => []
            ];

            $partieManager = $this->getDoctrine()->getManager();
            $partie = new Partie();
            $partie->setPartieTerrain1($terrainj1);
            $partie->setPartieTerrain2($terrainj2);
            $partie->setPartieStatus(1);
            $tour = rand(1, 2);
            $partie->setPartieTour($tour);
            $partie->setPartieNom($nom);
            $partie->setPartieDes($des);

            $partieManager->persist($partie);

            $partieManager->flush();
        }
        return $this->redirectToRoute('all-parties');
    }

    /**
     * @Route("/show-partie/{partieid}/{joueur}/", name="show-partie")
     */
    public function afficherPartie($partieid, $joueur){
            $repo = $this->getDoctrine()->getRepository(Partie::class);
            if($partie = $repo->find($partieid)){
            if($joueur == 1){
                $terraindessous = $partie->getPartieTerrain1();
                $terraindessus = $partie->getPartieTerrain2();
            }

            else if($joueur == 2){
                $terraindessous = $partie->getPartieTerrain2();
                $terraindessus = $partie->getPartieTerrain1();
            }
            else{
                return $this->redirectToRoute('all-parties');
            }

            $des = $partie->getPartieDes();
            if($des[1] == 4 && $des[2] == 4 && $des[3] == 4){
                $actionsdes = 1;
            }
            else{
                $actionsdes = 0;
            }

            $actions = $partie->getPartieTour();
            if($joueur == $actions){
                $actions = 1;
            }
            else{
                $actions = 0;
            }

            return $this->render('partie/afficher.html.twig', [
                'terrainadv' => $terraindessus,
                'terrainjou' => $terraindessous,
                'joueur' => $joueur,
                'id' => $partieid,
                'des' => $des,
                'actions' => $actions,
                'actionsdes' => $actionsdes
            ]);
        }

        return $this->redirectToRoute('all-parties');
    }

    /**
     * @Route("/roll-des/{partieid}/{joueur}", name="roll-des")
     */
    public function jeterDes($partieid, $joueur){
        $repo = $this->getDoctrine()->getRepository(Partie::class);
        if($partie = $repo->find($partieid)){
            if($partie->getPartieTour() == $joueur) {
                $des = $partie->getPartieDes();

                if($des[1] == 4 && $des[2] == 4 && $des[3] == 4 ){
                $des[1] = rand(1, 3);
                $des[2] = rand(1, 3);
                $des[3] = rand(1, 3);

                $partie->setPartieDes($des);
                $partieManager = $this->getDoctrine()->getManager();
                $partieManager->persist($partie);
                $partieManager->flush();
                }
            }
            return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
        }
        else {
            return $this->redirectToRoute('all-parties');
        }
    }

    /**
     * @Route("/end-tour/{partieid}/{joueur}", name="end-tour")
     */
    public function finTour($partieid, $joueur){
        $repo = $this->getDoctrine()->getRepository(Partie::class);
        if($partie = $repo->find($partieid)) {
            if ($partie->getPartieTour() == $joueur) {
                $des = $partie->getPartieDes();
                if ($des[1] == 5 || $des[2] == 5 || $des[3] == 5){
                    $des[1] = 4;
                    $des[2] = 4;
                    $des[3] = 4;
                    $des[4] = 1;
                    $partie->setPartieDes($des);

                    if($joueur == 1){
                        $partie->setPartieTour(2);
                    }
                    else if($joueur == 2){
                        $partie->setPartieTour(1);
                    }
                    $partieManager = $this->getDoctrine()->getManager();
                    $partieManager->persist($partie);
                    $partieManager->flush();
                }
            }
            return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
        }
        else{
            return $this->redirectToRoute('all-parties');
        }
    }

    /**
     * @Route("/move-carte/{partieid}/{joueur}/{element}/{force}/{pile}/{position}/{maitre}", name="move-carte")
     */
    public function deplacerCarte($partieid, $joueur, $element, $force, $pile, $position, $maitre = "0"){
        $repo = $this->getDoctrine()->getRepository(Partie::class);
        if($partie = $repo->find($partieid)) {
            if($partie->getPartieTour() == $joueur) {
                $des = $partie->getPartieDes();
                if($joueur == 1){
                    $terrain = $partie->getPartieTerrain1();
                }
                elseif ($joueur == 2){
                    $terrain = $partie->getPartieTerrain2();
                }

                foreach ($des as $key => $value){
                    if( $key == $element){
                        $avancement = $value;
                    }
                }

                if($avancement < 4){
                    if ($maitre == 1 && $des[4] == 1){
                            $des[4] = 0;
                    }
                    elseif ($force == 4 && $des[4] == 0){
                        return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
                    }
                    $position = $position-1;
                    $positionsup = $position;
                    $pilearrive = $pile+$avancement;
                    if($pilearrive > 11 && $pile != 11){
                        $pilearrive = 11;
                    }
                    elseif($pile == 11){
                        return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
                    }
                    $pileterraincarte = array_slice($terrain[$pile], $position);

                    if(count($pileterraincarte) <= 3){
                        $terrain[$pilearrive] = array_merge($terrain[$pilearrive], $pileterraincarte);
                        for($i = 0; $i < 3; $i++){
                            unset($terrain[$pile][$positionsup]);
                            $positionsup++;
                        }

                        $des[$element] = 5;
                        $partie->setPartieDes($des);
                    }
                }
                else{
                    return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
                }

                if($joueur == 1){
                    $partie->setPartieTerrain1($terrain);
                }
                elseif ($joueur == 2){
                    $partie->setPartieTerrain2($terrain);
                }

                return $this->combat($partie, $pilearrive, $partieid, $joueur); //POUR COMBATS
                $partieManager = $this->getDoctrine()->getManager();
                $partieManager->persist($partie);
                $partieManager->flush();
                return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
            }
            return $this->redirectToRoute('all-parties');
        }
        else{
            return $this->redirectToRoute('all-parties');
        }
    }

    public function combat(Partie $partie, $pilearrive, $partieid, $joueur){
        $terrain1 = $partie->getPartieTerrain1();
        $terrain2 = $partie->getPartieTerrain2();
        switch ($pilearrive){
            case 1;
                $pilearriveu = 11;
                break;
            case 2;
                $pilearriveu = 10;
                break;
            case 3;
                $pilearriveu = 9;
                break;
            case 4;
                $pilearriveu = 8;
                break;
            case 5;
                $pilearriveu = 7;
                break;
            case 6;
                $pilearriveu = 6;
                break;
            case 7;
                $pilearriveu = 5;
                break;
            case 8;
                $pilearriveu = 4;
                break;
            case 9;
                $pilearriveu = 3;
                break;
            case 10;
                $pilearriveu = 2;
                break;
            case 11;
                $pilearriveu = 1;
                break;
        }
        if($joueur == 1){
            $nbpile1 = count($terrain1[$pilearrive]);
            $nbpile2 = count($terrain2[$pilearriveu]);
        }
        elseif($joueur == 2){
            $nbpile1 = count($terrain1[$pilearriveu]);
            $nbpile2 = count($terrain2[$pilearrive]);
        }


        if($nbpile1 != 0 && $nbpile2 != 0){
            if($nbpile1 < $nbpile2){
                $iset = $nbpile1 - 1;
                $terraini = $terrain1;
                $uset = $nbpile2 - 1;
                $terrainu = $terrain2;
            }
            else{
                $iset = $nbpile2 - 1;
                $terraini = $terrain2;
                $uset = $nbpile1 - 1;
                $terrainu = $terrain1;
                if($joueur == 1){
                switch ($pilearrive){
                    case 1;
                        $pilearriveu = 1;
                        $pilearrive = 11;
                        break;
                    case 2;
                        $pilearriveu = 2;
                        $pilearrive = 10;
                        break;
                    case 3;
                        $pilearriveu = 3;
                        $pilearrive = 9;
                        break;
                    case 4;
                        $pilearriveu = 4;
                        $pilearrive = 8;
                        break;
                    case 5;
                        $pilearriveu = 5;
                        $pilearrive = 7;
                        break;
                    case 6;
                        $pilearriveu = 6;
                        $pilearrive = 6;
                        break;
                    case 7;
                        $pilearriveu = 7;
                        $pilearrive = 5;
                        break;
                    case 8;
                        $pilearriveu = 8;
                        $pilearrive = 4;
                        break;
                    case 9;
                        $pilearriveu = 9;
                        $pilearrive = 3;
                        break;
                    case 10;
                        $pilearriveu = 10;
                        $pilearrive = 2;
                        break;
                    case 11;
                        $pilearriveu = 11;
                        $pilearrive = 1;
                        break;
                }
                }
            }
            for($i = $iset; $i >= 0; $i--){
                for($u = $uset; $u >= 0; $u--){
                    $i_element = $terraini[$pilearrive][$i]['ELEMENT'];
                    $i_force = $terraini[$pilearrive][$i]['FORCE'];
                    $u_element = $terrainu[$pilearriveu][$u]['ELEMENT'];
                    $u_force = $terrainu[$pilearriveu][$u]['FORCE'];
                    //1 FEU <= 2 VENT
                    //2 VENT <= 3 TERRE
                    //3 TERRE <= 1 FEU
                    switch ($i_element){
                        case 1;
                            switch($u_element){
                                case 1;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                                case 2;
                                    unset($terraini[$pilearrive][$i]);
                                    $i--;
                                    break;
                                case 3;
                                    unset($terrainu[$pilearriveu][$u]);
                                    break;
                                case 4;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                        $terrainu[$pilearriveu][$u]['FORCE'] = $terrainu[$pilearriveu][$u]['FORCE'] - $i_force;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                        $terraini[$pilearrive][$i]['FORCE'] = $terraini[$pilearrive][$i]['FORCE'] - $u_force;
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                            }
                            break;
                        case 2;
                            switch($u_element){
                                case 1;
                                    unset($terrainu[$pilearriveu][$u]);
                                    break;
                                case 2;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                                case 3;
                                    unset($terraini[$pilearrive][$i]);
                                    $i--;
                                    break;
                                case 4;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                        $terrainu[$pilearriveu][$u]['FORCE'] = $terrainu[$pilearriveu][$u]['FORCE'] - $i_force;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                        $terraini[$pilearrive][$i]['FORCE'] = $terraini[$pilearrive][$i]['FORCE'] - $u_force;
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                            }
                            break;
                        case 3;
                            switch($u_element){
                                case 1;
                                    unset($terraini[$pilearrive][$i]);
                                    $i--;
                                    break;
                                case 2;
                                    unset($terrainu[$pilearriveu][$u]);
                                    break;
                                case 3;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                                case 4;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                        $terrainu[$pilearriveu][$u]['FORCE'] = $terrainu[$pilearriveu][$u]['FORCE'] - $i_force;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                        $terraini[$pilearrive][$i]['FORCE'] = $terraini[$pilearrive][$i]['FORCE'] - $u_force;
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                            }
                            break;
                        case 4;
                            switch($u_element){
                                case 1;
                                case 2;
                                case 3;
                                    unset($terraini[$pilearrive][$i]);
                                    $i--;
                                    break;
                                case 4;
                                    if($i_force < $u_force){
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                        $terrainu[$pilearriveu][$u]['FORCE'] = $terrainu[$pilearriveu][$u]['FORCE'] - $i_force;
                                    }
                                    elseif($i_force > $u_force){
                                        unset($terrainu[$pilearriveu][$u]);
                                        $terraini[$pilearrive][$i]['FORCE'] = $terraini[$pilearrive][$i]['FORCE'] - $u_force;
                                    }
                                    elseif($i_force == $u_force){ //PAS ENCORE FAIT
                                        unset($terrainu[$pilearriveu][$u]);
                                        unset($terraini[$pilearrive][$i]);
                                        $i--;
                                    }
                                    break;
                            }
                        break;
                    }
                }
            }
            if($nbpile1 < $nbpile2){
                $partie->setPartieTerrain1($terraini);
                $partie->setPartieTerrain2($terrainu);
            }
            else{
                $partie->setPartieTerrain1($terrainu);
                $partie->setPartieTerrain2($terraini);
            }
        }
        $partieManager = $this->getDoctrine()->getManager();
        $partieManager->persist($partie);
        $partieManager->flush();
        return $this->redirectToRoute('show-partie', ['partieid' => $partieid, 'joueur' => $joueur]);
    }


}
