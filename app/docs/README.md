# PHP Modular MVC Full Coding Convention

---

# 1. Core Principles

- One file = one responsibility.
- Controllers must remain thin.
- Services contain business logic.
- Repositories are responsible for data access only.
- Views are responsible for presentation only.
- No SQL queries inside Controllers.
- No business logic inside Views.
- Strict separation between layers.
- Code must be modular, scalable, maintainable, and predictable.

---

# 2. Naming Convention

## Classes, Methods, Variables, and Constants

### Class Names

Use **PascalCase**.

Example:

```php
ProductController
OrderService
UserRepository
```

### Method Names

Use **camelCase**.

Example:

```php
getProductList()
createOrder()
findUserById()
```

### Variable Names

Use **camelCase**.

Example:

```php
$productList
$currentUser
$orderItems
```

### Constant Names

Use **UPPER_SNAKE_CASE**.

Example:

```php
MAX_UPLOAD_SIZE
DEFAULT_LANGUAGE
CACHE_LIFETIME
```

---

## Module Names

Module names must:

- Use lowercase letters only.
- Prefer singular nouns.

Examples:

```txt
admin
shop
website
booking
```

---

## Folder Naming

### Architecture Folders

Use lowercase singular names.

Examples:

```txt
app
core
config
public
storage
common
```

### Collection Folders

Use lowercase plural names when the folder contains multiple items of the same type.

Examples:

```txt
modules
controllers
services
repositories
validators
views
assets
middleware
```

---

# 3. Route (Router) Rules

## URL Convention

URLs must represent resources and follow RESTful conventions.

### Resource URLs

Always use plural nouns for resources.

Examples:

```txt
/admin/products
/admin/categories
/admin/orders
/admin/users
```

### Route Definitions

```php
Router::get('/admin/products', 'ProductController@index');

Router::get('/admin/products/{id}', 'ProductController@show');

Router::post('/admin/products', 'ProductController@store');

Router::put('/admin/products/{id}', 'ProductController@update');

Router::delete('/admin/products/{id}', 'ProductController@destroy');
```

### RESTful Actions

| HTTP Method | URL                           | Action    | Description          |
|------------|-------------------------------|-----------|----------------------|
| GET        | /admin/products               | index     | Display all records  |
| GET        | /admin/products/{id}          | show      | Display one record   |
| POST       | /admin/products               | store     | Create a new record  |
| PUT        | /admin/products/{id}          | update    | Update a record      |
| DELETE     | /admin/products/{id}          | destroy   | Delete a record      |

---

# 4. Layer Responsibilities

## Controller

Responsibilities:

- Receive HTTP requests.
- Validate request flow.
- Call Services.
- Return Views or Responses.

Controllers should not:

- Execute SQL queries.
- Contain business logic.

---

## Service

Responsibilities:

- Contain all business logic.
- Coordinate repositories.
- Process application rules.

Services should not:

- Render HTML.
- Access HTTP directly.

---

## Repository

Responsibilities:

- Communicate with the database.
- Execute queries.
- Return data objects or arrays.

Repositories should not:

- Contain business rules.
- Handle HTTP requests.

---

## View

Responsibilities:

- Render UI only.
- Display prepared data.

Views should not:

- Execute SQL.
- Contain business logic.

---

# 5. General Rules

- Follow the Single Responsibility Principle (SRP).
- Keep methods small and focused.
- Avoid duplicated code (DRY).
- Prefer dependency injection over tight coupling.
- Write reusable and testable code.
- Keep naming explicit and meaningful.
- Maintain consistent project structure.