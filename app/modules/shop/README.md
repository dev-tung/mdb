modules/
└── shop/
    ├── routes/
    │   ├── web.php
    │   └── api.php
    │
    ├── controllers/
    │   ├── BaseController.php
    │   ├── ProductController.php
    │   ├── CategoryController.php
    │   ├── BrandController.php
    │   ├── InventoryController.php
    │   ├── SupplierController.php
    │   ├── PurchaseController.php
    │   └── OrderController.php
    │
    ├── services/
    │   ├── ProductService.php
    │   ├── CategoryService.php
    │   ├── BrandService.php
    │   ├── InventoryService.php
    │   ├── SupplierService.php
    │   ├── PurchaseService.php
    │   └── OrderService.php
    │
    ├── repositories/
    │   ├── ProductRepository.php
    │   ├── CategoryRepository.php
    │   ├── BrandRepository.php
    │   ├── InventoryRepository.php
    │   ├── SupplierRepository.php
    │   ├── PurchaseRepository.php
    │   └── OrderRepository.php
    │
    ├── endpoints/
    │   ├── ProductEndpoint.php
    │   ├── CategoryEndpoint.php
    │   ├── BrandEndpoint.php
    │   ├── InventoryEndpoint.php
    │   ├── SupplierEndpoint.php
    │   ├── PurchaseEndpoint.php
    │   └── OrderEndpoint.php
    │
    ├── validators/
    │   ├── ProductValidator.php
    │   ├── PurchaseValidator.php
    │   └── OrderValidator.php
    │
    └── views/
        ├── product/
        │   ├── index.php
        │   ├── create.php
        │   ├── edit.php
        │   └── show.php
        │
        ├── category/
        │   ├── index.php
        │   └── form.php
        │
        ├── brand/
        │   ├── index.php
        │   └── form.php
        │
        ├── inventory/
        │   └── index.php
        │
        ├── supplier/
        │   └── index.php
        │
        ├── purchase/
        │   └── index.php
        │
        └── order/
            ├── index.php
            └── detail.php