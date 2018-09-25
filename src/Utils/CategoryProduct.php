<?php

namespace App\Utils;

use App\Entity\Category;
use App\Entity\Product;
use App\Factory\ProductModelFactory;
use App\Model\Product\Result;
use Doctrine\ORM\EntityManagerInterface;

class CategoryProduct
{
    /** @var EntityManager $em * */
    private $em;

    /** @var ProductModelFactoryInterface $productModelFactory * */
    private $productModelFactory;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,ProductModelFactory $productModelFactory)
    {
        $this->em = $em;
        $this->productModelFactory = $productModelFactory;
    }

    /**
     * Gets the all categories.
     *
     * @return array
     */
    public function getAllCategory()
    {
        return $this->getCategoryRepository()->findAll();
    }

    /**
     * Gets the all products.
     *
     * @return array
     */
    public function getAllProduct()
    {
        return $this->getProductRepository()->findAll();
    }

    /**
     * Create a category.
     *
     * @return array
     */
    public function postCategory($category)
    {
        $this->persistAndFlush($category);

        return $category;
    }

    /**
     * Create a product.
     *
     * @return array
     */
    public function postProduct($product)
    {
        foreach ($product->getCategories() as $category) {
            $objCategory = $this->getCategoryRepository()->findOneBy(['name' => $category->getName()]);
            if ($objCategory) {
                $product->addCategory($objCategory);
            }
        }
        $this->persistAndFlush($product);

        return $product;
    }


    /**
     * Get a category by Id.
     *
     * @param int $id
     *
     * @return Category
     */
    public function getCategorybyId($idCategory)
    {
        return $this->getCategoryRepository()->findOneBy(['id' => $idCategory]);
    }


    /**
     * @param $entity
     */
    private function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

}
