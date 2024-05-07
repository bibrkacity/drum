## Test task

https://docs.google.com/document/d/1g_6R0n4DS6rTyWS1BciNdREfJl2oA2qst04tE5c5CLo/edit

## Installation

- Clone repository from [https://github.com/bibrkacity/drum.git](https://github.com/bibrkacity/drum.git).
- Go to root folder of project
- Save **/.env.example** as **/.env**
- Edit **/.env** - write your parameters of MySQL connection
- Run **composer install**
- Run migrations **php artisan migrate**
- Run **php artisan db:seed --force**
- Update API-docs **php artisan l5-swagger:generate**
- Run **php artisan serve**
- Go to [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser
- Enjoy!

## API documentation

Please read [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation). It is not only docs: it also online-tests od API-methods.

Authorization: bearer token in header of request. You can get token from login endpoint (see first endpoint in docs). Credentials: 
- Email: **admin@admin.com**
- Password: **password**

Id of this user equals 1
