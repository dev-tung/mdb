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
│   └── Middleware.php
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── modules/
│
│   ├── website/
│   │
│   │   ├── routes/
│   │   │   └── web.php
│   │   │
│   │   ├── controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── PageController.php
│   │   │   ├── ShopController.php
│   │   │   ├── BookingController.php
│   │   │   └── AcademyController.php
│   │   │
│   │   └── views/
│   │       ├── home/
│   │       ├── pages/
│   │       │   ├── about.php
│   │       │   ├── contact.php
│   │       │   ├── faq.php
│   │       │   └── terms.php
│   │       │
│   │       ├── shop/
│   │       │   ├── product-list.php
│   │       │   ├── product-detail.php
│   │       │   ├── cart.php
│   │       │   └── checkout.php
│   │       │
│   │       ├── booking/
│   │       └── academy/
│
│
│   ├── shop/
│   │
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
│
│   ├── booking/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│   │
│   │       ├── court/
│   │       ├── booking/
│   │       ├── calendar/
│   │       └── pricing/
│
│
│   ├── academy/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│   │
│   │       ├── student/
│   │       ├── coach/
│   │       ├── class/
│   │       ├── attendance/
│   │       └── tuition/
│
│
│   ├── customer/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│
│
│   ├── staff/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│
│
│   ├── location/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│   │
│   │       ├── location/
│   │       ├── branch/
│   │       ├── court/
│   │       ├── room/
│   │       ├── facility/
│   │       └── working-hour/
│
│
│   ├── payment/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   ├── validators/
│   │   └── views/
│
│
│   ├── report/
│   │   ├── routes/
│   │   ├── controllers/
│   │   ├── endpoints/
│   │   └── views/
│   │
│   │       ├── dashboard/
│   │       ├── revenue/
│   │       ├── booking/
│   │       ├── academy/
│   │       ├── customer/
│   │       ├── staff/
│   │       ├── inventory/
│   │       └── finance/
│
│
│   ├── notification/
│   │
│   ├── audit/
│   │
│   └── crawler/
│       ├── clients/
│       ├── parsers/
│       ├── mappers/
│       └── jobs/
│
│
├── shared/
│   ├── tools/
│   ├── helpers/
│   ├── cache/
│   ├── mail/
│   └── partials/
│
└── public/
    ├── index.php
    └── assets/