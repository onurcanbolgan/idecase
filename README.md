# idecase

Bu proje, Laravel kullanarak oluşturulmuş bir RESTful API örneğidir. API, müşteriler, siparişler, ürünler, kategoriler ve indirim hesaplama gibi temel işlemleri yönetir.

## Yönlendirmeler

API rotaları aşağıdaki gibidir:


### Müşteriler (Customers)

localhost/api/customers

- `GET /` : Tüm müşterileri getirir.
- `POST /` : Yeni müşteri oluşturur.
- `GET /{customerId}` : Belirli bir müşteriyi getirir.
- `PUT /{customerId}` : Belirli bir müşteriyi günceller.
- `DELETE /{customerId}` : Belirli bir müşteriyi siler.

### POST ve PUT için Örnek JSON:

```json
{
    "name": "Müşteri 4",
    "since": "2014-06-28",
    "revenue": "492.12"
}
```

### Siparişler (Orders)

localhost/api/orders

- `GET /` : Tüm siparişleri getirir.
- `POST /` : Yeni sipariş oluşturur.
- `GET /{orderId}` : Belirli bir siparişi getirir.
- `PUT /{orderId}` : Belirli bir siparişi günceller.
- `DELETE /{orderId}` : Belirli bir siparişi siler.

### POST ve PUT için Örnek JSON:
```json
{
    "customerId": 3,
    "items": [
        {
            "productId": 6,
            "quantity": 2
        },
        {
            "productId": 7,
            "quantity": 2
        },
        {
            "productId": 8,
            "quantity": 4
        },
        {
            "productId": 1,
            "quantity": 1
        }
    ]
}
```

### Ürünler (Products)

localhost/api/products

- `GET /` : Tüm ürünleri getirir.
- `POST /` : Yeni ürün oluşturur.
- `GET /{productId}` : Belirli bir ürünü getirir.
- `PUT /{productId}` : Belirli bir ürünü günceller.
- `DELETE /{productId}` : Belirli bir ürünü siler.

### POST ve PUT için Örnek JSON:
```json
{
    "name": "Ürün 11",
    "category": 1,
    "price": "200.95",
    "stock": 10
}
```


### Kategoriler (Categories)

localhost/api/categories

- `GET /` : Tüm kategorileri getirir.
- `POST /` : Yeni kategori oluşturur.
- `GET /{categoryId}` : Belirli bir kategoriyi getirir.
- `PUT /{categoryId}` : Belirli bir kategoriyi günceller.
- `DELETE /{categoryId}` : Belirli bir kategoriyi siler.

### POST ve PUT için Örnek JSON:
```json
{
    "name": "Kategori 4"
}
```


### İndirim Hesaplama

- `GET /{orderId}` : Belirli bir sipariş için indirim hesaplar.

### Laravel Projesini Yerel Sunucuda Çalıştırmak
- cd idecase
- composer install
- php artisan migrate
- php artisan serve

Proje http://localhost:8000 adresinde çalışacaktır.

### Laravel Projesini Docker ile Çalıştırmak
- cd idecase
- docker-compose up --build
- docker exec -it app_container php artisan migrate

Proje http://localhost adresinde çalışacaktır.


