app/

├── core/
│   ├── App.php
│   ├── Router.php
│   ├── Request.php
│   ├── Response.php
│   ├── Database.php
│   ├── View.php
│   ├── Validator.php
│   ├── Session.php
│   ├── Auth.php
│   ├── Middleware.php
│   └── helpers.php
│
├── modules/

│   ├── shop/
│   │   ├── routes/
│   │   │   ├── web.php
│   │   │   └── api.php
│   │   │
│   │   ├── controllers/
│   │   │   ├── ProductController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── BrandController.php
│   │   │   ├── InventoryController.php
│   │   │   ├── SupplierController.php
│   │   │   ├── PurchaseController.php
│   │   │   └── OrderController.php
│   │   │
│   │   ├── endpoints/
│   │   │   ├── ProductEndpoint.php
│   │   │   ├── CategoryEndpoint.php
│   │   │   ├── BrandEndpoint.php
│   │   │   ├── InventoryEndpoint.php
│   │   │   ├── SupplierEndpoint.php
│   │   │   ├── PurchaseEndpoint.php
│   │   │   └── OrderEndpoint.php
│   │   │
│   │   ├── services/
│   │   ├── repositories/
│   │   ├── validators/
│   │   │
│   │   └── views/
│   │       ├── product/
│   │       ├── category/
│   │       ├── brand/
│   │       ├── inventory/
│   │       ├── supplier/
│   │       ├── purchase/
│   │       └── order/
│

│   ├── website/
│   │   ├── config/
│   │   │   └── module.php
│   │
│   │   ├── routes/
│   │   │   └── web.php
│   │   │
│   │   ├── controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── PageController.php
│   │   │   ├── ShopController.php
│   │   │   ├── CartController.php
│   │   │   └── AuthController.php
│   │   │
│   │   ├── services/
│   │   │   ├── HomeService.php
│   │   │   ├── PageService.php
│   │   │   ├── ShopService.php
│   │   │   ├── CartService.php
│   │   │   └── AuthService.php
│   │   │
│   │   ├── middlewares/
│   │   │   └── AuthMiddleware.php
│   │   │
│   │   └── views/
│   │       │
│   │       ├── layouts/
│   │       │   ├── master.php
│   │       │   ├── header.php
│   │       │   └── footer.php
│   │       │
│   │       ├── components/
│   │       │   ├── product-card.php
│   │       │   ├── cart-item.php
│   │       │   ├── pagination.php
│   │       │   ├── breadcrumbs.php
│   │       │   └── flash.php
│   │       │
│   │       ├── home/
│   │       │   └── index.php
│   │       │
│   │       ├── pages/
│   │       │   ├── career.php
│   │       │   ├── string.php
│   │       │   └── affiliate.php
│   │       │
│   │       ├── shop/
│   │       │   ├── index.php
│   │       │   ├── show.php
│   │       │   ├── category.php
│   │       │   └── search.php
│   │       │
│   │       ├── cart/
│   │       │   ├── index.php
│   │       │   └── checkout.php
│   │       │
│   │       ├── auth/
│   │       │   ├── login.php
│   │       │   ├── register.php
│   │       │   └── forgot-password.php
│   │       │
│   │       └── errors/
│   │           ├── 404.php
│   │           └── 500.php
│
│   ├── booking/
│   │
│   ├── academy/
│   │
│   ├── customer/
│   │
│   ├── staff/
│   │
│   ├── location/
│   │
│   ├── payment/
│   │
│   ├── report/
│   │
│   ├── notification/
│   │
│   ├── audit/
│   │
│   └── crawler/
│
├── common/
│   ├── tools/
│   ├── helpers/
│   ├── cache/
│   ├── mail/
│   └── partials/
│
└── public/
    ├── index.php
    └── assets/