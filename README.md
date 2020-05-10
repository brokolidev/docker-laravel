# Local env settings for Laravel app

_Docker가 설치되어 있어야 합니다. [다운로드](https://www.docker.com/get-started)_

## 사용방법

1. 먼저 mysql 디렉토리를 생성합니다.:

   ```
   mkdir mysql
   ```

2. docker 이미지를 불러온 뒤 실행합니다.:

   ```
   docker-compose build && docker-compose up -d
   ```

3. 이후에는 일반적으로 라라벨을 클론했을 때 프로세스를 따릅니다.

   ```
   cd ./src
   composer install
   copy .env.example .env
   php artisan key:generate
   ```

4. 브라우저로 localhost:8088에 접속하여 정상 실행되는지 확인합니다. 현재 설치된 라라벨 버전은 7.10.2로 다른 버전을 설치하고 싶다면 src 폴더를 제거한 뒤 src폴더에 새로운 라라벨 버전을 설치하면 됩니다.

5. DB 마이그레이션을 위해 .env 설정을 변경해줍니다. `./src/.env`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=homestead
   DB_USERNAME=homestead
   DB_PASSWORD=secret
   ```

6. DB 마이그레이션을 실행합니다. Docker 컨테이너 내에서 실행되어야 하므로 아래와 같이 실행합니다.

   ```
   docker-compose exec php php /var/www/html/artisan migrate
   ```

7. 테스트를 위해 seeding data를 생성합니다.

   ```
   docker-compose exec php php /var/www/html/artisan tinker
   >> factory(App\Order::class, 500)->create();
   ```

# API Documentation

## 회원가입

회원가입을 처리하는 api 입니다. 성공시 해당 유저 정보를 반환합니다.

```http
POST /api/register
```

| Parameter       | Type     | Description                                                                          |
| :-------------- | :------- | :----------------------------------------------------------------------------------- |
| `name`          | `string` | **필수**. 회원 이름(한글, 영문 대소문자)                                             |
| `nickname`      | `string` | **필수**. 회원 별명(영문 소문자)                                                     |
| `password`      | `string` | **필수**. 비밀번호(영문 대문자, 영문 소문자, 특수문자, 숫자 각 1개 이상씩 필수 포함) |
| `passwordCheck` | `string` | **필수**. 비밀번호확인                                                               |
| `phone`         | `string` | **필수**. 전화번호                                                                   |
| `email`         | `string` | **필수**. 이메일 주소                                                                |
| `gender`        | `string` | _선택_. 성별                                                                         |

## Responses

```javascript
{
    "name": "tester",
    "nickname": "tester",
    "phone": "01012341234",
    "email": "tester@tester.com",
    "gender": "m",
    "updated_at": "2020-05-09T06:02:57.000000Z",
    "created_at": "2020-05-09T06:02:57.000000Z",
    "id": 947
}
```

## 회원 로그인(인증)

회원 로그인을 처리합니다. 성공시 해당 유저 정보와 생성된 토큰 정보를 반환합니다. 이 토큰을 사용하여 Bearer Authentication을 처리할 수 있습니다. 이 plainText 값은 로그인시 한 번만 반환되므로 이 정보를 별도의 공간에 저장 후 인증이 필요한 api 요청시 이용합니다.

```http
POST /api/login
```

| Parameter  | Type     | Description             |
| :--------- | :------- | :---------------------- |
| `email`    | `string` | **필수**. 가입된 이메일 |
| `password` | `string` | **필수**. 비밀번호      |

## Responses

```javascript
{
  "id": 79,
  "name": "한글gs",
  "nickname": "test",
  "email": "gang.juwoong@example.org",
  "email_verified_at": null,
  "phone": "0102341234",
  "gender": "m",
  "created_at": "2020-05-08T08:09:13.000000Z",
  "updated_at": "2020-05-08T08:09:13.000000Z",
  "token": {
    "accessToken": {
      "name": "app-token",
      "abilities": [
        "*"
      ],
      "tokenable_id": 79,
      "tokenable_type": "App\\User",
      "updated_at": "2020-05-08T08:26:13.000000Z",
      "created_at": "2020-05-08T08:26:13.000000Z",
      "id": 3
    },
    "plainTextToken": "3|tEosZ4FcAyY69tvR1EV43U9YA3lum7hRSNjPUphZt1sdopFJtPNriFn9vn8SbXxBVILhmE07dCbNSCRV"
  }
}
```

## 회원 로그아웃

로그아웃을 처리합니다. 인증된 사용자만 요청할 수 있으며 성공시 해당 유저에게 발급된 모든 토큰을 삭제합니다.

```http
GET /api/logout
```

## Responses

```javascript
{
    "result": true,
    "message": "로그아웃 성공"
}
```

## 단일회원 상세정보 조회

지정한 회원의 상세정보를 가져옵니다.

```http
GET /api/user/{id}
```

| Parameter | Type  | Description                   |
| :-------- | :---- | :---------------------------- |
| `id`      | `int` | **필수**. 가입된 회원의 id 값 |

## Responses

```javascript
{
    "id": 947,
    "name": "tester",
    "nickname": "tester",
    "email": "tester@tester.com",
    "email_verified_at": null,
    "phone": "01012341234",
    "gender": "m",
    "created_at": "2020-05-09T06:02:57.000000Z",
    "updated_at": "2020-05-09T06:02:57.000000Z"
}
```

## 단일회원 주문목록 조회

지정한 회원의 주문 내역을 가져옵니다.

```http
GET /api/user/{id}/orders
```

| Parameter | Type  | Description                   |
| :-------- | :---- | :---------------------------- |
| `id`      | `int` | **필수**. 가입된 회원의 id 값 |

## Responses

```javascript
[
  {
    id: 3,
    user_id: 947,
    order_num: "651ECA3F9000",
    product_name: "Perferendis similique esse est quis vitae quis inventore.",
    created_at: "2020-05-09T05:11:22.000000Z",
    updated_at: "2020-05-09T05:11:22.000000Z",
  },
  {
    id: 162,
    user_id: 947,
    order_num: "73FC387F3AC8",
    product_name:
      "Dolorem inventore ratione et voluptatem veritatis deleniti consequuntur.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 163,
    user_id: 947,
    order_num: "58A43873F4FE",
    product_name: "Ducimus voluptas magnam nihil suscipit nostrum nulla rerum.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 164,
    user_id: 947,
    order_num: "8AB5CA1650B4",
    product_name: "Veniam quis eligendi qui voluptas.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 165,
    user_id: 947,
    order_num: "46017D93F952",
    product_name: "Distinctio rerum esse impedit nihil et vel.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 167,
    user_id: 947,
    order_num: "B300DEA5E06F",
    product_name: "In odit debitis hic provident atque sint et.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 168,
    user_id: 947,
    order_num: "F681B2603317",
    product_name:
      "Blanditiis molestias aut sapiente non explicabo deserunt saepe.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
  {
    id: 169,
    user_id: 947,
    order_num: "399EE5116692",
    product_name: "Asperiores repudiandae unde quasi est.",
    created_at: "2020-05-09T05:13:22.000000Z",
    updated_at: "2020-05-09T05:13:22.000000Z",
  },
];
```

## 여러 회원 목록 조회

회원 리스트를 페이지 단위로 가져옵니다. 단위는 pageLimit을 통해 변경할 수 있으며, 기본값은 15개씩 가져옵니다. 각 유저 정보에는 마지막 주문정보가 포함되어 있습니다.

```http
POST /api/users
```

| Parameter   | Type     | Description                  |
| :---------- | :------- | :--------------------------- |
| `name`      | `string` | _선택_. 검색할 아이디        |
| `email`     | `string` | _선택_. 검색할 이메일 주소   |
| `pageLimit` | `int`    | _선택_. 한번에 노출되는 갯수 |

## Responses

```javascript
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "문은희",
            "nickname": "nno",
            "email": "sanghun.bae@example.net",
            "email_verified_at": "2020-05-09T05:10:56.000000Z",
            "phone": "038-6372-9146",
            "gender": "m",
            "created_at": "2020-05-09T05:10:56.000000Z",
            "updated_at": "2020-05-09T05:10:56.000000Z",
            "last_order": null
        },
        {
            "id": 2,
            "name": "권채현",
            "nickname": "mijung.kim",
            "email": "juhee99@example.net",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "010-1437-7798",
            "gender": null,
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 3,
            "name": "마정란",
            "nickname": "jung.changyoung",
            "email": "ushin@example.net",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "066-5625-1089",
            "gender": "f",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 4,
            "name": "안종수",
            "nickname": "cheon.sunyup",
            "email": "mjung@example.net",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "1872-6263",
            "gender": "f",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 5,
            "name": "한희원",
            "nickname": "minhee48",
            "email": "junho36@example.com",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "058-8833-1546",
            "gender": "m",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 6,
            "name": "고영하",
            "nickname": "ojeon",
            "email": "son.daesun@example.org",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "1648-8421",
            "gender": "m",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 7,
            "name": "지한나",
            "nickname": "yeji98",
            "email": "lim.jungnam@example.com",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "042-4214-2527",
            "gender": null,
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 8,
            "name": "송정민",
            "nickname": "jongju69",
            "email": "yuri21@example.org",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "02-3664-9918",
            "gender": "f",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 9,
            "name": "맹진호",
            "nickname": "bom.ahn",
            "email": "dongyoon.son@example.org",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "1550-3951",
            "gender": null,
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": null
        },
        {
            "id": 10,
            "name": "변민재",
            "nickname": "icheon",
            "email": "sungmin84@example.net",
            "email_verified_at": "2020-05-09T05:11:22.000000Z",
            "phone": "02-5684-4978",
            "gender": "f",
            "created_at": "2020-05-09T05:11:22.000000Z",
            "updated_at": "2020-05-09T05:11:22.000000Z",
            "last_order": {
                "id": 10,
                "user_id": 10,
                "order_num": "E8C08105D4FB",
                "product_name": "Voluptatum delectus et expedita porro exercitationem.",
                "created_at": "2020-05-09T05:11:22.000000Z",
                "updated_at": "2020-05-09T05:11:22.000000Z"
            }
        }
    ],
    "first_page_url": "http://localhost:8088/api/users?page=1",
    "from": 1,
    "last_page": 95,
    "last_page_url": "http://localhost:8088/api/users?page=95",
    "next_page_url": "http://localhost:8088/api/users?page=2",
    "path": "http://localhost:8088/api/users",
    "per_page": 10,
    "prev_page_url": null,
    "to": 10,
    "total": 945
}
```
