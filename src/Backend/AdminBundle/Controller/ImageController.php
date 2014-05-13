<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Backend\AdminBundle\Entity\Log;

class ImageController extends Controller
{
    /*
     * Kép feltöltése
     */
    public function uploadAction()
    {  
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        $productId = $request->query->get('productId');
        
        if ($request->getMethod() == 'POST') {
            $file = $request->files->get('uploadedImage');
            if($file instanceof UploadedFile){
                $product = $this->getDoctrine()->getRepository('FrontendProductBundle:Product')->findOneById($productId);
                $currentTime = new \DateTime("now");
                $productImages = new ProductImages();
                $productImages->setProduct($product);
                $productImages->setFile($file);
                $productImages->setCreatedAt($currentTime);
                $productImages->setUpdatedAt($currentTime);
                
                if($file->getSize() > 1048576 * 5){
                 return new JsonResponse(array('success' => false, 'error' => 'Túl nagy fájl')); 
                }elseif(!$productImages->upload()){
                     return new JsonResponse(array('success' => false, 'error' => 'Hiba a fájl feltöltésében')); 
                }else{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($productImages);
                        $em->flush();
                        
                        //LOGOLÁS
                        $user = $this->get('security.context')->getToken()->getUser();

                        $changedData = "<div class=\"label-text\">Új kép: </div><div class='content-box'>". $productImages->getId() ."</div>";
                        $now = new \DateTime('now');
                        $log = new Log();
                        $log->setAction(0);
                        $log->setObjectClass("Frontend\ProductBundle\Entity\ProductImages");
                        $log->setObjectId($productImages->getId());
                        $log->setTime($now);
                        $log->setUser($user);
                        $log->setData($changedData);
                        $em = $this->getDoctrine()->getEntityManager(); 
                        $em->persist($log);
                        $em->flush();

                        
                }
            }                   
        }
        return $this->redirect($this->generateUrl('backend_admin_product_new',array('productId' => $productId)) . "#productImages");
    }
    
    /*
     * Kép törlése
     */
    public function removeAction(){
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) { //Csak admin férhet hozzá a tartalmakhoz
            return $this->redirect($this->generateUrl('backend_admin'));
        }
        $request = $this->get('request');
        
        if ($request->getMethod() == 'POST') {
            $productImageId = $request->request->get('productImageId');
            
            $productImage = $this->getDoctrine()->getRepository('FrontendProductBundle:ProductImages')->findOneById($productImageId);
            
            $product = $productImage->getProduct();
            $user = $this->get('security.context')->getToken()->getUser();

            $changedData = "<div class=\"label-text\">Kép törlése: </div><div class='content-box'>". $productImage->getId() ."</div>";
            $now = new \DateTime('now');
            $log = new Log();
            $log->setAction(2);
            $log->setObjectClass("Frontend\ProductBundle\Entity\ProductImages");
            $log->setObjectId($productImage->getId());
            $log->setTime($now);
            $log->setUser($user);
            $log->setData($changedData);
            $em = $this->getDoctrine()->getEntityManager(); 
            $em->persist($log);
            $em->flush();
                        
            unlink($productImage->getAbsolutePath());
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($productImage);
            $em->flush();
            
            
            $html = $this->renderView('BackendAdminBundle:Product:productImageList.html.twig', array(
                'product' => $product
                    ));
            return new JsonResponse(array('success' => true, 'html' => $html));               
        }
        return new JsonResponse(array('success' => false));
    }
    
}
