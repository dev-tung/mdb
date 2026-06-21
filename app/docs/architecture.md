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
│
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
│   │   ├── models/
│   │   │   ├── ProductModel.php
│   │   │   ├── CategoryModel.php
│   │   │   ├── BrandModel.php
│   │   │   ├── InventoryModel.php
│   │   │   ├── SupplierModel.php
│   │   │   ├── PurchaseModel.php
│   │   │   └── OrderModel.php
│   │   │
│   │   ├── validators/
│   │   │   ├── ProductValidator.php
│   │   │   ├── CategoryValidator.php
│   │   │   ├── BrandValidator.php
│   │   │   ├── InventoryValidator.php
│   │   │   ├── SupplierValidator.php
│   │   │   ├── PurchaseValidator.php
│   │   │   └── OrderValidator.php
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
│   ├── crm/
│   │   ├── routes/
│   │   │   ├── web.php
│   │   │   └── api.php
│   │   │
│   │   ├── controllers/
│   │   │   ├── CustomerController.php
│   │   │   ├── GroupController.php
│   │   │   ├── ContactController.php
│   │   │   ├── InteractionController.php
│   │   │   ├── NoteController.php
│   │   │   └── LoyaltyController.php
│   │   │
│   │   ├── endpoints/
│   │   │   ├── CustomerEndpoint.php
│   │   │   ├── GroupEndpoint.php
│   │   │   ├── ContactEndpoint.php
│   │   │   ├── InteractionEndpoint.php
│   │   │   ├── NoteEndpoint.php
│   │   │   └── LoyaltyEndpoint.php
│   │   │
│   │   ├── models/
│   │   │   ├── CustomerModel.php
│   │   │   ├── GroupModel.php
│   │   │   ├── ContactModel.php
│   │   │   ├── InteractionModel.php
│   │   │   ├── NoteModel.php
│   │   │   └── LoyaltyModel.php
│   │   │
│   │   ├── validators/
│   │   │   ├── CustomerValidator.php
│   │   │   ├── GroupValidator.php
│   │   │   ├── ContactValidator.php
│   │   │   ├── InteractionValidator.php
│   │   │   ├── NoteValidator.php
│   │   │   └── LoyaltyValidator.php
│   │   │
│   │   └── views/
│   │       ├── customer/
│   │       ├── group/
│   │       ├── contact/
│   │       ├── interaction/
│   │       ├── note/
│   │       └── loyalty/
│
│   ├── booking/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── academy/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── staff/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── location/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── payment/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── report/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── notification/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── audit/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   ├── crawler/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── models/
│   │   ├── validators/
│   │   └── views/
│
│   └── website/
│       ├── routes/
│       │   └── web.php
│       │
│       ├── controllers/
│       │   ├── HomeController.php
│       │   ├── PageController.php
│       │   ├── ShopController.php
│       │   ├── CartController.php
│       │   └── AuthController.php
│       │
│       ├── models/
│       │   ├── HomeModel.php
│       │   ├── PageModel.php
│       │   ├── ShopModel.php
│       │   ├── CartModel.php
│       │   └── AuthModel.php
│       │
│       ├── validators/
│       │   └── AuthValidator.php
│       │
│       ├── middlewares/
│       │   └── AuthMiddleware.php
│       │
│       └── views/
│           ├── layouts/
│           │   ├── master.php
│           │   ├── header.php
│           │   └── footer.php
│           │
│           ├── components/
│           │   ├── product-card.php
│           │   ├── cart-item.php
│           │   ├── pagination.php
│           │   ├── breadcrumbs.php
│           │   └── flash.php
│           │
│           ├── home/
│           ├── pages/
│           ├── shop/
│           ├── cart/
│           ├── auth/
│           └── errors/
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