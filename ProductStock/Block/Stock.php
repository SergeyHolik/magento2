<?php
declare(strict_types=1);

namespace Biotus\ProductStock\Block;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Store\Model\StoreManagerInterface;

class Stock extends Template
{
    /**
     * @var GetProductSalableQtyInterface
     */
    protected GetProductSalableQtyInterface $salebleqty;
    /**
     * @var StockResolverInterface
     */
    protected StockResolverInterface $stockresolver;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storemanager;
    /**
     * @var Http
     */
    protected Http $request;
    /**
     * @var ProductFactory
     */
    protected ProductFactory $product;

    /**
     * LeftQty constructor.
     * @param Context $context
     * @param Http $request
     * @param ProductFactory $product
     * @param StoreManagerInterface $storemanager
     * @param GetProductSalableQtyInterface $salebleqty
     * @param StockResolverInterface $stockresolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        Http $request,
        ProductFactory $product,
        StoreManagerInterface $storemanager,
        GetProductSalableQtyInterface $salebleqty,
        StockResolverInterface $stockresolver,
        array $data = [])
    {
        $this->request = $request;
        $this->product = $product;
        $this->storemanager = $storemanager;
        $this->salebleqty = $salebleqty;
        $this->stockresolver = $stockresolver;
        parent::__construct($context, $data);
    }
    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getStockQty()
    {
        $productId = $this->request->getParam('id');
        $websiteCode = $this->storemanager->getWebsite()->getCode();
        $stockDetails = $this->stockresolver->execute(SalesChannelInterface::TYPE_WEBSITE, $websiteCode);
        $stockId = $stockDetails->getStockId();
        $productDetails = $this->product->create()->load($productId);
        $sku = $productDetails->getSku();
        $proType = $productDetails->getTypeId();
        if ($proType != 'configurable' && $proType != 'bundle' && $proType != 'grouped') {
            $stockQty = $this->salebleqty->execute($sku, $stockId);
            return $stockQty;
        } else {
            return '';
        }
    }
}

