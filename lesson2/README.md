## Домашка.
**Всем без исключения:**  
- установить cURL на домашний компьютер;
- установить make;
- результаты домашки записать на видео. (например, с помощью https://www.teamviewer.com/ru/info/screen-recording/); 
- залить код домашки в новую ветку уже созданного репозитория, добавить описание, что проделано вами в hw-2.

**На "3".**  
Скачать файлы с урока и запустить в докере.  

**На "4".**
- изменить www.config или создать свой новый; 
- добавить в конфигурацию nginx отдельные location (по одному на группу) для статики:
  - документов;
  - изображений; 
  - видео; 
- добавить хотя бы в один раздел конфигурацию кеширования на уровне nginx; 
- продемонстрировать, что файлы отдаются.

**На "5".**  
- добавить в конфигурацию php-fpm ещё один пул;
- настроить nginx на ещё один пул;
- продемонстрировать, что nginx может работать с двумя пулами.


## Что может помочь для выполнения домашки.
### Образы докера скачаны с оф сайтов:
https://hub.docker.com/_/php
https://hub.docker.com/_/nginx

### Теория и документация.
**HTTP**:  
https://habr.com/ru/post/215117/  
https://habr.com/ru/post/221427/  
https://www.8host.com/blog/v-chem-raznica-mezhdu-http1-1-i-http2/  

**cURL**:  
https://www.keycdn.com/support/popular-curl-examples  

**Nginx + PHP-FPM**:  
https://serversforhackers.com/s/managing-php-fpm (серия видео про управление пулами)  
https://www.nginx.com/resources/wiki/start/topics/examples/phpfcgi/ (как подружить)  
https://www.php.net/manual/en/install.unix.nginx.php  (как подружить)  
https://nginx.org/ru/docs/beginners_guide.html (документация nginx)  

**Makefile**  
https://www.gnu.org/software/make/manual/make.html  
https://www.gnu.org/software/make/manual/make.html#Introduction  

**Бонус**:  
**как настроить кеширование ответов в nginx**: попробуйте погуглить :), если не найдёте за 5 минут понятную документацию, напишите, я помогу.

### Полезные команды.  
**Референс команд докера**: https://docs.docker.com/engine/reference/commandline/docker/  
Войти в запущенный инстанс  
```
docker exec -it [container_name] sh
# или
docker exec -it [container_name] bash
```
Выполнить в запущенном инстансе команду  
```
docker exec [container_name] [command]
```
Посмотреть логи контейнера  
```
docker logs -f [container_name]
```

Команды консоли:
```
# cat somefile.txt - вывести содержимое файла
cat 
# содержимое текущей директории
ls -lah
# запущенные процессы
top
# cat somefile.txt | grep 'some string in file' - найти строку в теле файла
grep
```

### Почему не завелось на уроке.

Анализ проблемы "не смогли отправить запрос из nginx в php-fpm пул symfony":  
- скопипастили listen = 127.0.0.1:9000 с дефолтного пула www (а ведь это же локалхост, т.е. мы слушаем локальный адрес, а nginx с которого идёт запрос - это внешний ресурс);
- торопились и не обратили внимание, потому что работало (так бывает, проблема всегда под носом);
- работало всё, потому что был ещё один конфиг, который переопределял значение listen;
- можно тут почитать https://github.com/docker-library/php/issues/241.

Как посмотреть:
```
# список конфигов пулов в php-fpm
docker exec project-php ls -lah /usr/local/etc/php-fpm.d/
# что было прописано в дефолтном пуле www
docker exec project-php cat /usr/local/etc/php-fpm.d/www.conf | grep 'listen = '
# как переопределилось значение listen для пула www
docker exec project-php cat /usr/local/etc/php-fpm.d/zz-docker.conf
```
Как должно было быть для symfony.conf пула, смотрите в приложенных файлах, больше ничего нигде не нужно менять.
