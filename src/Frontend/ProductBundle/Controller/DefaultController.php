<?php

namespace Frontend\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Gregwar\Image\Image;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {        
         //$em = $this->getDoctrine()->getEntityManager();
        // $user = $this->get('security.context')->getToken()->getUser();
         //var_dump($user);die;

        $request = $this->get('request');        
        $session = $request->getSession();
        $session->clear();
        return $this->render('FrontendProductBundle:Default:index.html.twig');
    }
    
	public function registrationAction(Request $request){
            
		  /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
         $user = $userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            
            return $event->getResponse();
        }
         $form = $formFactory->createForm();
        $form->setData($user);
        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('frontend_product_homepage');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }
		//return $this->render('FrontendProductBundle:Default:registration.html.twig');
                return $this->container->get('templating')->renderResponse('FrontendProductBundle:Default:registration.html.twig', array(
                    'form' => $form->createView(),
                ));
	}
        
        public function loginAction(){
            return $this->render('FrontendProductBundle:Default:login.html.twig');
        }
    public function sidebarAction(){
        $repo =  $this->getDoctrine()->getRepository('FrontendProductBundle:Taxon');
        
        $taxonWithTaxonomy = $repo
                    ->createQueryBuilder('t')
                    ->select('t, tt')                          
                    ->leftJoin('t.taxonomy', 'tt')                    
                    ->orderBy('tt.name,t.name')
                    ->getQuery()->getResult();
        
        $leftMenu = array();
        foreach($taxonWithTaxonomy as $t){
            $leftMenu[$t->getTaxonomy()->getName()][] = $t;
        }
        return $this->render('FrontendProductBundle:Default:sidebar.html.twig', array('leftMenu'=>$leftMenu));
    }
    public function mainProductAction(){
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findAll();
        $productsPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp, p')                          
                    ->leftJoin('pp.property', 'p') 
                    ->getQuery()->getResult();
        $productsImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findAll();
        
        
        $propertys = array();
        $images = array();
        foreach($products as $i => $product){  
            $propertys[$i] = array();
            $images[$i] = array();
            foreach($productsPropertys as $pp){
                if($product->getId() == $pp->getProductId()){
                    $propertys[$i][] = $pp;
                }
            }
            foreach($productsImages as $img){
                if($product->getId() == $img->getProductId()){
                    $images[$i][] = $img;
                }
            }           
        }
        return $this->render('FrontendProductBundle:Default:products.html.twig',array('products'=>$products,'propertys'=>$propertys,'images'=>$images));
    }
    
    public function productByTaxonomyAction($taxon, $taxonomy=null){
        $permalinks = $taxon . "/". $taxonomy;
        $productsPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductTaxon')->createQueryBuilder('pt')
                    ->select('pt.productId')  
                    ->leftJoin('pt.taxon', 't')
                    ->where('t.permalinks LIKE :permalinks')     
                    ->setParameter('permalinks',$permalinks."%")
                    ->getQuery()->getResult();
        $products = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->createQueryBuilder('p')
                    ->select('p')  
                    ->where('p.id IN (:productsPropertys)')     
                    ->setParameter('productsPropertys',$productsPropertys)
                    ->getQuery()->getResult();
        
        $productsPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp, p')                          
                    ->leftJoin('pp.property', 'p') 
                    ->getQuery()->getResult();
        $productsImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findAll();
        
        
        $propertys = array();
        $images = array();
        foreach($products as $i => $product){  
            $propertys[$i] = array();
            $images[$i] = array();
            foreach($productsPropertys as $pp){
                if($product->getId() == $pp->getProductId()){
                    $propertys[$i][] = $pp;
                }
            }
            foreach($productsImages as $img){
                if($product->getId() == $img->getProductId()){
                    $images[$i][] = $img;
                }
            }           
        }
        return $this->render('FrontendProductBundle:Default:products_by_taxon.html.twig',array('products'=>$products,'propertys'=>$propertys,'images'=>$images));
    }
    
    public function productAction($slug){
        if(!is_numeric($slug)){//slug alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneBySlug($slug);
        }else{//id alapján
            $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($slug);
        }
        
        $productPropertys = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductProperty')->createQueryBuilder('pp')
                    ->select('pp')
                    ->where('pp.productId = :productId')
                    ->setParameter('productId',$product->getId())
                    ->getQuery()->getResult();
        $productImages = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findByProductId($product->getId());
        
        
        $propertys = array();
        $images = array();
        foreach($productPropertys as $pp){
            if($product->getId() == $pp->getProductId()){
                $propertys[] = $pp;
            }
        }
        foreach($productImages as $img){
            if($product->getId() == $img->getProductId()){
                $images[] = $img;
            }
        } 
        
        return $this->render('FrontendProductBundle:Default:product.html.twig',array('product'=>$product,'propertys'=>$propertys,'images'=>$images));
    }
}
