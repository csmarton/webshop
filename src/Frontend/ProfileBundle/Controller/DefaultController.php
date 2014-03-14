<?php

namespace Frontend\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProfileBundle\Entity\Profile;
use Frontend\ProfileBundle\Form\ProfileType;

class DefaultController extends Controller
{
     public function editAction(){
             $request = $this->get('request');
             $user = $this->get('security.context')->getToken()->getUser();
             if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') )
                 return $this->redirect($this->generateUrl('frontend_product_homepage'));
                 
             $profile = $user->getProfile();
             
             $form = $this->createForm(new ProfileType(),$profile);
        
             if ($request->getMethod() == 'POST') {
                $form->bind($request);
                if ($form->isValid()) { 
                    /*if (isset($form['name'])) {
                        $profile->setName($form['name']->getData());
                    }*/
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($profile);
                    $em->flush();
                    $html = "Sikeresen megváltoztattad az adataidat!";
                        return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
                            'form' => $form->createView(),
                            'succesChanges' => $html
                            ));
                    }                
             }    
        
            return $this->render('FrontendProfileBundle:Default:edit.html.twig', array(
                'form' => $form->createView())
            );
        }
}
