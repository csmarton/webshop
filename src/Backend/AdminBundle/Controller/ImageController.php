<?php

namespace Backend\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Frontend\ProductBundle\Entity\ProductImages;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageController extends Controller
{
    public function uploadAction()
    {  
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
                       // var_dump($productImages->getWebPath());die;

                        return $this->redirect($this->generateUrl('backend_admin_product_new',array('productId' => $productId)));
                }
                return new JsonResponse(array('success' => false));
            }       
            
        }
        return new JsonResponse(array('success' => false));
    }  
    
}
