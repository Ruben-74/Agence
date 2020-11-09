<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManager;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    public function __construct(PropertyRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em =$em;
    }

    /**
     * afficher lire tous les biens 
     * @Route("/admin", name="admin.property.index")
     * @return Response 
     * 
     */
    public function index(): Response
    {
        $properties = $this->repository->findAll();

        return $this->render('admin/property/index.html.twig',compact('properties'));
        //compact est une fonction qui envoi un tableau properties
    }

    
    /**
     * Ajouter un Bien
     * @Route("/admin/property/create", name="admin.property.new")
     * @param Request $request
     */
    public function new(Request $request){
        
        //on creer une nouvelle propriété donc on recupêre pas les données de la classe Property
        $property = new Property();

        //on crée le formulaire
        $form = $this->createForm( PropertyType::class, $property);

        //on soumet la requet et on la traite
        //il va voir le type de requete et l'ensemble des champs
        $form->handleRequest($request); 

        //valide si les info ont été soumis et s'ils sont valident 

        if ($form->isSubmitted() && $form->isValid()) {
            //il faut persister cette entité car elle n'est pas geré par l'ObjectManager
            $this->em->persist($property);

            $this->em->flush();

            //Message de succes
            $this->addFlash('success', 'Bien ajouté avec succès');

            //redigirer l'utilisateur
            return $this->redirectToRoute('admin.property.index');
        }

         //controle du visuel du formulaire qui sera transmit a la vue
         return $this->render('admin/property/new.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
        

    }    


    /**
     * Editer un Bien
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request  $request
     */
    public function edit(Property $property, Request $request){ //on introduit 

        // //Ajouter une option
        // $option = new Option();
        // $property->addOption($option);


        //on crée le formulaire
        $form = $this->createForm( PropertyType::class,  $property);

        //on soumet la requet et on la traite
        //il va voir le type de requete et l'ensemble des champs
        $form->handleRequest($request); 

        //valide si les info ont été soumis et s'ils sont valident 

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            //Message de succes
            $this->addFlash('success', 'Bien modifié avec succès');

            //redigirer l'utilisateur
            return $this->redirectToRoute('admin.property.index');
        }

        //controle du visuel du formulaire qui sera transmit a la vue
        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprimer un Bien
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request  $request
     */
    public function delete(Property $property, Request $request){ //on introduit 

        //Le token on peut le recuperer grace a la requete
        //Verifier que le token est valide 

        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            
            $this->em->remove($property);

            $this->em->flush();

            //Message de succes
            $this->addFlash('success', 'Bien supprimé avec succès');

        }

        return $this->redirectToRoute('admin.property.index');
    
    }
}
