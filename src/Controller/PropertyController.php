<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use Doctrine\ORM\EntityManager;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{

    //POUR AVOIR ACCES AU REPOSITORY

    /**
     * 
     * @var PropertyRepository
     */
    private $repository;


    public function __construct(PropertyRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    // ou par injection
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        //Creer une entité qui va representer notre recherche
        //Creer un formulaire 
        //Gerer le traitement dans le controller

        $search = new PropertySearch();
        //on creer le formulaire
        $form = $this->createform(PropertySearchType::class, $search );
        //il doit gerer la requete
        $form->handleRequest($request);


        //afficher les 30 proêrties dans l'onglets tous les biens
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            //envoi du formualire a la vue
            'form'=> $form->createView()
        ]);
    }
    //$property[0]->setSold(true); //il saura que la valeur de sold a ete modifié et fera le necessaire pour afficher ce qu'on veut
    //$this->em->flush();

    //find() qui retrouve en fonction de l'id
    //findAll() qui retourne un tableau d'element
    //findOneBy(['floor' => 4])qui retourne un element en fonction d'un critere on passe un tableau
    //Autre methode
    //$repo = $this->getDoctrine()->getRepository( Property::class);


    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(string $slug, Property $property, $id): Response
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' =>$property->getId(),
                'slug' => $property->getSlug()
            ], 301); //statut redirection permanente
        }
        //recupere la proprieté
        $property= $this->repository->find($id);

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
}
