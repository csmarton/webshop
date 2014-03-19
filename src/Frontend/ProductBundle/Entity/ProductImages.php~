<?php

namespace Frontend\ProductBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductImages
 *
 * @ORM\Table(name="product_images")
 * @ORM\Entity
 */
class ProductImages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=false)
     */
    private $path;
    
    /**
     * @Assert\File(
      maxSize = "6000000",
      maxSizeMessage = "A kép mérete túl nagy!",
      mimeTypes = {"image/*"},
      mimeTypesMessage = "Kérlek képet tölts fel!"
      )
     */
    private $file;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Frontend\ProductBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Frontend\ProductBundle\Entity\Product", inversedBy="productImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * Set productId
     *
     * @param integer $productId
     * @return ProductImages
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return ProductImages
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ProductImages
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ProductImages
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param \Frontend\ProductBundle\Entity\Product $product
     * @return ProductImages
     */
    public function setProduct(\Frontend\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Frontend\ProductBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    
    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/'. $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }
    
    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/productImages/' . $this->getProduct()->getId();
    }
    
     /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function upload() {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return false;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to

        /* létezik-e a fájl már? 
         * közben megkapja a time()-t is
         * ha igen akkor a neve mellé egy indexet kell tenni 
         */


        $fileOriginal = $this->getFile()->getClientOriginalName();
        $extraIndex = 1;
        $this->path = $fileOriginal;
        $pathInf = pathinfo($this->getAbsolutePath());
        $fileOriginal = $pathInf['filename'] . "." . $pathInf['extension'];

        while (file_exists($this->getAbsolutePath())) {
            $fileOriginal = $pathInf['filename'] . "-$extraIndex" . "." . $pathInf['extension'];
            $extraIndex++;
            $this->path = $fileOriginal;
        }

        $this->getFile()->move(
                $this->getUploadRootDir(), $fileOriginal
        );

        // set the path property to the filename where you've saved the file
        //$this->path = $fileOriginal;
        // clean up the file property as you won't need it anymore
        $this->file = null;
        return true;
    }
}
