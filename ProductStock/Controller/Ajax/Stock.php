<?php
declare(strict_types=1);

namespace Biotus\ProductStock\Controller\Ajax;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Stock extends Action
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;
    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * @param Context $context
     * @param ProductRepository $productRepository
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ProductRepository $productRepository,
        JsonFactory $resultJsonFactory
    ) {
        $this->productRepository = $productRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute(): Json|ResultInterface|ResponseInterface
    {
        $result = $this->resultJsonFactory->create();
        $productId = $this->getRequest()->getParam('product_id');
        $product = $this->productRepository->getById($productId);
        $qty = $product->getQty();
        return $result->setData(['qty' => $qty]);
    }
}

