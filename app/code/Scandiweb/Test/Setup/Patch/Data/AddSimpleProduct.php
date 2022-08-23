<?php
/*
 * Scandiweb_Test
 *
 * @category Scandiweb
 * @package Test
 * @author Illia Shunder <illia.shunder@scandiweb.com>
 * @copyright Copyright (c) 2022. Scandiweb, Inc (http://scandiweb.com)
 * @license http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Scandiweb\Test\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\State;
use Magento\Catalog\Api\CategoryLinkManagementInterface;

class AddSimpleProduct implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var ProductInterfaceFactory
     */
    protected ProductInterfaceFactory $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var CategoryFactory
     */
    protected CategoryFactory $categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
    * @var State
    */
    protected State $state;

    /**
    * @var CategoryLinkManagementInterface
    */
    protected CategoryLinkManagementInterface $categoryLink;

    /**
     * CreateSimpleProduct constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductInterfaceFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param State $state
     * @param CategoryLinkManagementInterface $categoryLink
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        State $state,
        CategoryLinkManagementInterface $categoryLink
    ) {
        $this->moduleDataSetup    = $moduleDataSetup;
        $this->productFactory     = $productFactory;
        $this->productRepository  = $productRepository;
        $this->categoryFactory    = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager       = $storeManager;
        $this->state              = $state;
        $this->categoryLink       = $categoryLink;
    }
       
    /**
     * apply
     *
     * @return void
     */
    public function apply()
    {
        $this->state->emulateAreaCode('adminhtml', [$this, 'execute']);
    }

    /**
     * execute
     * 
     * @return void
     */
    public function execute(): void
    {
        $simpleProductArray = [
            [
                'sku'               => 'SIMPLE-PRODUCT',
                'name'              => 'Some simple product',
                'attribute_id'      => '4',
                'status'            => 1,
                'weight'            => 2,
                'price'             => 100,
                'visibility'        => 1,
                'type_id'           => 'simple',
            ]
        ];

        foreach ($simpleProductArray as $data) {
            // Create Product
            $product = $this->productFactory->create();
            $product->setSku($data['sku'])
                ->setName($data['name'])
                ->setAttributeSetId($data['attribute_id'])
                ->setStatus($data['status'])
                ->setWeight($data['weight'])
                ->setPrice($data['price'])
                ->setVisibility($data['visibility'])
                ->setTypeId($data['type_id'])
                ->setCategoryIds(12)
                ->setStockData(
                    [
                        'use_config_manage_stock' => 1,
                        'manage_stock' => 1,
                        'is_in_stock' => 1,
                        'qty' => 199
                    ]
                );

            $this->productRepository->save($product);
            $this->categoryLink->assignProductToCategories($product->getSku(), [12]);
        }
    }
    
    /**
     * getDependencies
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }
    
    /**
     * getAliases
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}