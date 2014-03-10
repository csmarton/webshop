<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Form\TaxonType;
use Frontend\ProductBundle\Entity\Taxon;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function indexAction()
    {   
        $taxons = $this->getDoctrine()->getRepository('FrontendProductBundle:Taxon')->findAll();
        //$form = $this->createForm(new ProductType());
        return $this->render('BackendAdminBundle:Category:list.html.twig', array(
            //'form' => $form->createView(),
            'taxons' => $taxons
        ));
    }
    
    public function newAction()
    {   
        $request = $this->get('request');
        $taxonId = $request->query->get('taxonId');

        if($taxonId == null){
            $taxon = new Taxon();        
        }else{
            $taxon = $this->getDoctrine()->getRepository('FrontendProductBundle:Taxon')->findOneById($taxonId);
        }
       
        $form = $this->createForm(new TaxonType(),$taxon);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {     
               if (isset($form['name'])) {
                        $productName = $form['name']->getData();
               }

               
               $taxon->setName($productName);
               $em = $this->getDoctrine()->getManager();
               $em->persist($taxon);
               $em->flush();
               
               return $this->redirect($this->generateUrl('backend_admin_category'));
            }
        }    
        
        return $this->render('BackendAdminBundle:Category:new.html.twig', array(
            'form' => $form->createView(),
            'taxonId'=>$taxonId,
            'taxon' => $taxon,
        ));
    }
    
     public function removeAction(){
         if ($request->getMethod() == 'POST') {
            $request = $this->get('request');
            $productId = $request->query->get('productId');
            if($productId == null){
                $product = new Product();        
            }else{
                $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
            }
            return new JsonResponse(array('success' => true));
         }else{
             return new JsonResponse(array('success' => false));
         }   
    }
    
}
