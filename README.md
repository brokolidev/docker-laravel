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
